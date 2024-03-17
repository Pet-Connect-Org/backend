<?php

namespace App\Http\Controllers;

use App\Models\LikeComment;
use App\Models\User;
use Illuminate\Http\Request;

class LikeCommentController extends Controller
{
    //
    public function like(string $id) {
        $user = User::where('account_id', auth()->user()->id)->first();

        
        $isLiked = LikeComment::where([
            'user_id' => $user->id,
            'comment_id' => $id
            ])->first();
     
        if ($isLiked) {
            return response()->json([
                'message' => 'Already like.'
            ], 404);
        } else {
            LikeComment::create([
                'user_id' => $user->id,
                'comment_id' => $id
            ]);
            return response()->json([
                'message' => 'Like successfully.'
            ], 201);
        }
    }

    public function unlike(string $id) {
        $user = User::where('account_id', auth()->user()->id)->first();

        $likeComment = LikeComment::where([
            'user_id' => $user->id,
            'comment_id' => $id
        ])->first();

        if ($likeComment) {
            $likeComment->delete();

            return response()->json([
                'message' => 'Unlike successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Already unlike.'
            ], 404);
        }
    }
}
