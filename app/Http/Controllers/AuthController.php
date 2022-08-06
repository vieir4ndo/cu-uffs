<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function redirectToResetPassword(Request $request)
    {
        $uid = $request->route('uid');
        $token = $request->route('token');
        return view('auth.reset-password', ['token' => $token, 'uid' => $uid]);
    }
}
