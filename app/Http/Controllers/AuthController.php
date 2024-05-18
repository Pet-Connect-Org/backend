<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResendEmailVerificationCodeRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\Account;
use App\Models\User;
use App\Services\EmailVerificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct(private EmailVerificationService $service)
    {
    }

    /**
     * Register
     * 
     * @OA\Post(
     *      path="/auth/sign-up",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              ref="#/components/schemas/SignupRequest"
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sign up successful",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Sign up successful"),
     *                  @OA\Property(property="accessToken", type="string", example=""),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      @OA\JsonContent(
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *              example="The password field format is invalid. (and 1 more error)"
     *          ),
     *          @OA\Property(
     *              property="errors",
     *              type="object",
     *              @OA\Property(
     *                  property="password",
     *                  type="array",
     *                  @OA\Items(type="string", example="The password field format is invalid.")
     *              ),
     *              @OA\Property(
     *                  property="confirmPassword",
     *                  type="array",
     *                  @OA\Items(type="string", example="The confirm password field must match password.")
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="array",
     *                  @OA\Items(type="string", example="The email field is required.")
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="array",
     *                  @OA\Items(type="string", example="The name field is required.")
     *              ),
     *              @OA\Property(
     *                  property="sex",
     *                  type="array",
     *                  @OA\Items(type="string", example="The selected sex is invalid.")
     *              ),
     *              @OA\Property(
     *                  property="birthday",
     *                  type="array",
     *                  @OA\Items(type="string", example="The birthday field is required.")
     *              ),
     *              @OA\Property(
     *                  property="address",
     *                  type="array",
     *                  @OA\Items(type="string", example="The address field is required.")
     *              )
     *          )
     *      )
     *      ),
     * )
     * @param SignupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function signUp(SignupRequest $request)
    {

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
        ], 201);
    }

    public function resendEmailVerificationCode(ResendEmailVerificationCodeRequest $request)
    {
        return $this->service->resendLink($request->email);
    }

    /**
     * Verify with Otp
     * 
     * @OA\Post(
     *      path="/auth/verify_user_email",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              ref="#/components/schemas/VerifyEmailRequest"
     *         )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Account not found",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Account not found"),
     *          )
     *      ),
     *  @OA\Response(
     *          response=201,
     *          description="Verify successfully",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Verify successfully"),
     *          )
     *      ),
     * )
     * @param VerifyEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyUserEmail(VerifyEmailRequest $request)
    {
        return $this->service->verifyEmail($request->email, $request->token);
    }
    /**
     * Login
     * 
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              ref="#/components/schemas/LoginRequest"
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *          @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Success"),
     *                  @OA\Property(property="token", type="string", example=""),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorize",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorize")
     *          )
     *      ),
     *      @OA\Response(
     *          response=423,
     *          description="Account have not active yet.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Account have not active yet.")
     *          )
     *      )
     * )
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(LoginRequest $request)
    {
        $account = Account::where([
            'email' => $request->input('email'),
            'provider' => 'credential'
        ])->first();


        if (!$account) {
            return response()->json([
                'message' => 'This email not register with any account.'
            ], 404);
        }

        if (!Hash::check($request->input('password'), $account->password)) {
            return response()->json([
                'message' => 'Please check your email or password.'
            ], 401);
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
            ], 401);
        }
    }


    public function loginWithGoogle(Request $request) {
        $account = Account::where([
            'email' => $request->input('email'),
            'provider' => 'google',
            'providerAccountId' => $request->input('providerAccountId')
        ])->first();

        if ($account ) {
            $account->update([
                'access_token' => $request->input('access_token'),
                'expires_at' => $request->input('expires_at')
            ]);

        
            $user = User::where(['account_id'=>$account->id])->first();
            $user->update([
                'name' => $request->input('name'),
                'image' => $request->input('image')
            ]);

            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('providerAccountId'),
                'provider' => 'google'
            ];
            
            $token = auth()->attempt($credentials);
            $mergedData = array_merge($user->refresh()->toArray(), $account->refresh()->toArray());
            
            return $this->responseWithToken($token, $mergedData); 

        } else {
            $account = Account::create([
                'email' => $request->input('email'),
                'provider' => 'google',
                'providerAccountId' => $request->input('providerAccountId'),
                'password' => Hash::make($request->input('providerAccountId')),
                'role' => 1,
                'isActived' => 1,
                'access_token' => $request->input('access_token'),
                'type' => 'oath',
                'token_type' => 'bearer',
                'expires_at' => $request->input('expires_at'),
                'remember_token' => Str::random(10)
            ]);

        

            $user = User::create([
                'account_id' => $account->id,
                'name' => $request->input('name'),
                'image' => $request->input('image')
            ]);

            unset($account['id']);
            
            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('providerAccountId'),
                'provider' => 'google'

            ];

            $token = auth()->attempt($credentials);

            $mergedData = array_merge($user->toArray(), $account->toArray());
            return $this->responseWithToken($token, $mergedData); 
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 201);
    }

    public function responseWithToken($token, $user)
    {
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => "Success"
        ], 201);
    }
}
