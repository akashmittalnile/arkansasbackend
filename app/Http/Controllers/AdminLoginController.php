<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Mail\DefaultMail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminLoginController extends Controller
{
    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        if(Auth::check())
        {
            if(Auth::user()->role == 3){
                return redirect()->route('SA.Dashboard');
            }else{
                Auth::logout();
            }
        } else {
            return view('super-admin.login');  
        }
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    { 
        try{
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $user = User::where('email', $request->email)->where('status', 1)->first();
            if (isset($user->id) && $user->role == 3) {
                if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                    $user = auth()->user()->role;
                    if ($user == 3) {
                        return redirect()->route('SA.Dashboard');
                    } else return redirect()->back()->withInput()->with('success', 'These credentials do not match our records.');
                }else return redirect()->back()->withInput()->with('success', 'These credentials do not match our records.');
            } else return redirect()->back()->withInput()->with('success', 'These credentials do not match our records.');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended('super-admin/dashboard');
    }

    protected function AdminAuthenticated(Request $request, $user) 
    {
        return redirect()->intended('super-admin/dashboard');
    }

    public function forgot_password() 
    {
        return view('super-admin-layouts.forgot-password.forgot_password');
    }

    public function forgot_password_email(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {
            $user = User::where('email', $request->email)->where('status', 1)->where('role', 3)->first();
            if (isset($user->id)) {
                $code = rand(1000, 9999);

                $data['subject']    = 'Arkansas Admin Forgot Password OTP';
                $data['from_email'] = env('MAIL_FROM_ADDRESS');
                $data['site_title'] = 'Arkansas Admin Forgot Password OTP';
                $data['view'] = 'email.admin-otp';
                $data['otp'] = $code;
                $data['customer_name'] = $user->first_name ?? 'NA' + ' ' + $user->last_name ?? '';
                $data['to_email'] = $user->email ?? 'NA';
                sendEmail($data);

                User::where('email', $request->email)->where('status', 1)->where('role', 3)->update([
                    'verification_code' => $code
                ]);
                $user_email = encrypt_decrypt('encrypt',$request->email);
                return redirect()->route('SA.reset_password', $user_email)->with('message', 'Your OTP was '.$code);
            } else {
                return redirect()->back()->with('success', 'The email is not registered with Arkansas');
            }
        }
    }

    public function reset_password($email) 
    {   
        $decrypt_email = encrypt_decrypt('decrypt', $email);
        $user = User::where('email', $decrypt_email)->where('status', 1)->where('role', 3)->first();
        $otp = $user->verification_code ?? null;
        return view('super-admin-layouts.forgot-password.reset_password')->with(compact('email', 'otp'));
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
            $user = User::where('email', $val)->where('status', 1)->where('role', 3)->orderBy('id','DESC')->first();
            
            $otp = $request['otp1'].''.$request['otp2'].$request['otp3'].$request['otp4'];
            if (isset($user->id)) {
                $user_login_source = User::where('id', $user->id)->where('verification_code', $otp)->where('role', 3)->orderBy('id','DESC')->first();
                if (isset($user_login_source)) {
                    User::where('id', $user_login_source->id)->update([
                        'verification_code' => null
                    ]);
                    return redirect()->route('SA.change_password', $request->email);
                } else {
                    return redirect()->route('SA.reset_password', $request->email)->with('success','OTP is incorrect.');
                }
            } else {
                return redirect('/forgot_password');
            }
        }
    }

    public function change_password($email) 
    {
        return view('super-admin-layouts.forgot-password.change_password')->with(compact('email'));
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
            $user = User::where('email', $val)->where('status', 1)->where('role', 3)->orderBy('id','DESC')->first();
            if(isset($user->id)){
                User::where('email', $val)->where('status', 1)->where('role', 3)->update([
                    'password' => Hash::make($request->password)
                ]);
                return redirect()->route('SA.LoginShow')->with('success', 'Password reset successfully.');
            }
        }
    }
}