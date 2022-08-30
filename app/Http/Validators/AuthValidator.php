<?php

namespace App\Http\Validators;

class AuthValidator
{
    public static function resetPasswordRules()
    {
        return [
            'new_password' => [
                'required',
                'string',
            ]
        ];
    }

    public static function forgotPasswordRules()
    {
        return [
            'uid' => [
                'required',
                'string',
            ]
        ];
    }

    public static function loginRules()
    {
        return [
            'uid' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ]
        ];
    }
}
