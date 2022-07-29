<?php

namespace App\Http\Controllers\Api;

use App\Enums\Operation;
use App\Jobs\StartCreateOrUpdateUserJob;
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

            $created = $this->service->getUserByUsernameFirstOrDefault($user['uid']);

            if ($created) {
                return ApiResponse::conflict("User already has an account.");
            }

            $this->userPayloadService->create($user, Operation::UserCreationWithIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($user["uid"]);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }


    public function createUserWithoutIdUFFS(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!$request->user()->isRUEmployee()) {
                return ApiResponse::forbidden('User is not allowed to do this operation.');
            }

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

            $created = $this->service->getUserByUsernameFirstOrDefault($user['uid']);

            if ($created) {
                return ApiResponse::conflict("User already has an account.");
            }

            $this->userPayloadService->create($user, Operation::UserCreationWithoutIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($user["uid"]);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getUserOperationStatus($uid)
    {
        try {
            $operation = $this->userPayloadService->getStatusAndMessageByUid($uid);

            if (empty($operation)) {

                $created = $this->service->getUserByUsernameFirstOrDefault($uid);

                if ($created) {
                    return ApiResponse::ok("User has no operation in progress.");
                }

                return ApiResponse::noContent(null);
            }

            return ApiResponse::Ok($operation);
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
                "enrollment_id" => $request->enrollment_id,
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
                "uid" => $uid
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithIdUFFSRules($request->enrollment_id));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $created = $this->service->getUserByUsernameFirstOrDefault($uid);

            if (!$created) {
                return ApiResponse::conflict("User does not have an account.");
            }

            $this->userPayloadService->create($user, Operation::UserUpdateWithIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($uid);

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
                "profile_photo" => $request->profile_photo,
                "birth_date" => $request->birth_date,
                "uid" => $uid
            ];

            $user = array_filter($user);

            $validation = Validator::make($user, $this->updateUserWithoutIdUFFSRules($request->email));

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $created = $this->service->getUserByUsernameFirstOrDefault($uid);

            if (!$created) {
                return ApiResponse::conflict("User does not have an account.");
            }

            $this->userPayloadService->create($user, Operation::UserUpdateWithoutIdUFFS);

            StartCreateOrUpdateUserJob::dispatch($uid);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function changeUserType(Request $request)
    {
        try {
            if (!$request->user()->isRUEmployee()) {
                return ApiResponse::forbidden('User is not allowed to do this operation.');
            }

            $data = [
                'uid' => $request->uid,
                "type" => $request->type,
            ];

            $validation = Validator::make($data, $this->changeUserTypeRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            unset($data['uid']);
            $savedUser = $this->service->changeUserType($request->uid, $data);

            return ApiResponse::ok($savedUser);
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

    private function changeUserTypeRules()
    {
        return [
            'uid' => ['required', 'string'],
            'type' => ['required', 'int'],
        ];
    }

    private function updateUserWithIdUFFSRules($enrollment_id): array
    {
        return [
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
            'enrollment_id' => ['required', 'string', 'max:10', 'min:10', 'unique:users', Rule::in(array_keys(config('course.chapeco')))],
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
            'profile_photo' => ['required', 'string'],
            'birth_date' => ['required', 'date']
        ];
    }
}
