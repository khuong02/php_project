<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthUI extends Controller
{
    public function loginPage()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function ForgotPassworPage()
    {
        return view('content.authentications.auth-forgot-password-basic');
    }
}
