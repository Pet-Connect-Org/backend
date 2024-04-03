<?php

namespace App\Http\Controllers;

use App\Models\LikePost;
use App\Models\User;

class LikePostController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/post/like/{id}",
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
     *             @OA\Property(property="message", type="string", example="Like successfully."),
     *                @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="user_id", type="integer", example="1"),
     *                  @OA\Property(property="post_id", type="integer", example="1"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
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
            ], 400);
        } else {
            $like = LikePost::create([
                'user_id' => $user->id,
                'post_id' => $id
            ]);
            return response()->json([
                'message' => 'Like successfully.',
                'data' => $like
            ], 201);
        }
    }
    /**
     * @OA\Post(
     *     path="/post/unlike/{id}",
     *     tags={"Like Post"},
     *     summary="Unlike a post",
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
     *         description="Unlike successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unlike successfully."),
     *                    @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="user_id", type="integer", example="1"),
     *                  @OA\Property(property="post_id", type="integer", example="1"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
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

        $likePost = LikePost::where([
            'user_id' => $user->id,
            'post_id' => $id
        ])->first();

        if ($likePost) {
            $likePost->delete();

            return response()->json([
                'message' => 'Unlike successfully.',
                'data' => $likePost
            ], 201);
        } else {
            return response()->json([
                'message' => 'Already unlike.'
            ], 400);
        }
    }
}
