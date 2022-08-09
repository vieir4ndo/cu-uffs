<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\IUserPayloadService;
use App\Interfaces\Services\IAuthService;

use App\Models\Api\ApiResponse;
use App\Models\User;
use Carbon\Carbon;
use App\Enums\Operation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Validators\UserValidator;
use App\Http\Validators\AuthValidator;
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

    public function index() {
        return view('user.index');
    }

    public function create() {
        $title = 'Novo Usuário';

        return view('user.form', [
            'title' => $title
        ]);
    }

    public function form(Request $request){
        try {
            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date
            ];

            $validation = Validator::make($user, UserValidator::createUserWitoutIdUFFSRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $created = $this->service->getUserByUsernameFirstOrDefault($user['uid']);

            if ($created) {
                return ApiResponse::conflict("User already has an account.");
            }

            $this->userPayloadService->create($user, Operation::UserCreationWithoutIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($user["uid"]);

            return redirect()->route('web.user.index');
        } catch (Exception $e) {
            echo($e->getMessage());
            // return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function resetPassword($uid) {
        try {
            $validation = Validator::make(["uid" => $uid], AuthValidator::forgotPasswordRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->authService->forgotPassword($uid);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}