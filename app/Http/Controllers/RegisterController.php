<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display register page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request) 
    {
        $user = User::create($request->validated());

        //auth()->login($user);

        return redirect('/login')
        ->with('success', "Account approval request has been sent to admin we will notify you once it is approved via email.");
    }

    public function forgot_password() 
    {
        return view('auth.forgot_password');
    }

    public function forgot_password_email(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {
            $user = User::where('email', $request->email)->where('role', 2)->where('status', 1)->first();
            if (isset($user->id)) {
                $code = rand(1000, 9999);
                $data['subject']    = 'Forgot Password OTP';
                $data['from_email'] = env('MAIL_FROM_ADDRESS');
                $data['site_title'] = 'Forgot Password OTP';
                $data['view'] = 'home.otp';
                $data['otp'] = $code;
                $data['customer_name'] = $user->first_name ?? 'NA' + ' ' + $user->last_name ?? '';
                $data['to_email'] = $user->email ?? 'NA';
                sendEmail($data);
                User::where('email', $request->email)->where('role', 2)->where('status', 1)->update([
                    'verification_code' => $code
                ]);
                $user_email = encrypt_decrypt('encrypt',$request->email);
                return redirect()->route('admin.reset_password', $user_email)->with('message', 'Your OTP was '.$code);
            } else {
                return redirect()->back()->with('success', 'The email is not registered with Arkansas');
            }
        }
    }

    public function reset_password($email) 
    {   
        $decrypt_email = encrypt_decrypt('decrypt', $email);
        $user = User::where('email', $decrypt_email)->where('role', 2)->where('status', 1)->first();
        $otp = $user->verification_code ?? null;
        return view('auth.reset_password')->with(compact('email', 'otp'));
    }

    public function reset_password_otp(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp1' => 'required',
            'otp2' => 'required',
            'otp3' => 'required',
            'otp4' => 'required',
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {
            $val = $request->email;
            $val = encrypt_decrypt('decrypt',$val);
            $user = User::where('email', $val)->where('status', 1)->where('role', 2)->orderBy('id','DESC')->first();
            
            $otp = $request['otp1'].''.$request['otp2'].$request['otp3'].$request['otp4'];
            if (isset($user->id)) {
                $user_login_source = User::where('id', $user->id)->where('role', 2)->where('verification_code', $otp)->orderBy('id','DESC')->first();
                if (isset($user_login_source)) {
                    User::where('id', $user_login_source->id)->update([
                        'verification_code' => null
                    ]);
                    return redirect()->route('admin.change_password', $request->email);
                } else {
                    return redirect()->route('admin.reset_password', $request->email)->with('success','OTP is incorrect.');
                }
            } else {
                return redirect('/forgot_password');
            }
        }
    }

    public function change_password($email) 
    {
        return view('auth.change_password')->with(compact('email'));
    }

    public function change_password_update(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'cnf_password' => 'required',
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {
            $val = encrypt_decrypt('decrypt',$request->email);
            $user = User::where('email', $val)->where('status', 1)->where('role', 2)->orderBy('id','DESC')->first();
            if(isset($user->id)){
                User::where('email', $val)->where('role', 2)->where('status', 1)->update([
                    'password' => Hash::make($request->password)
                ]);
                return redirect()->route('login')->with('success', 'Password reset successfully.');
            }
        }
    }
}
