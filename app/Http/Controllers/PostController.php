<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function store(Request $request)
    {
        $post = Post::create($request->all());

        return $post;
    }

    /**
     * {
     *  user_id
     *  limit
     *  offset
     *  order_by
     * }
     */
    /**
     * Retrieve a list of posts based on specified criteria.
     *
     * This endpoint allows you to retrieve a list of posts based on optional query parameters such as user ID, limit, offset, and order by.
     *
     * @OA\Get(
     *     path="/posts",
     *     tags={"Post"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID of the user whose posts to retrieve (optional)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of posts to retrieve (optional)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Number of posts to skip (optional)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Ordering of posts (optional, default: desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of posts retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="user_id", type="integer", example="1"),
     *                      @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
     *                  )
     *             ),
     *             @OA\Property(property="message", type="string", example="Query successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listPost(Request $request)
    {
        $query = $request->query();
        $limit = 40;
        $offset = 0;
        $orderBy = $request->query('order_by', 'desc');

        if (isset($query['limit'])) {
            $limit = $query['limit'];
        }
        if (isset($query['offset'])) {
            $offset = $query['offset'];
        }

        $postsQuery = Post::query();

        if (isset($query['user_id'])) {
            $postsQuery->where([
                'user_id' => $query['user_id']
            ]);
        }

        $postList = $postsQuery->orderBy('created_at', $orderBy)
            ->with(['images', 'user', 'likes', 'comments' => function ($query) {
                $query->orderBy('created_at', 'asc')->with('likes');
            }])
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json([
            'data' => $postList,
            'message' => 'Query successfully.'
        ], 200);
    }

    /**
     * Update a post.
     *
     * @OA\Put(
     *     path="/post/{id}",
     *     tags={"Post"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to be updated",
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
     *                 ref="#/components/schemas/PostRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post updated successfully."),
     *             @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example="1"),
     *                  @OA\Property(property="user_id", type="integer", example="1"),
     *                  @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
     *                  @OA\Property(property="latitude", type="number", format="float", example="20.0"),
     *                  @OA\Property(property="longitude", type="number", format="float", example="106.0"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                  @OA\Property(property="images", type="array", @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="link", type="string", example="http://example.com/image.png")
     *                  ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to update this post.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized to perform this action.")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function updatePost(string $id, PostRequest $request)
    {
        $post = Post::find($id);
        $user = User::where('account_id', auth()->user()->id)->first();

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        if ($post['user_id'] == $user->id) {
            $post->update([
                'content' => $request->input('content'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            Image::where('post_id', $id)->delete();

            $newImages = $request->input('images', []);
            foreach ($newImages as $link) {
                Image::create([
                    'post_id' => $post->id,
                    'link' => $link
                ]);
            }
            $post->load('images');

            return response()->json([
                'data' => $post,
                'message' => 'Update post successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not have permission.'
            ], 409);
        }
    }

    /**
     * Create post
     *
     * @OA\Post(
     *     path="/post",
     *     tags={"Post"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/PostRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Create new post successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post created successfully."),
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
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Create new post failed.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Create new post failed.")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function createPost(PostRequest $request)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        $post = Post::create([
            'content' => $request->input('content'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'user_id' => $user->id
        ]);

        $images = $request->input('images', []);

        foreach ($images as $link) {
            Image::create([
                'post_id' => $post->id,
                'link' => $link
            ]);
        }

        $post->load('images');

        if ($post) {
            return response()->json([
                'data' => $post,
                'message' => 'Create new post successfully.'
            ], 201);
        }

        return response()->json([
            'message' => 'Create new post failed.'
        ], 500);
    }

    /**
     * Delete post
     *
     * @OA\Delete(
     *     path="/post/{id}",
     *     tags={"Post"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Delete post successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to delete this post.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not authorized to delete this post.")
     *         )
     *     )
     * )
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function deletePost(string $id)
    {
        try {
            $account = Post::find($id);
            if ($account) {

                $account->delete();

                return response()->json(['message' => 'Post deleted successfully'], 201);
            } else {
                return response()->json(['message' => 'Post no longer exist..'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to post account'], 500);
        }
    }


    public function getPostById(Request $request)
    {
        $post = Post::find($request->id);
        $post->load(['images', 'user', 'likes', 'comments' => function ($query) {
            $query->orderBy('created_at', 'asc')->with('likes');
        }]);
        if ($post) {
            return response()->json(['message' => 'Get post successfully.', 'data' => $post], 201);
        } else {
            return response()->json(['message' => 'Post not exist..'], 404);
        }
    }
}
