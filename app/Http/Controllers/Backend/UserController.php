<?php

namespace App\Http\Controllers\Backend;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserRequest;
use App\Mail\UserMoneyChangedMail;
use App\Repositories\UserRepo;
use App\User;
use App\UserCourse;
use DB;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function list(Request $request)
    {
        $query = User::query();

        if ($request->input('filter.id')) {
            $query->where('id', $request->input('filter.id'));
        }

        if ($request->input('filter.username')) {
            $query->where('username', 'ilike', '%' . $request->input('filter.username') .  '%');
        }

        if ($request->input('filter.email')) {
            $query->where('email', 'ilike', '%' . $request->input('filter.email') .  '%');
        }

        if ($request->input('filter.name')) {
            $query->where('name', 'ilike', '%' . $request->input('filter.name') .  '%');
        }

        return view('backend.user.list', [
            'list' => $query->orderBy('created_at', 'desc')->paginate()
        ]);
    }

    public function add()
    {
        return view('backend.user.edit', [
            'roles' => Role::orderBy('name', 'asc')->get()
        ]);
    }

    public function submitAdd(UserRequest $request)
    {
        $data = $request->input();
        $data['username'] = mb_strtolower(trim($data['username']));
        $data['password'] = Hash::make($data['password']);

        $user = new User;
        $user->fill($data);
        $user->save();

        $role = Role::find($request->input('role_id'));
        $user->syncRoles([$role]);

        if ($request->input('money') !== null) {
            $this->userRepo->setMoney($user->id, $request->input('money'));
            $user->refresh();
        }

        if ($user->money > 0 && config('settings.email_notification')) {
            Mail::to(config('settings.email_notification'))
                ->send(
                    new UserMoneyChangedMail([
                        'user' => $user,
                        'old_money' => 0,
                        'amount' => $user->money
                    ])
                );
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
            'data' => $user,
            'roles' => Role::orderBy('name', 'asc')->get()
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

        $role = Role::find($request->input('role_id'));
        $user->syncRoles([$role]);

        if ($request->input('money') !== null) {
            $this->userRepo->setMoney($user->id, $request->input('money'));
        }

        $oldMoney = $user->money;
        $user->refresh();

        if ($user->money != $oldMoney && config('settings.email_notification')) {
            Mail::to(config('settings.email_notification'))
                ->send(
                    new UserMoneyChangedMail([
                        'user' => $user,
                        'old_money' => $oldMoney,
                        'amount' => $user->money - $oldMoney
                    ])
                );
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

    public function getSearchUser(Request $request)
    {
        $keyword = $request->input('keyword');
        // if (empty($keyword)) return [];

        $query = User::where('username', 'ilike', "%$keyword%")
            ->orWhere('email', 'ilike', "%$keyword%")
            ->orWhere('name', 'ilike', "%$keyword%")
            ->orderBy('username', 'asc')
            ->orderBy('email', 'asc');

        return $query->get();
    }
}
