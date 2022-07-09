<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserCreationStatus;
use App\Jobs\User\StartUserCreationJob;
use App\Models\Api\ApiResponse;
use App\Services\UserCreationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCreationController
{
    private UserCreationService $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
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

            $this->userCreationService->create($user);

            StartUserCreationJob::dispatch($user["uid"]);

            return ApiResponse::accepted();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getUserCreation($uid)
    {
        try {
            $this->userCreationService->updateStatusAndMessageByUid($uid, UserCreationStatus::Starting);

            $userCreation = $this->userCreationService->getStatusAndMessageByUid($uid);

            return ApiResponse::Ok($userCreation);
        } catch (\Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
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
}
