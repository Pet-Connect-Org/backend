<?php

namespace App\Http\Controllers;

use App\Models\LikePost;
use App\Models\User;

class LikePostController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/post/toggleLike/{id}",
     *     tags={"Like Post"},
     *     summary="Like a post",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to like",
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


        $isLiked = LikePost::where([
            'user_id' => $user->id,
            'post_id' => $id
        ])->first();

        if ($isLiked) {
            return response()->json([
                'message' => 'Already like.'
            ], 404);
        } else {
            LikePost::create([
                'user_id' => $user->id,
                'post_id' => $id
            ]);
            return response()->json([
                'message' => 'Like successfully.'
            ], 201);
        }
    }

    public function unlike(string $id)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        $likePost = LikePost::where([
            'user_id' => $user->id,
            'post_id' => $id
        ])->first();

        if ($likePost) {
            $likePost->delete();

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
