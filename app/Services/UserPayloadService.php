<?php

namespace App\Services;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Helpers\StorageHelper;
use App\Repositories\UserPayloadRepository;

class UserPayloadService
{
    private UserPayloadRepository $userPayloadRepository;
    private UserService $userService;

    public function __construct(UserPayloadRepository $repository, UserService $userService)
    {
        $this->userPayloadRepository = $repository;
        $this->userService = $userService;
    }

    public function getByUid(string $uid, bool $shouldReturnPayloadAsArray = true)
    {
        $data = $this->userPayloadRepository->getByUid($uid);

        $payload = StorageHelper::getFile($data->payload);
        $data->payload = json_decode($payload, $shouldReturnPayloadAsArray);

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function getStatusAndMessageByUid(string $uid)
    {
        $user = $this->userService->getUserByUsernameFirstOrDefault($uid);

        if ($user) {
            throw new \Exception("User already has an account.");
        }

        return $this->userPayloadRepository->getStatusByUid($uid);
    }

    /**
     * @throws \Exception
     */
    public function create($user, $operation) : bool
    {
        $userDb = $this->userService->getUserByUsernameFirstOrDefault($user["uid"]);

        if ($userDb and in_array($operation, [Operation::UserCreationWithIdUFFS, Operation::UserCreationWithoutIdUFFS])) {
            return false;
        }

        $payload = StorageHelper::saveUserPayload($user["uid"], json_encode($user));

        $this->userPayloadRepository->updateOrCreate([
            "uid" => $user["uid"],
            "status" => UserOperationStatus::Solicitaded,
            "message" => null,
            "payload" => $payload,
            "operation" => $operation
        ]);

        return true;
    }

    public function updatePayloadByUid(string $uid, $user)
    {
        StorageHelper::deleteUserPayload($uid);

        StorageHelper::saveUserPayload($uid, json_encode($user));
    }

    public function deletePayloadByUid(string $uid)
    {
        StorageHelper::deleteUserPayload($uid);

        $this->userPayloadRepository->update($uid, [
            "payload" => null
        ]);
    }

    public function updateStatusAndMessageByUid(string $uid, $status, $message = null)
    {
        $data = [
            "status" => $status,
            "message" => $message
        ];

        $this->userPayloadRepository->update($uid, $data);
    }

    public function deleteByUid(string $uid){
        $this->deletePayloadByUid($uid);

        $this->userPayloadRepository->delete($uid);
    }
}
