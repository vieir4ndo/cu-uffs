<?php

namespace App\Services;

use App\Enums\UserCreationStatus;
use App\Helpers\StorageHelper;
use App\Repositories\UserPayloadRepository;

class UserPayloadService
{
    private $userCreationRepository;

    public function __construct(UserPayloadRepository $repository)
    {
        $this->userCreationRepository = $repository;
    }

    public function getByUid(string $uid, bool $shouldReturnPayloadAsArray = true)
    {
        $data = $this->userCreationRepository->getByUid($uid);

        $payload = StorageHelper::getFile($data->payload);
        $data->payload = json_decode($payload, $shouldReturnPayloadAsArray);

        return $data;
    }

    public function getStatusAndMessageByUid(string $uid)
    {
        return $this->userCreationRepository->getStatusAndMessageByUid($uid);
    }

    /**
     * @throws \Exception
     */
    public function create($user): void
    {
        $oldUserCreation = $this->userCreationRepository->getByUid($user["uid"]);

        if ($oldUserCreation != null and $oldUserCreation->status == UserCreationStatus::Suceed->value) {
            throw new \Exception("User already has an account.");
        }

        $payload = StorageHelper::saveUserPayload($user["uid"], json_encode($user));

        if ($oldUserCreation == null) {
            $this->userCreationRepository->create([
                "uid" => $user["uid"],
                "status" => UserCreationStatus::Solicitaded,
                "payload" => $payload,
                "message" => null
            ]);
        } else {
            $this->userCreationRepository->update($user["uid"], [
                "status" => UserCreationStatus::Solicitaded,
                "payload" => $payload,
                "message" => null
            ]);
        }
    }

    public function updatePayloadByUid(string $uid, $user)
    {
        StorageHelper::deleteUserPayload($uid);

        StorageHelper::saveUserPayload($uid, json_encode($user));
    }

    public function deletePayloadByUid(string $uid)
    {
        StorageHelper::deleteUserPayload($uid);

        $this->userCreationRepository->update($uid, [
            "payload" => null
        ]);
    }

    public function updateStatusAndMessageByUid(string $uid, $status, $message = null)
    {
        $data = [
            "status" => $status,
            "message" => $message
        ];

        $this->userCreationRepository->update($uid, $data);
    }
}
