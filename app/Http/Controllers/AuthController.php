<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResendEmailVerificationLinkRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function __construct(private EmailVerificationService $service)
    {
    }

    

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
            'sex' => ['required', Rule::in(['male', 'female'])],
            'birthday' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $accountController = new AccountController();

        $account = $accountController->store($request);

        $userController = new UserController();

        $userController->store($request->merge(['account_id' => $account->id]));
        
        $this->service->sendVerificationLink($account);
        
        $token = auth()->login($account);

        return response()->json([
            'status' => 201,
            'message' => 'Sign up successful',
            'access_token' => $token
        ]);
    }

    public function resendEmailVerificationLink(ResendEmailVerificationLinkRequest $request) {
        return $this->service->resendLink($request->email);
    }

    public function verifyUserEmail(VerifyEmailRequest $request) {
        return $this->service->verifyEmail($request->email, $request->token);

    }

    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->responseWithToken($token, auth()->user());
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'invalid credentials'
            ],401);
        }
    }
    

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function responseWithToken($token, $user) {
        return response()->json([
            'status' => 201,
            'user' => $user,
            'token' => $token
        ]);

    }
}
