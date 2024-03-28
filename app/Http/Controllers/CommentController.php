<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
 * List comments for a post
 *
 * @OA\Get(
 *     path="/comments",
 *     tags={"Comment"},
 *     summary="Lists all comments for a specific post",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="post_id",
 *         in="query",
 *         required=true,
 *         description="The ID of the post to retrieve comments for",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array",
 *                  @OA\Items(@OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="user_id", type="integer", example="1"),
 *                  @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
 *             ))
 *             )
 *            
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Post not found"
 *     )
 * )
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function listComments(Request $request) {
        $user = User::where('account_id', auth()->user()->id)->first();
        $comments = Post::where(['id' => $request->input("post_id"), "user_id"=> $user->id])->with('comments')->get();
    
        return response()->json([
            "data" => $comments
        ], 200);
    }
   /**
 * Create comment
 *
 * @OA\Post(
 *     path="/comment",
 *     tags={"Comment"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/CommentRequest"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Create new comment successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Create comment successfully."),
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="user_id", type="integer", example="1"),
 *                  @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Create new comment failed.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Create new comment failed.")
 *         )
 *     )
 * )
 *
 * @param \Illuminate\Http\CommentRequest $request
 * @return \Illuminate\Http\JsonResponse
 */

    public function createComment(CommentRequest $request) {
        $user = User::where('account_id', auth()->user()->id)->first();
  
        $comment = Comment::create([
            'content' => $request->input('content'),
            'user_id' => $user->id,
            'post_id' => $request->input("post_id")
        ]);

        if ($comment) {
            $comment->load('user', 'likes');
            return response()->json([
                'data' => $comment,
                'message' => 'Create comment successfully.'
            ], 201);
        }
        return response()->json([
            'message' => 'Failed to create comment.'
        ], 500);
    }

 /**
 * Update comment
 *
 * @OA\Put(
 *     path="/comment/{id}",
 *     tags={"Comment"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the comment to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 ref="#/components/schemas/CommentRequest"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment updated successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Update comment successfully."),
 *             @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="user_id", type="integer", example="1"),
 *                  @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment not found.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to update this comment.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to update this comment.")
 *         )
 *     )
 * )
 *
 * @param \Illuminate\Http\CommentRequest $request
 * @return \Illuminate\Http\JsonResponse
 */

    public function updateComment(string $id, CommentRequest $request) {
        $comment = Comment::find($id);
        $user = User::where('account_id', auth()->user()->id)->first();


        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment['user_id'] == $user->id) {
            $comment->update([
                'content' => $request->content
            ]);
            return response()->json([
                'data' => $comment->refresh(),
                'message' => 'Update comment successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not have permission.'
            ], 409);
        }
    }
/**
 * Delete comment
 *
 * @OA\Delete(
 *     path="/comment/{id}",
 *     tags={"Comment"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the comment to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment deleted successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Delete comment successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Comment not found.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Not authorized to delete this comment.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not authorized to delete this comment.")
 *         )
 *     )
 * )
 *
 * @param string $id
 * @return \Illuminate\Http\JsonResponse
 */
    public function deleteComment(string $id) {
        $comment = Comment::find($id);
        $user = User::where('account_id', auth()->user()->id)->first();

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found.'
            ], 404);
        }

        if($comment['user_id'] == $user->id) {
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
