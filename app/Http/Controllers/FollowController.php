<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * @OA\Post(
     *     path="/follow/user/{id}",
     *     tags={"Follow"},
     *     summary="Follow user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to follow",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64" 
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Follow successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User followed successfully.")
     *         )
     *     ),
     * *     @OA\Response(
     *         response=400,
     *         description="You cannot follow yourself.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User followed successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Already followed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User already being followed.")
     *         )
     *     ),
     * )
     */
    public function follow(string $id)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        if ($id == $user->id) {
            return response()->json([
                'message' => 'You cannot follow yourself.'
            ], 400);
        }

        $isFollowing = Follow::where([
            'user_id' =>$user->id,
            'following_user_id' => $id
        ])->first();


        if ($isFollowing) {
            return response()->json([
                'message' => 'Already followed.'
            ], 409);
        } else {
            Follow::create([
                'user_id' =>$user->id,
                'following_user_id' => $id
            ]);
            return response()->json([
                'message' => 'Follow successfully.'
            ], 201);
        }
    }
    /**
     * @OA\Post(
     *     path="/unfollow/user/{id}",
     *     tags={"Follow"},
     *     summary="Unfollow user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to unfollow",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64" 
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Follow successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unfollow successfully.")
     *         )
     *     ),
     *   @OA\Response(
     *         response=400,
     *         description="Cannot unfollow yourself",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cannot unfollow yourself.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not found.")
     *         )
     *     ),
     * )
     */
    public function unfollow(string $id)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        if ($id == $user->id) {
            return response()->json([
                'message' => 'Cannot unfollow yourself.'
            ], 400);
        }

        $follow = Follow::where([
            'user_id' =>$user->id,
            'following_user_id' => $id
        ])->first();

        if ($follow) {
            $follow->delete();

            return response()->json([
                'message' => 'Unfollow successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not found.'
            ], 409);
        }
    }
}
