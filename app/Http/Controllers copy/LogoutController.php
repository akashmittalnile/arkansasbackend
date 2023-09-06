<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function perform()
    {
        Session::flush();
        if (Auth::user()->role == 3) {
            $url = 'super-admin/login';
        } else {
            $url = 'login';
        }
        
        Auth::logout();
        return redirect($url);
    }
}