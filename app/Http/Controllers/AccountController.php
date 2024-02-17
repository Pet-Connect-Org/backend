<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Active account
     */
    public function active($email) {
        Account::where('email', $email)->update(['isActive' => true]);

        return true;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:accounts,email',
            'password' => [
                'required',
                'min:6',
                'max:30',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
            'confirmPassword' => ['required', 'same:password']
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $hashedPassword = Hash::make($request->input('password'));
        $remember_token = Str::random(10);
        
        $account = Account::create([
            'email' => $request->input('email'),
            'password' => $hashedPassword,
            'remember_token' => $remember_token
        ]);

        // $token = $account->createToken('Laravel Password Grant Client')->accessToken;
        return $account;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            // Define validation rules for your account update
        ]);

        $account = Account::findOrFail($id);
        $account->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    try {
        $account = Account::findOrFail($id);

        $account->delete();

        return response()->json(['message' => 'Account deleted successfully'], Response::HTTP_OK);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete account'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}
