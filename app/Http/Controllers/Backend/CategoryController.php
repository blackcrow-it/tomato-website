<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function list($id = null)
    {
        $category = Category::find($id);

        if ($category == null && $id != null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $list = Category::with('descendants')
            ->where('parent_id', $category->id ?? null)
            ->orderBy('id', 'ASC')
            ->paginate();

        return view('backend.category.list', [
            'list' => $list,
            'category' => $category
        ]);
    }

    public function add()
    {
        $categories = Category::get()->toTree();

        return view('backend.category.edit', [
            'categories' => categories_traverse($categories)
        ]);
    }

    public function submitAdd(CategoryRequest $request)
    {
        $category = new Category;

        $this->processPostFromRequest($request, $category);

        return redirect()
            ->route('admin.category.edit', [ 'id' => $category->id ])
            ->with('success', 'Thêm danh mục mới thành công.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $categories = Category::where('id', '!=', $category->id)->get()->toTree();

        return view('backend.category.edit', [
            'data' => $category,
            'categories' => categories_traverse($categories)
        ]);
    }

    public function submitEdit(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $this->processPostFromRequest($request, $category);

        return redirect()
            ->route('admin.category.edit', [ 'id' => $category->id ])
            ->with('success', 'Thay đổi danh mục thành công.');
    }

    public function processPostFromRequest(Request $request, Category $category)
    {
        $data = $request->input();
        $category->fill($data);

        $uploadResult = $this->uploadImages($request);
        $category->fill($uploadResult);

        $category->save();
    }

    public function submitDelete($id)
    {
        $category = Category::find($id);
        if ($category == null) {
            return redirect()->route('admin.category.list')->withErrors('Danh mục không tồn tại hoặc đã bị xóa.');
        }

        $category->delete();

        return redirect()
            ->route('admin.category.list')
            ->with('success', 'Xóa danh mục thành công.');
    }

    public function uploadImages(Request $request)
    {
        $result = [];

        if ($request->file('cover')) {
            $result['cover'] = Storage::cloud()->putFile('categories', $request->file('cover'), 'public');
        }

        if ($request->file('og_image')) {
            $result['og_image'] = Storage::cloud()->putFile('categories', $request->file('og_image'), 'public');
        }

        return $result;
    }
}
