<?php

namespace App\Http\Controllers;

use App\Http\Validators\AuthValidator;
use App\Http\Validators\UserValidator;
use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\IUserPayloadService;
use App\Interfaces\Services\IAuthService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Enums\Operation;
use App\Jobs\StartCreateOrUpdateUserJob;


class UserController extends Controller
{
    private IUserService $service;
    private IUserPayloadService $userPayloadService;
    private IAuthService $authService;

    public function __construct(IUserService $service, IUserPayloadService $userPayloadService, IAuthService $authService)
    {
        $this->service = $service;
        $this->userPayloadService = $userPayloadService;
        $this->authService = $authService;
    }

    public function index()
    {
        return view('user.index');
    }

    public function create()
    {
        $title = 'Novo Usuário';

        return view('user.form', [
            'title' => $title
        ]);
    }

    public function form(Request $request)
    {
        try {
            // Converter data para dd-mm-yyyy
            $birth_date = str_replace('/', '-', $request->birth_date);
            // e depois formatar para yyyy-mm-dd
            $formatted_date = date('Y-m-d', strtotime($birth_date));

            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $formatted_date
            ];

            $validation = Validator::make($user, UserValidator::createUserWitoutIdUFFSRules());

            if ($validation->fails()) {
                $errors = $validation->errors()->all();
            }

            $created = $this->service->getUserByUsernameFirstOrDefault($user['uid']);

            if ($created) {
                Alert::error('Erro', 'Usuário informado já tem uma conta.');
                return back();
            }

            $this->userPayloadService->create($user, Operation::UserCreationWithoutIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($user["uid"]);

            Alert::success('Sucesso', 'Usuário registrado com sucesso!');
            return redirect()->route('web.user.index');
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }

    public function forgotPassword($uid)
    {
        try {
            $validation = Validator::make(["uid" => $uid], AuthValidator::forgotPasswordRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->authService->forgotPassword($uid);

            // return ApiResponse::ok(null);
            return redirect()->route('web.user.index');
        } catch (Exception $e) {
            // return ApiResponse::badRequest($e->getMessage());
            return redirect()->route('web.user.index');
        }
    }

}
