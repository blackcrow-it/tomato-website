<?php

namespace App\Http\Controllers\Backend;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserRequest;
use App\Repositories\UserRepo;
use App\User;
use App\UserCourse;
use DB;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function list()
    {
        return view('backend.user.list', [
            'list' => User::orderBy('id', 'DESC')->paginate()
        ]);
    }

    public function add()
    {
        return view('backend.user.edit');
    }

    public function submitAdd(UserRequest $request)
    {
        $data = $request->input();
        $data['username'] = mb_strtolower(trim($data['username']));
        $data['password'] = Hash::make($data['password']);

        $user = new User;
        $user->fill($data);
        $user->save();

        if ($request->input('money') !== null) {
            $this->userRepo->setMoney($user->id, $request->input('money'));
        }

        foreach ($request->input('__user_courses', []) as $item) {
            UserCourse::insert([
                'course_id' => $item['course_id'],
                'user_id' => $user->id,
                'expires_on' => $item['expires_on'],
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()
            ->route('admin.user.list')
            ->with('success', 'Thêm thành viên mới thành công.');
    }

    public function edit($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.list')->withErrors('Thành viên không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.user.edit', [
            'data' => $user
        ]);
    }

    public function submitEdit(UserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.list')->withErrors('Thành viên không tồn tại hoặc đã bị xóa.');
        }

        $data = $request->input();
        $data['username'] = mb_strtolower(trim($data['username']));
        $data['email'] = mb_strtolower(trim($data['email']));

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->input('money') !== null) {
            $this->userRepo->setMoney($user->id, $request->input('money'));
        }

        DB::transaction(function () use ($request, $user) {
            UserCourse::where('user_id', $user->id)->delete();
            foreach ($request->input('__user_courses', []) as $item) {
                UserCourse::insert([
                    'course_id' => $item['course_id'],
                    'user_id' => $user->id,
                    'expires_on' => $item['expires_on'],
                    'deleted_at' => null,
                    'created_at' => $item['created_at'],
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()
            ->route('admin.user.list')
            ->with('success', 'Thông tin thành viên thay đổi thành công.');
    }

    public function submitDelete($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return redirect()->route('admin.user.list')->withErrors('Thành viên không tồn tại hoặc đã bị xóa.');
        }

        $user->delete();

        return redirect()
            ->route('admin.user.list')
            ->with('success', 'Xóa thành viên thành công.');
    }

    public function getUserCourses($id)
    {
        return UserCourse::query()
            ->with('course')
            ->where('user_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
