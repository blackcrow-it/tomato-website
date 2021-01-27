<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function list()
    {
        return view('backend.permission.list', [
            'list' => Role::orderBy('name', 'asc')->get()
        ]);
    }

    public function add()
    {
        return view('backend.permission.edit');
    }

    public function submitAdd(Request $request)
    {
        $roleName = $request->input('role.name');
        $permissions = $request->input('permissions', []);

        if (Role::where('name', $roleName)->exists()) {
            return redirect()->route('admin.permission.add')->withInput()->withErrors('Tên nhóm đã tồn tại.');
        }

        DB::transaction(function () use ($roleName, $permissions) {
            $role = Role::create([
                'name' => $roleName
            ]);
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName
                ]);
                $permission->assignRole($role);
            }
        });

        return redirect()->route('admin.permission.list')->with('success', 'Thêm nhóm thành công.');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if ($role == null) {
            return redirect()->route('admin.permission.list')->withErrors('Nhóm không tồn tại.');
        }

        return view('backend.permission.edit', [
            'role' => $role,
            'permissions' => $role->getPermissionNames()->toArray()
        ]);
    }

    public function submitEdit(Request $request, $id)
    {
        $role = Role::find($id);
        if ($role == null) {
            return redirect()->route('admin.permission.list')->withErrors('Nhóm không tồn tại.');
        }

        $roleName = $request->input('role.name');
        $permissions = $request->input('permissions', []);

        if (Role::where('id', '!=', $id)->where('name', $roleName)->exists()) {
            return redirect()->route('admin.permission.edit')->withInput()->withErrors('Tên nhóm đã tồn tại.');
        }

        DB::transaction(function () use ($role, $roleName, $permissions) {
            $role->name = $roleName;
            $role->save();

            $role->syncPermissions([]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName
                ]);
                $permission->assignRole($role);
            }

            Permission::has('roles', '=', 0)->delete();
        });

        return redirect()->route('admin.permission.list')->with('success', 'Sửa nhóm thành công.');
    }

    public function submitDelete($id)
    {
        $role = Role::find($id);
        if ($role == null) {
            return redirect()->route('admin.permission.list')->withErrors('Nhóm không tồn tại.');
        }

        DB::transaction(function () use ($role) {
            $role->delete();
            Permission::has('roles', '=', 0)->delete();
        });

        return redirect()->route('admin.permission.list')->with('success', 'Xóa nhóm thành công.');
    }
}
