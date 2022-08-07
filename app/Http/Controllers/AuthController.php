<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function redirectToResetPassword($uid, $token)
    {
        return view('auth.reset-password', ['uid' => $uid, 'token' => $token]);
    }
}
