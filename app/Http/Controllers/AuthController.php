<?php

namespace App\Http\Controllers;

use App\Http\Validators\AuthValidator;
use App\Interfaces\Services\IAuthService;
use App\Models\PersonalAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private IAuthService $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }

    public function redirectToResetPassword($uid, $token, $errors = null)
    {
        return view('auth.reset-password', ['uid' => $uid, 'token' => $token, 'errors' => $errors]);
    }

    public function resetPassword(Request $request){
            try {
                $tokenData = PersonalAccessToken::findToken($request->token)->first();

                $validation = Validator::make(["new_password" => $request->new_password], AuthValidator::resetPasswordRules());

                if ($validation->fails()) {
                    return $this->redirectToResetPassword($tokenData->name, $request->token, $validation->errors()->all());
                }

                $this->service->resetPassword($tokenData->name, $request->new_password);

                return view('auth.login');
            } catch (Exception $e) {
                $errors=[];
                $errors[] = $e->getMessage();
                return $this->redirectToResetPassword($tokenData->name, $request->token, $errors);
            }
    }
}
