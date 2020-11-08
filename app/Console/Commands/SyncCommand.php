<?php

namespace App\Console\Commands;

use App\Category;
use App\Course;
use App\Post;
use App\User;
use App\UserCourse;
use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Console\Command;
use Storage;
use Str;

class SyncCommand extends Command
{
    protected $signature = 'sync';
    protected $description = 'Command description';

    private $oldConn = null;
    private $newConn = null;

    public function __construct()
    {
        parent::__construct();

        $this->oldConn = DB::connection('mysql');
        $this->newConn = DB::connection('pgsql');
    }

    public function handle()
    {
        try {
            DB::beginTransaction();

            $this->syncCategories();
            $this->syncPosts();
            $this->syncCourses();
            $this->syncUsers();
            $this->syncUserCourses();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    private function syncUserCourses() {
        echo "syncUsers\n";

        $oldUserCourses = $this->oldConn->table('user_course')
            ->leftJoin('user', 'user.user_id', '=', 'user_course.user_id')
            ->leftJoin('course', 'course.course_id', '=', 'user_course.course_id')
            ->select([
                'user_course.user_course_id',
                'user.email',
                'course.title',
                'user_course.time',
                'user_course.expired_days',
            ])
            ->get();

        $syncErrors = [];

        echo "Found: " . $oldUserCourses->count() . "\n";

        foreach ($oldUserCourses as $item) {
            $expiresOn = Carbon::createFromTimestamp($item->time)->addDays($item->expired_days);
            if ($item->expired_days != '-1' && $expiresOn < Carbon::now()) {
                continue;
            }

            if ($item->email == null || $item->title == null) {
                $syncErrors[] = $item;
                continue;
            }

            $newUser = User::where('email', $item->email)->first();
            $newCourse = Course::where('title', $item->title)->first();

            if ($newUser == null || $newCourse == null) {
                $syncErrors[] = $item;
                continue;
            }

            $newData = new UserCourse();
            $newData->user_id = $newUser->id;
            $newData->course_id = $newCourse->id;
            $newData->expires_on = $item->expired_days != '-1' ? $expiresOn : null;
            $newData->save();
        }

        dump($syncErrors);
    }

    private function syncUsers()
    {
        echo "syncUsers\n";

        $oldUsers = $this->oldConn->table('user')
            ->get();

        echo "Found: " . $oldUsers->count() . "\n";

        for ($i = 0; $i < $oldUsers->count(); $i++) {
            $user = $oldUsers[$i];

            echo "Syncing (" . $i . " / " . $oldUsers->count() . "): " . $user->email . "\n";

            $data = [
                'name' => $user->name,
                'username' => $user->email,
                'email' => $user->email,
                'password' => Hash::make(Str::random()),
                'avatar' => $user->avatar,
                'phone' => $user->mobile,
                'address' => $user->address
            ];
            foreach ($data as $attr => $value) {
                if ($value !== '') continue;
                $data[$attr] = null;
            }

            if (User::where('email', $user->email)->exists()) continue;

            $newUser = new User();
            $newUser->fill($data);
            $newUser->email_verified_at = Carbon::now();
            $newUser->google_id = $user->google_id ?: null;
            $newUser->money = $user->coin;
            $newUser->created_at = Carbon::createFromTimestamp($user->reg_time);
            $newUser->save();
        }
    }

    private function syncCourses()
    {
        echo "syncCourses\n";

        $newCourses = Course::all();

        foreach ($newCourses as $newCourse) {
            if ($newCourse->description == null) continue;

            $oldCourse = $this->oldConn->table('course')
                ->where('course_id', $newCourse->description)
                ->first();

            if ($oldCourse == null) continue;

            echo "Syncing: " . $oldCourse->title . "\n";

            $content = preg_replace_callback('/<img(.*?) src="(.*?)"(.*?)>/', function ($matches) {
                return '<img' . $matches[1] . ' src="' . $this->syncFileToS3($matches[2]) . '"' . $matches[3] . '>';
            }, $oldCourse->content);

            $data = [
                'title' => $oldCourse->title,
                'slug' => $oldCourse->slug,
                'thumbnail' => $this->syncFileToS3($oldCourse->avatar),
                'description' => $oldCourse->description,
                'content' => $content,
                'meta_title' => $oldCourse->meta_title,
                'meta_description' => $oldCourse->meta_description,
                'og_title' => $oldCourse->og_title,
                'og_description' => $oldCourse->og_description,
                'og_image' => $this->syncFileToS3($oldCourse->og_image),
                'enabled' => $oldCourse->publish,
                'category_id' => $oldCourse->category_id ?? null,
                'view' => $oldCourse->views,
                'price' => $oldCourse->coin,
                'original_price' => $oldCourse->coin_linethrough,
                'intro_youtube_id' => get_youtube_id_from_url($oldCourse->intro),
                'buyer_days_owned' => $oldCourse->expired_days > 0 ? $oldCourse->expired_days : null,
            ];
            foreach ($data as $attr => $value) {
                if ($value !== '') continue;
                $data[$attr] = null;
            }

            $newCourse->fill($data);
            $newCourse->save();
        }
    }

    private function syncPosts()
    {
        echo "syncPosts\n";

        $oldPosts = $this->oldConn->table('post')
            ->orderBy('time', 'asc')
            ->get();

        echo "Found: " . $oldPosts->count() . "\n";

        foreach ($oldPosts as $item) {
            echo "Syncing: " . $item->title . "\n";

            $oldCategoryContent = $this->oldConn->table('category_content')
                ->where('type', 'post')
                ->where('object_id', $item->post_id)
                ->first();

            $content = preg_replace_callback('/<img(.*?) src="(.*?)"(.*?)>/', function ($matches) {
                return '<img' . $matches[1] . ' src="' . $this->syncFileToS3($matches[2]) . '"' . $matches[3] . '>';
            }, $item->content);

            $data = [
                'title' => $item->title,
                'slug' => $item->slug,
                'thumbnail' => $this->syncFileToS3($item->avatar),
                'description' => $item->description,
                'content' => $content,
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,
                'og_title' => $item->og_title,
                'og_description' => $item->og_description,
                'og_image' => $this->syncFileToS3($item->og_image),
                'enabled' => $item->publish,
                'category_id' => $oldCategoryContent->category_id ?? null,
            ];
            foreach ($data as $attr => $value) {
                if ($value !== '') continue;
                $data[$attr] = null;
            }

            $newPost = new Post();
            $newPost->fill($data);
            $newPost->created_at = Carbon::createFromTimestamp($item->time);
            $newPost->updated_at = Carbon::createFromTimestamp($item->time);
            $newPost->save();
        }
    }

    private function syncCategories($oldParentId = 0, $newParentId = null)
    {
        echo "syncCategories\n";

        $oldCategories = $this->oldConn->table('category')
            ->where('parent', $oldParentId)
            ->get();

        echo "Found: " . $oldCategories->count() . "\n";

        foreach ($oldCategories as $item) {
            echo "Syncing: " . $item->title . "\n";

            $data = [
                'title' => $item->title,
                'slug' => $item->slug,
                'icon' => $this->syncFileToS3($item->avatar),
                'description' => $item->description,
                'meta_title' => $item->meta_title,
                'meta_description' => $item->meta_description,
                'og_title' => $item->og_title,
                'og_description' => $item->og_description,
                'og_image' => $this->syncFileToS3($item->og_image),
                'headings' => $item->headings,
                'parent_id' => $newParentId,
                'type' => $item->type,
                'enabled' => true,
            ];
            foreach ($data as $attr => $value) {
                if ($value !== '') continue;
                $data[$attr] = null;
            }

            $newCategory = new Category();
            $newCategory->fill($data);
            $newCategory->id = $item->category_id;
            $newCategory->save();

            $this->syncCategories($item->category_id, $newCategory->id);
        }
    }

    private function syncFileToS3($url)
    {
        if (empty($url)) return null;

        $path = parse_url($url)['path'];

        if (Storage::disk('s3')->exists('files' . $path)) {
            return Storage::disk('s3')->url('files' . $path);
        }

        try {
            $client = new Client();
            $response = $client->get($url);

            $fileContent = $response->getBody();

            Storage::disk('s3')->put('files' . $path, $fileContent, 'public');

            return Storage::disk('s3')->url('files' . $path);
        } catch (Exception $ex) {
            return null;
        }
    }
}
