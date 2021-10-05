<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Book;
use App\ComboCourses;
use App\Comment;
use App\Constants\ObjectType;
use App\Course;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $object_id = $request->input('object_id');
        $type = $request->input('type');
        $comments = Comment::query()->with(array('user' => function($query) {
            $query->select('id', 'name', 'avatar');
        }))
        ->where('type', $type)
        ->where('object_id', $object_id)
        ->where('parent_id', null)
        ->orderBy('created_at', 'DESC')
        ->get();
        foreach ($comments as $comment) {
            $comment->child = $comment->comments_child();
        }
        return response([
            'data' => $comments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $object_id = $request->input('object_id');
        $type = $request->input('type');
        switch ($type) {
            case ObjectType::COURSE:
                $item = Course::find($object_id);
                break;
            case ObjectType::COMBO_COURSE:
                $item = ComboCourses::find($object_id);
                break;
            case ObjectType::BOOK:
                $item = Book::find($object_id);
                break;
            case ObjectType::POST:
                $item = Post::find($object_id);
                break;
            default:
                return response(['msg' => 'Missing data', 'status' => 'error'], 401);
        }
        if ($item) {
            $comment = new Comment();
            $comment->user_id = auth()->id();
            $comment->type = $type;
            $comment->object_id = $object_id;
            $comment->parent_id = $request->input('parent_id');
            $comment->content = $request->input('content');
            $comment->save();
            $comment = Comment::query()->with(array('user' => function($query) {
                $query->select('id', 'name', 'avatar');
            }))->find($comment->id);
            if ($comment->parent_id == null) {
                $comment->child = [];
            }
            return response([
                'msg' => 'Create or update rating complete',
                'status' => 'success',
                'data' => $comment
            ], 200);
        } else {
            return response(['msg' => 'Not found item', 'status' => 'error'], 404);
        }
    }

    /**
     * Phê duyệt bình luận.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->approved = true;
            $comment->save();
            return response(['msg' => 'Approve comment', 'data' => $comment, 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Not found comment', 'status' => 'error'], 404);
        }
    }

    /**
     * Phê duyệt bình luận.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            $comments = Comment::where('parent_id', $id)->get();
            foreach ($comments as $c) {
                $c->delete();
            }
            return response(['msg' => 'Delete comment', 'status' => 'success'], 200);
        } else {
            return response(['msg' => 'Not found comment', 'status' => 'error'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
