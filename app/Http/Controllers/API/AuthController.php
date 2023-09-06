<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register','logout','refresh','forget_password',
        'forget_password_verify','resend_otp','verify_otp']]);
    }

    // public function login(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'email' => 'required|email',
    //         'password' => 'required|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return errorMsg($validator->errors()->first());
    //     }

    //     $user = User::where('email', $request->email)->first();
    //     if (Hash::check($request->password, $user->password)) {
    //         // $user = User::where('id',$user->id)->first();
    //         // $user->fcm_token = $request->fcm_token;
    //         // $user->save();

    //         $token = $user->createToken('apiToken')->plainTextToken;

    //         return response()->json([
    //             'status' => true,
    //             'user' => $user,
    //             'authorization' => [
    //                 'token' => $token,
    //                 'type' => 'bearer',
    //             ]
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Invalid credentials',
    //     ], 401);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                
                'user' => $user,
                'status' => true,
                'authorization' => [
                    'token' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
                ]
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    // public function register(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'name' => 'required|string|max:255',
    //         'phone' => 'required',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return errorMsg($validator->errors()->first());
    //     }

    //     $user = User::create([
    //         'first_name' => $request->name,
    //         'phone' => $request->phone,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     if($user){
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'User created successfully',
    //             'user' => $user
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong',
    //             'user' => ''
    //         ]);
    //     }

        
    // }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User;
        $user->first_name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'user' => $user
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'user' => ''
            ]);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function forget_password_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => 'required',
            "password" => "required",
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        }
        $user = User::where('email', $request->email)->where('status', 1)->orderBy('id','DESC')->first();

        if (isset($user->id)) {
            $user_login_source = User::where('id', $user->id)->where('verification_code', $request->otp)->orderBy('id','DESC')->first();
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'New password cannot same as old password.',
                ], 200);
            } else {
                $user->password = bcrypt($request->password);
                if ($user->save()) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Password reset successfully.'
                    ]);
                }
            }
        } else return response()->json([
            'status' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        }

        $user = User::where('email', $request->email)->where('status', 1)->orderBy('id','DESC')->first();
        $msg = "Email address";

        if (isset($user->id)) {
            $code = rand(1000, 9999);
            $user->Verification_code = $code;
            if ($user->save()) {
                return response()->json(['status' => true, 'message' => "OTP send to your " . $msg . ". OTP is expire in 5 minutes...", 'otp' => $code]);
            }

        } else return errorMsg($msg . " not found.");
    }

    public function resend_otp(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->where('status', 1)->orderBy('id','DESC')->first();
            $code = rand(1000, 9999);
            $user->verification_code = $code;
            $user->save();
            return response()->json(['status' => true, 'message' => 'OTP resend successfully.', 'code' => $code]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function verify_otp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|numeric|digits:4'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            }
            $user = User::where('email', $request->email)->orderBy('id','DESC')->first();
            if (isset($user->id)) {
                if(!empty($user->verification_code))
                {
                    if($user->verification_code == $request->otp)
                    {
                        return response()->json(['status' => true, 'message' => 'Verification successfully.']);
                    }else{
                        return response()->json(['status' => false, 'message' => 'OTP is incorrect.']);
                    }
                }else return response()->json(['status' => false, 'message' => 'OTP is incorrect.']);
            } else return response()->json(['status' => false, 'message' => 'OTP is incorrect.']);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
}
