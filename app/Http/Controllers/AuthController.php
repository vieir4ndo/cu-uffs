<?php

namespace App\Http\Controllers;

use App\Http\Validators\AuthValidator;
use App\Interfaces\Services\IAuthService;
use App\Models\PersonalAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    private IAuthService $service;

    public function __construct(IAuthService $service)
    {
        $this->service = $service;
    }

    public function index(){
        if (Auth::check()) {
            return redirect(config('fortify.home'));
        } else {
            return view('auth.login');
        }
    }
    public function redirectResetPassword(Request $request)
    {
        return view('auth.reset-password', ['uid' => $request->uid, 'token' => $request->token]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $tokenData = PersonalAccessToken::findToken($request->token)->first();

            $validation = Validator::make(["new_password" => $request->new_password], AuthValidator::resetPasswordRules());

            if ($validation->fails()) {
                Alert::error('Erro', Arr::flatten($validation->errors()->all()));
                return back();
            }

            $this->service->resetPassword($tokenData->name, $request->new_password);

            Alert::success('Sucesso', 'Senha alterada com sucesso!');
            return redirect()->route('web.auth.index');
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }
}
