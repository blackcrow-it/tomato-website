<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CkEditorImageUploadRequest;
use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $post = new Post;
        $post->save();
        $post->delete();

        return redirect()->route('admin.post.edit', ['id' => $post->id]);
    }

    public function edit($id)
    {
        $post = Post::withTrashed()->find($id);
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
        $post = Post::withTrashed()->find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        // Fill dữ liệu cơ bản
        $data = $request->input();
        $post->fill($data);

        // Upload ảnh và fill vào trường ảnh
        $uploadResult = $this->uploadImages($request, $post->id);
        $post->fill($uploadResult);

        // Lưu post object
        $post->save();
        $post->restore();

        return redirect()
            ->route('admin.post.edit', ['id' => $post->id])
            ->with('success', 'Thay đổi bài viết thành công.');
    }

    public function submitDelete($id)
    {
        $post = Post::find($id);
        if ($post == null) {
            return redirect()->route('admin.post.list')->withErrors('Bài viết không tồn tại hoặc đã bị xóa.');
        }

        $post->forceDelete();

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

    public function uploadImages(Request $request, $id)
    {
        $result = [];

        if ($request->file('thumbnail')) {
            $result['thumbnail'] = Storage::cloud()->putFile('posts/' . $id, $request->file('thumbnail'), 'public');
        }

        if ($request->file('cover')) {
            $result['cover'] = Storage::cloud()->putFile('posts/' . $id, $request->file('cover'), 'public');
        }

        if ($request->file('og_image')) {
            $result['og_image'] = Storage::cloud()->putFile('posts/' . $id, $request->file('og_image'), 'public');
        }

        return $result;
    }

    public function getCategoriesTraverse()
    {
        return categories_traverse(
            Category::orderBy('title', 'ASC')
                ->get()
                ->toTree()
        );
    }

    public function submitImage(CkEditorImageUploadRequest $request, $id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        $file = $request->file('upload');
        $path = Storage::cloud()->putFile('posts/' . $post->id, $file, 'public');

        return [
            'uploaded' => 1,
            'fileName' => $file->getClientOriginalName(),
            'url' => Storage::cloud()->url($path)
        ];
    }
}
