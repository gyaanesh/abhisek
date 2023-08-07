<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class authController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otpCreatedAt = $user->otp_created_at;
        // if (!$otpCreatedAt || Carbon::parse($otpCreatedAt)->addMinutes(5)->isPast()) {
        //     return response()->json(['error' => 'OTP has expired'], 401);
        // }

        if ($request->otp != $user->otp) {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }

        $user->otp = null;
        $user->otp_created_at = null;
        // $user->save();
        Auth::guard('api')->login($user);
        $token = $user->createToken($user->id . 'APP_API_Token');
 
 
        return response()->json(['message'=> 'logged in'], 201,['token' => $token->plainTextToken]);
    }

    
    public function updateProfile(Request $request)
    {
        // return response()->json(['message' => "Thanks"]);
        return auth()->user();
        
    }
}
