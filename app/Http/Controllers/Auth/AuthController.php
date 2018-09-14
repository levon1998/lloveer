<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidateRegisterRequest;

class AuthController extends Controller
{
    /**
     * Function to Register new user
     *
     * @param ValidateRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(ValidateRegisterRequest $request)
    {
        $verification_code = str_random(30);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'email'      => $request->input('email'),
            'password'   => Hash::make($request->input('password')),
            'birthday'   => $request->input('birthday'),
            'gender'     => $request->input('gender'),
            'token'      => $verification_code
        ]);

        dispatch(new SendVerificationCode($user->id));

        return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.'], Response::HTTP_OK);
    }

    /**
     * Function to verify user
     *
     * @param $verificationCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verificationCode)
    {
        $check = User::where('token', $verificationCode)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);
            if($user->is_verified == 1){
                return response()->json([ 'success'=> true, 'message'=> 'Account already verified..' ], Response::HTTP_OK);
            }

            $user->is_verified = 1;
            $user->token = null;
            $user->save();

            return response()->json([ 'success'=> true, 'message'=> 'You have successfully verified your email address.' ], Response::HTTP_OK);
        }

        return response()->json(['success'=> false, 'error' => "Verification code is invalid."], Response::HTTP_NOT_FOUND);
    }
}
