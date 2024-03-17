<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function createComment(CommentRequest $request) {
        $comment = Comment::create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
            'post_id' => $request->input("post_id")
        ]);

        if ($comment) {
            return response()->json([
                'data' => $comment,
                'message' => 'Create comment successfully.'
            ], 201);
        }
        return response()->json([
            'message' => 'Failed to create comment.'
        ], 500);
    }

    public function updateComment(string $id, Request $request) {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment['user_id'] == auth()->user()->id) {
            $updatedComment = $comment->update([
                'content' => $request->content
            ]);
            return response()->json([
                'data' => $updatedComment,
                'message' => 'Update comment successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not have permission.'
            ], 409);
        }
    }

    public function deleteComment(string $id) {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment['user_id'] == auth()->user()->id) {
            $comment->delete();

            return response()->json([
                'message' => 'Delete comment successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not have permission.'
            ], 409);
        }
    }
}
