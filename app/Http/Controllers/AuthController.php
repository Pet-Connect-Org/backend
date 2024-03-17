<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResendEmailVerificationCodeRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\Account;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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

        $birthday = Carbon::parse($request->birthday)->format('Y-m-d');

        $userController->store($request->merge(['account_id' => $account->id, 'birthday' => $birthday]));
        
        $this->service->sendVerificationLink($account);
        
        $token = auth()->login($account);

        return response()->json([
            'message' => 'Sign up successful',
            'access_token' => $token
        ],201);
    }

    public function resendEmailVerificationCode(ResendEmailVerificationCodeRequest $request) {
        return $this->service->resendLink($request->email);
    }

    public function verifyUserEmail(VerifyEmailRequest $request) {
        return $this->service->verifyEmail($request->email, $request->token);

    }

    public function login(LoginRequest $request)
    {
        $account = Account::where('email', $request->input('email'))->first();

        if (!$account) {
            return response()->json([
                'message' => 'This email not register with any account.'
            ], 404);
        }

        if (!Hash::check($request->input('password'), $account->password)) {
            return response()->json([
                'message' => 'Please check your email or password.'
            ], 404);
        }

        if ($account->isActived == 0) {
            return response()->json([
                'message' => 'Account have not active yet.'
            ], 423);
        }

        $token = auth()->attempt($request->validated());

        if ($token) {
            $user = User::where('account_id', auth()->user()->id)->first();

            $accountData = auth()->user();
        
            unset($accountData['id']);
    
            $mergedData = array_merge($user->toArray(), $accountData->toArray());
            return $this->responseWithToken($token, $mergedData);
        } else {
            return response()->json([
                'message' => 'Invalid credentials.'
            ],401);
        }
    }
    

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 201);
    }

    public function responseWithToken($token, $user) {
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => "Success"
        ], 201);

    }
}
