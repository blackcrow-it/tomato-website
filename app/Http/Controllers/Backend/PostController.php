<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Constants\ObjectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PostRequest;
use App\Post;
use App\PostPosition;
use App\PostRelatedCourse;
use App\PostRelatedPost;
use App\Repositories\PostRepo;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Log;
use Route;

class PostController extends Controller
{
    private $postRepo;

    public function __construct(PostRepo $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    public function list(Request $request)
    {
        $list = $this->postRepo->getByFilterQuery($request->input('filter'))->paginate();

        return view('backend.post.list', [
            'list' => $list,
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function add()
    {
        return view('backend.post.edit', [
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitAdd(PostRequest $request)
    {
        $post = new Post();

        try {
            DB::beginTransaction();
            $this->processPostFromRequest($request, $post);
            DB::commit();

            return redirect()
                ->route('admin.post.list')
                ->with('success', 'Thêm bài viết mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.post.list')
                ->withErrors('Thêm bài viết mới thất bại.');
        }
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        $post->__template_position = $post->position->pluck('code')->toArray();

        return view('backend.post.edit', [
            'data' => $post,
            'categories' => $this->getCategoriesTraverse()
        ]);
    }

    public function submitEdit(PostRequest $request, $id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $this->processPostFromRequest($request, $post);
            DB::commit();

            return redirect()
                ->route('admin.post.list')
                ->with('success', 'Thay đổi bài viết mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.post.list')
                ->withErrors('Thay đổi bài viết mới thất bại.');
        }
    }

    private function processPostFromRequest(Request $request, Post $post)
    {
        $data = $request->all();
        $post->fill($data);
        $post->save();

        $positionData = PostPosition::where('post_id', $post->id)->get();
        PostPosition::where('post_id', $post->id)->delete();
        $templatePositionCodeArray = $request->input('__template_position', []);
        foreach ($templatePositionCodeArray as $code) {
            $position = new PostPosition();
            $position->code = $code;
            $position->post_id = $post->id;
            $position->order_in_position = $positionData->firstWhere('code', $code)->order_in_position ?? 0;
            $position->save();
        }

        PostRelatedPost::where('post_id', $post->id)->delete();
        $relatedPostIds = $request->input('__related_posts', []);
        foreach ($relatedPostIds as $relatedPostId) {
            $related = new PostRelatedPost();
            $related->post_id = $post->id;
            $related->related_post_id = $relatedPostId;
            $related->save();
        }

        PostRelatedCourse::where('post_id', $post->id)->delete();
        $relatedCourseIds = $request->input('__related_courses', []);
        foreach ($relatedCourseIds as $relatedCourseId) {
            $related = new PostRelatedCourse();
            $related->post_id = $post->id;
            $related->related_course_id = $relatedCourseId;
            $related->save();
        }
    }

    public function submitDelete($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        try {
            DB::beginTransaction();
            $post->delete();
            DB::commit();

            return redirect()
                ->route('admin.post.list')
                ->with('success', 'Xóa bài viết mới thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);

            return redirect()
                ->route('admin.post.list')
                ->withErrors('Xóa bài viết mới thất bại.');
        }
    }

    public function submitEnabled(Request $request)
    {
        $post = Post::findOrFail($request->input('id'));
        $post->enabled = $request->input('enabled');
        $post->timestamps = false;
        $post->save();
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::where('type', ObjectType::POST)
                ->orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function submitOrderInCategory(Request $request)
    {
        $post = Post::findOrFail($request->input('id'));
        $post->order_in_category = intval($request->input('order_in_category'));
        $post->timestamps = false;
        $post->save();
    }

    public function submitOrderInPosition(Request $request)
    {
        $position = PostPosition::where([
            'post_id'   => $request->input('id'),
            'code'      => $request->input('code')
        ])->firstOrFail();
        $position->order_in_position = intval($request->input('order_in_position'));
        $position->timestamps = false;
        $position->save();
    }

    public function getSearchPost(Request $request)
    {
        $keyword = $request->input('keyword');
        if (empty($keyword)) return [];

        $query = Post::where('enabled', true)
            ->orderBy('updated_at', 'desc');

        if (strpos($keyword, config('app.url')) === 0) {
            $route = Route::getRoutes()->match(Request::create($keyword));
            $query->where('slug', $route->slug);
        } else {
            $query->where('title', 'ilike', "%$keyword%");
        }

        return $query->get();
    }

    public function getRelatedPost(Request $request)
    {
        $id = $request->input('id');
        return PostRelatedPost::with('related_post')
            ->where('post_id', $id)
            ->get()
            ->pluck('related_post');
    }

    public function getRelatedCourse(Request $request)
    {
        $id = $request->input('id');
        return PostRelatedCourse::with('related_course')
            ->where('post_id', $id)
            ->get()
            ->pluck('related_course');
    }
}
