<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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
            if(Auth::user()->role == 2){
                return redirect()->route('home.index');
            }else{
                Auth::logout();
            }
        } else {
            return view('auth.login');
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
            // dd($request->all());
            if (isset($user->id) && $user->role == 2) {
                if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                    $user = auth()->user()->role;
                    if ($user == 2) {
                        return redirect()->route('home.index');
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
        return redirect()->intended();
    }
}