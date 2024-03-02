<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostRequest;
use App\Models\Post;
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
     * 
     */
    public function listPost(Request $request) {
        $query = $request->query;
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

        if($query['user_id']) {
            $postsQuery->where([
                'userId' => $query['user_id']
            ]);
        }

        $postList = $postsQuery->orderBy('createdAt',$orderBy)->skip($offset)->take($limit)->with('user')->get();

        return response()->json([
            'data' => $postList,
            'message' => 'Query successfully.'
        ], 200);

    }

    public function updatePost(string $id, PostRequest $request) {
        $post = Post::find($id);
        if($post) {
            $updatedPost = $post->update($request->all());
            return response()->json([
                'data' => $updatedPost,
                'message' => 'Update post successfully.'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Post not found.'
            ], 404);
        }
    }

    public function createPost(PostRequest $request) {
        $post = $this->store($request);

        if ($post) {
            return response()->json([
                'data' => $post,
                'message' => 'Create new post successfully.'
            ], 201);
        }
    }

    public function deletePost(Request $request) {
        try {
            $account = Post::find($request->id);
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
