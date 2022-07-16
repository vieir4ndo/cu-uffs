<?php

namespace App\Http\Controllers\Api;

use App\Enums\Operation;
use App\Jobs\StartUserCreationJob;
use App\Jobs\StartUserUpdateJob;
use App\Models\Api\ApiResponse;
use App\Services\UserPayloadService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController
{
    private UserService $service;
    private UserPayloadService $userPayloadService;


    public function __construct(UserService $userService, UserPayloadService $userPayloadService)
    {
        $this->service = $userService;
        $this->userPayloadService = $userPayloadService;
    }

    public function createUserWithIdUFFS(Request $request)
    {
        try {
            $user = [
                "uid" => $request->uid,
                "password" => $request->password,
                "profile_photo" => $request->profile_photo,
                "enrollment_id" => $request->enrollment_id,
                "birth_date" => $request->birth_date
            ];

            $validation = Validator::make($user, $this->createUserWithIdUFFSRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $created = $this->userPayloadService->create($user, Operation::UserCreationWithIdUFFS);

            if (!$created){
                return ApiResponse::ok("User already has an account.");
            }

            StartUserCreationJob::dispatch($user["uid"]);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }


    public function createUserWithoutIdUFFS(Request $request)
    {
        try {
            $user = [
                "uid" => $request->uid,
                "email" => $request->email,
                "name" => $request->name,
                "password" => $request->password,
                "type" => ($request->type != 0) ? $request->type : null,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date
            ];

            $validation = Validator::make($user, $this->createUserWitoutIdUFFSRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $created = $this->userPayloadService->create($user, Operation::UserCreationWithoutIdUFFS);

            if (!$created){
                return ApiResponse::ok("User already has an account.");
            }

            StartUserCreationJob::dispatch($user["uid"]);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getUserOperationStatus($uid)
    {
        try {
            $userCreation = $this->userPayloadService->getStatusAndMessageByUid($uid);

            return ApiResponse::Ok($userCreation);
        } catch (\Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getUser($uid)
    {
        try {
            $user = $this->service->getUserByUsername($uid);

            return ApiResponse::ok($user);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function changeUserActivity(Request $request, string $uid)
    {
        try {
            $user = [
                "active" => $request->active,
            ];

            $validation = Validator::make($user, $this->changeUserActivityUserRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $savedUser = $this->service->deactivateUser($uid, $user);

            return ApiResponse::ok($savedUser);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUserWithIdUFFS(Request $request, string $uid)
    {
        try {
            $user = [
                "type" => ($request->type and $request->type != 0) ? $request->type : null,
                "enrollment_id" => $request->enrollment_id,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
                "uid"=> $uid
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithIdUFFSRules($request->enrollment_id));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->userPayloadService->create($user, Operation::UserUpdateWithIdUFFS);

            StartUserUpdateJob::dispatch($uid);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateUserWithoutIdUFFS(Request $request, string $uid)
    {
        try {
            $user = [
                "email" => $request->email,
                "name" => $request->name,
                "type" => $request->type,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
                "uid"=> $uid
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithoutIdUFFSRules($request->email));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->userPayloadService->create($user, Operation::UserUpdateWithoutIdUFFS);

            StartUserUpdateJob::dispatch($uid);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function changeUserActivityUserRules()
    {
        return [
            'active' => ['required', 'bool'],
        ];
    }

    private function updateUserWithIdUFFSRules($enrollment_id): array
    {
        return [
            'type' => [Rule::in(config('user.users_auth_iduffs'))],
            'profile_photo' => ['string'],
            'enrollment_id' => [Rule::unique('users')->ignore($enrollment_id, 'enrollment_id'), 'string', 'max:10', 'min:10'],
            'birth_date' => ['date']
        ];
    }

    private function updateUserWithoutIdUFFSRules($email): array
    {
        return [
            'email' => [Rule::unique('users')->ignore($email, 'email'), 'email'],
            'name' => ['string', 'max:255'],
            'type' => [Rule::notIn(config('user.users_auth_iduffs'))],
            'profile_photo' => ['string'],
            'birth_date' => ['date']
        ];
    }

    private function createUserWithIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string'],
            'profile_photo' => ['required', 'string'],
            'enrollment_id' => ['required', 'string', 'max:10', 'min:10', 'unique:users'],
            'birth_date' => ['required', 'date']
        ];
    }

    private function createUserWitoutIdUFFSRules()
    {
        return [
            "uid" => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'type' => [Rule::notIn(config('user.users_auth_iduffs')), 'required'],
            'profile_photo' => ['required', 'string'],
            'birth_date' => ['required', 'date']
        ];
    }

}
