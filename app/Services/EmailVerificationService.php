<?php

namespace App\Services;

use App\Models\Account;
use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;

class EmailVerificationService {
    public function sendVerificationLink($account) {
        Notification::send($account, new EmailVerificationNotification($this->generateVerificationToken($account->email)));
    }

    public function resendLink($email) {
        $account = Account::where('email',$email)->first();
        if (!$account) {
            response()->json([
                'status'=> "failed",
                "message" => 'Account not found'
            ])->send();
            exit();
        } else {
            $this->sendVerificationLink($account);

            return response()->json([
                'status'=> "success",
                "message" => 'Verification link resend successfully'
            ]);
        }
    }

    public function checkEmailIsVerified($account) {
        if ($account->isActived) {
            response()->json([
                'status'=> "failed",
                "message" => 'email has already been verified'
            ])->send();
            exit();
        }
    }

    public function verifyEmail($email, $token) {
        $account = Account::where('email',$email)->first();
        if (!$account) {
            response()->json([
                'status'=> "failed",
                "message" => 'Account not found'
            ])->send();
            exit();
        }

        $this->checkEmailIsVerified($account);

        $verifiedToken = $this->verifyToken($email,$token);
        if (!$account->isActived) {
            $verifiedToken->delete();
            $account->isActived = true;
            $account->save();
            return response()->json([
                'status'=> "success",
                "message" => 'Verify successfully'
            ]);
        } else {
            return response()->json([
                'status'=> "failed",
                "message" => 'Cannot verify'
            ]);
        }
    }



    public function verifyToken($email, $token){
        $tokenDTB = EmailVerificationToken::where('token', $token)->where('email',$email)->first();

        if($tokenDTB) {
            if ($tokenDTB->expired_at > now()) {
                return $tokenDTB;
            } else {
                response()->json([
                    'status'=> "failed",
                    "message" => 'token expired'
                ])->send();
                exit();
            }


        } else {
            response()->json([
                'status'=> "failed",
                "message" => 'invalid token'
            ])->send();
            exit();
        }
    }

    public function generateVerificationToken($email) {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first();
        if ($checkIfTokenExists) {
            $checkIfTokenExists->delete();
        }
        $token = rand(100000,999999);
        $saveToken = EmailVerificationToken::create([
            "email" => $email,
            "token" => $token,
            "expired_at" => now()->addMinutes(60),
        ]);

        if ($saveToken) {
            return $token;
        }

    }
}