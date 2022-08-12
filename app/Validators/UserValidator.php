<?php

namespace App\Http\Validators;

use Illuminate\Validation\Rule;


class UserValidator
{
    public static function changeUserActivityUserRules()
    {
        return [
            'active' => ['required', 'bool'],
        ];
    }

    public static function changeUserTypeRules()
    {
        return [
            'uid' => ['required', 'string'],
            'type' => ['required', 'int'],
        ];
    }

    public static function updateUserWithIdUFFSRules($enrollment_id): array
    {
        return [
            'profile_photo' => ['string'],
            'enrollment_id' => [Rule::unique('users')->ignore($enrollment_id, 'enrollment_id'), 'string', 'max:10', 'min:10'],
            'birth_date' => ['date']
        ];
    }

    public static function updateUserWithoutIdUFFSRules($email): array
    {
        return [
            'email' => [Rule::unique('users')->ignore($email, 'email'), 'email'],
            'name' => ['string', 'max:255'],
            'profile_photo' => ['string'],
            'birth_date' => ['date']
        ];
    }

    public static function createUserWithIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string'],
            'profile_photo' => ['required', 'string'],
            'enrollment_id' => ['required', 'string', 'max:10', 'min:10', 'unique:users'],
            'birth_date' => ['required', 'date']
        ];
    }

    public static function createUserWitoutIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'profile_photo' => ['required', 'string'],
            'birth_date' => ['required', 'date']
        ];
    }
}
