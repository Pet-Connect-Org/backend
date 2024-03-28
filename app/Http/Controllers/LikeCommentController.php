<?php

namespace App\Http\Controllers;

use App\Models\LikeComment;
use App\Models\User;
use Illuminate\Http\Request;

class LikeCommentController extends Controller
{
    //


    /**
     * @OA\Post(
     *     path="/comment/like/{id}",
     *     tags={"Like Comment"},
     *     summary="Like a comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to like",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Like successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Like successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Already like",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Already like.")
     *         )
     *     ),
     * )
     */
    public function like(string $id)
    {
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


    /**
     * @OA\Post(
     *     path="/comment/unlike/{id}",
     *     tags={"Like Comment"},
     *     summary="Unlike a comment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to like",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Unlike successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unlike successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Already Unlike",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Already Unlike.")
     *         )
     *     ),
     * )
     */
    public function unlike(string $id)
    {
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
