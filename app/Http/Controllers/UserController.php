<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sex' => ['required', Rule::in(['male', 'female'])],
            'birthday' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'sex' => $request->input('sex'),
            'birthday' => $request->input('birthday'),
            'address' => $request->input('address'),
            'account_id' => $request->input('account_id')
        ]);
        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find("account_id", $id)->first();

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(UpdateUserRequest $request)
    {
      
        $birthday = Carbon::parse($request->birthday)->format('Y-m-d');
        
        $data = $request->merge(['birthday' => $birthday])->all();
      
        $user = User::where('account_id', auth()->user()->id)->first();
    
         $user->update($data);
    
        if (!$user) {
            return response()->json([
                'message' => 'Failed to update user information.'
            ], 500);
        }
    
        $token = auth()->login(auth()->user());
    
        return response()->json([
            'data' => $user->refresh(),
            'token' => $token,
            'message' => 'Update user information successfully.'
        ], 201);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getUserByAccessToken(Request $request)
    {
        $account = $request->user();

        if (!$account) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user = User::where('account_id', $account->id)->first();

        $userData = $user->toArray();
        $accountData = $account->toArray();

        unset($accountData['id']);

        $mergedData = array_merge($userData, $accountData);

        return response()->json([
            'user' => $mergedData,
            'message' => 'Get user successful',
        ], 200);
    }
    /**
     * Get the user by the provided ID with associated posts.
     *
     * @OA\Get(
     *     path="/user/{id}",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of posts retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example="2"),
     *                 @OA\Property(property="account_id", type="integer", example="4"),
     *                 @OA\Property(property="name", type="string", example="Bùi Thúy Ngọc"),
     *                 @OA\Property(property="sex", type="string", example="female"),
     *                 @OA\Property(property="address", type="string", example="Hà Đông, Hà Nội, Việt Nam"),
     *                 @OA\Property(property="birthday", type="string", example="2005-12-03"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-20T09:43:36.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-20T09:43:36.000000Z"),
     *                 @OA\Property(
     *                     property="posts",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example="7"),
     *                         @OA\Property(property="content", type="string", example="post mới nè"),
     *                         @OA\Property(property="user_id", type="integer", example="2"),
     *                         @OA\Property(property="latitude", type="number", example=null),
     *                         @OA\Property(property="longitude", type="number", example=null),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-30T17:07:19.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-30T17:07:19.000000Z")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Get user successful")
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

    public function getUserById(string $id, Request $request)
    {
        $user = User::with(['followers', 'following', "posts" => function ($d) {
            $d->with(["user", 'likes',  'comments' => function ($query) {
                $query->orderBy('created_at', 'asc')->with('likes');
            }]);
        }])->find($id);

        return response()->json([
            'data' => $user,
            'message' => 'Get user successful',
        ], 200);
    }
}
