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
    public function superAdminLogout()
    {
        Auth::logout();
        return redirect()->route('SA.LoginShow');
    }
    public function contentCreatorLogout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}