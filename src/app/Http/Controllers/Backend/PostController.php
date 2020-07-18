<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function list(Request $request)
    {
        $categories = Category::where('type', Category::TYPE_POST)
            ->get()
            ->toTree();

        $list = Post::with('owner')
            ->with('last_editor');

        if ($request->input('category_id')) {
            $categoryIds = Category::descendantsAndSelf($request->input('category_id'))
                ->pluck('id');

            $list = $list->whereIn('category_id', $categoryIds)
                ->orderByRaw('CASE WHEN order_in_category > 0 THEN 0 ELSE 1 END, order_in_category ASC, updated_at DESC');
        } else {
            $list = $list->orderBy('updated_at', 'DESC');
        }

        $list = $list->paginate();

        return view('backend.post.list', [
            'list' => $list,
            'categories' => categories_traverse($categories)
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
        $post = new Post;

        $this->processPostFromRequest($request, $post);

        return redirect()
            ->route('admin.post.edit', ['id' => $post->id])
            ->with('success', 'Thêm bài viết mới thành công.');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

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

        $this->processPostFromRequest($request, $post);

        return redirect()
            ->route('admin.post.edit', ['id' => $post->id])
            ->with('success', 'Thay đổi bài viết thành công.');
    }

    private function processPostFromRequest(Request $request, Post $post)
    {
        $data = $request->all();
        $post->fill($data);

        $post->save();
    }

    public function submitDelete($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        $post->delete();

        return redirect()
            ->route('admin.post.list')
            ->with('success', 'Xóa bài viết thành công.');
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
            Category::where('type', Category::TYPE_POST)
                ->orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function submitOrderInCategory(Request $request)
    {
        $course = Post::findOrFail($request->input('id'));
        $course->order_in_category = intval($request->input('order_in_category'));
        $course->timestamps = false;
        $course->save();
    }
}
