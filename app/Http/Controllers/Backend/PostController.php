<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function list()
    {
        $list = Post::with('owner')
            ->with('last_editor')
            ->orderBy('updated_at', 'DESC')
            ->paginate();

        return view('backend.post.list', [
            'list' => $list
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

    public function submitEnabled(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->enabled = $request->input('enabled');
        $post->save();
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }
}
