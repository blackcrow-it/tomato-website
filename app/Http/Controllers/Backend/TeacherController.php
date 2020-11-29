<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function list() {
        $list = Teacher::orderBy('created_at', 'desc')->paginate();

        return view('backend.teacher.list', [
            'list' => $list
        ]);
    }

    public function add()
    {
        return view('backend.teacher.edit');
    }

    public function submitAdd(Request $request)
    {
        $teacher = new Teacher;
        $teacher->fill($request->input('teacher'));
        $teacher->save();

        return redirect()
            ->route('admin.teacher.list')
            ->with('success', 'Thêm giảng viên mới thành công.');
    }

    public function edit($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher == null) {
            return redirect()->route('admin.teacher.list')->withErrors('Giảng viên không tồn tại hoặc đã bị xóa.');
        }

        return view('backend.teacher.edit', [
            'teacher' => $teacher
        ]);
    }

    public function submitEdit(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if ($teacher == null) {
            return redirect()->route('admin.teacher.list')->withErrors('Giảng viên không tồn tại hoặc đã bị xóa.');
        }

        $teacher->fill($request->input('teacher'));
        $teacher->save();

        return redirect()
            ->route('admin.teacher.list')
            ->with('success', 'Thông tin giảng viên thay đổi thành công.');
    }

    public function submitDelete($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher == null) {
            return redirect()->route('admin.teacher.list')->withErrors('Giảng viên không tồn tại hoặc đã bị xóa.');
        }

        if ($teacher->courses()->count() > 0) {
            return redirect()->route('admin.teacher.list')->withErrors('Giảng viên đang liên kết với khóa học. Không thể xóa.');
        }

        $teacher->delete();

        return redirect()
            ->route('admin.teacher.list')
            ->with('success', 'Xóa giảng viên thành công.');
    }
}
