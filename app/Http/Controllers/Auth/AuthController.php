<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\ResetTokenRequest;
use App\Jobs\SentPasswordResetEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ValidateRegisterRequest;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $verificationCode = str_random(30);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'email'      => $request->input('email'),
            'password'   => Hash::make($request->input('password')),
            'birthday'   => $request->input('birthday'),
            'gender'     => $request->input('gender'),
            'token'      => $verificationCode
        ]);

        dispatch(new SendVerificationCode($user));

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
            $user = User::find($check->id);

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

    /**
     * Function to login user
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_verified'] = 1;

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 404);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }

        // update user status
        $user = User::find(Auth::user()->id);
        $user->is_online        = true;
        $user->is_mobile        = $request->input('is_mobile') ? true : false;
        $user->last_action_date = Carbon::now();
        $user->save();

        // all good so return the token
        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]], 200);
    }

    /**
     * Function to logout user
     *
     * @param LogoutRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(LogoutRequest $request)
    {
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true, 'message'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }

    /**
     * Function to recover user password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $errorMessage = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email'=> $errorMessage]], 401);
        }

        try {

            $email     = $request->only('email');
            dispatch(new SentPasswordResetEmail($email));

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['success' => false, 'error' => $errorMessage], 401);
        }
        return response()->json([
            'success' => true, 'data'=> ['message'=> 'A reset email has been sent! Please check your email.']
        ]);
    }

    /**
     * Function to check reser password token
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkResetToken(Request $request, $token)
    {
        $check = DB::table('password_resets')->where('token', $token)->first();

        if (is_null($check)) {
            return response()->json([ 'success' => false ]);
        }

        return response()->json([ 'success' => true ]);
    }

    /**
     * Function to reset user password
     *
     * @param PasswordResetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(PasswordResetRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->get('password'));
        $user->save();

        DB::table('password_resets')->where('email', $request->input('email'))->delete();

        return response()->json([ 'success' => true ]);
    }
}
