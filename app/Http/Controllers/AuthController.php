<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthController extends Authenticatable
{
    // [POST]
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:accounts,email',
            'password' => [
                'required',
                'min:6',
                'max:30',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
            'confirmPassword' => ['required', 'same:password'],
            'name' => 'required',
            'sex'=> ['required', Rule::in(['male', 'female'])],
            'birthday'=> 'required',
            'address'=> 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $accountController = new AccountController();

        $response = $accountController->store($request);

        if ($response->getStatusCode() === 200) {
            // User creation successful
            return response()->json(['message' => 'Sign up successfully'], 200);
        } else {
            // User creation failed, return error response
            return response(['errors'=>$validator->errors()->all()], 422);
        }        
    }

    // [POST]
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Auth Facade
        if(Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])){

            $user = Auth::user();

            $token = $user->createToken("myToken")->accessToken;

            return response()->json([
                "status" => true,
                "message" => "Login successful",
                "access_token" => $token
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid credentials"
        ]);
    }

    // [GET]
    public function logout(){

        auth()->user()->token()->revoke();

        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }
}
