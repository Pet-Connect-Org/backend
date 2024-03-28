<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    public function update(Request $request, string $id)
    {
        //
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

    public function getUserById(string $id)
    {
        $user = User::with('posts')->find($id);

        return response()->json([
            'data' => $user,
            'message' => 'Get user successful',
        ], 200);
    }
}
