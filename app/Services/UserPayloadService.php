<?php

namespace App\Services;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Interfaces\Repositories\IUserPayloadRepository;
use App\Interfaces\Services\IUserPayloadService;
use App\Interfaces\Services\IUserService;
use Illuminate\Support\Facades\Crypt;

class UserPayloadService implements IUserPayloadService
{
    private IUserPayloadRepository $userPayloadRepository;
    private IUserService $userService;

    public function __construct(IUserPayloadRepository $repository, IUserService $userService)
    {
        $this->userPayloadRepository = $repository;
        $this->userService = $userService;
    }

    public function getByUid(string $uid, bool $shouldReturnPayloadAsArray = true)
    {
        $data = $this->userPayloadRepository->getByUid($uid);

        $payload = Crypt::decrypt($data->payload);
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
            return null;
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

        $this->userPayloadRepository->updateOrCreate([
            "uid" => $user["uid"],
            "status" => UserOperationStatus::Solicitaded,
            "message" => null,
            "payload" => Crypt::encrypt(json_encode($user)),
            "operation" => $operation
        ]);

        return true;
    }

    public function updatePayloadByUid(string $uid, $user)
    {
        $this->userPayloadRepository->update($uid, [
            "payload" => Crypt::encrypt(json_encode($user))
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
        $this->userPayloadRepository->delete($uid);
    }
}
