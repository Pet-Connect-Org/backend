<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function store(Request $request) {
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
    public function listPost(Request $request) {
        $query = $request->query();
        $limit = 40;
        $offset= 0;
        $orderBy = $request->query('order_by', 'desc');
        
        if ($query['limit']) {
            $limit = $query['limit'];
        }
        if ($query['offset']) {
            $offset = $query['offset'];
        }
      
        $postsQuery = Post::query();

        if(isset($query['user_id'])) {
            $postsQuery->where([
                'userId' => $query['user_id']
            ]);
        }

        $postList = $postsQuery->orderBy('created_at', $orderBy)
        ->with(['user', 'likes', 'comments' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])
        ->skip($offset)
        ->take($limit)
        ->get();

        return response()->json([
            'data' => $postList,
            'message' => 'Query successfully.'
        ], 200);
    }

    public function updatePost(string $id, PostRequest $request) {
        $post = Post::find($id);
        $user = User::where('account_id', auth()->user()->id)->first();

        if (!$post) {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }

        if($post['user_id'] == $user->id) {
            $updatedPost = $post->update($request->all());
            return response()->json([
                'data' => $updatedPost,
                'message' => 'Update post successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Not have permission.'
            ], 409);
        }
    }

    public function createPost(PostRequest $request) {
        $user = User::where('account_id', auth()->user()->id)->first();

        $post = Post::create([
            'content' => $request->input('content'),
            'user_id' => $user->id
        ]);

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

    public function deletePost(string $id) {
        try {
            $account = Post::find($id);
            if ($account) {

                $account->delete();
                
                return response()->json(['message' => 'Account deleted successfully'], 201);
            } else {
                return response()->json(['message' => 'Post no longer exist..'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete account'], 500);
        }
    }
    
    public function getPostById(Request $request) {
        $post = Post::find($request->id);
        
        if ($post) {
            return response()->json(['message'=>'Get post successfully.', 'data'=> $post],201);
        } else {
            return response()->json(['message' => 'Post not exist..'], 404);
        }

    }
}
