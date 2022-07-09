<?php

namespace App\Services;

use App\Enums\UserCreationStatus;
use App\Repositories\UserCreationRepository;

class UserCreationService
{
    private $userCreationRepository;

    public function __construct(UserCreationRepository $repository)
    {
        $this->userCreationRepository = $repository;
    }

    public function getByUid(string $uid, bool $shouldReturnPayloadAsArray = true)
    {
        $data = $this->userCreationRepository->getByUid($uid);
        if ($shouldReturnPayloadAsArray)
            $data->payload = json_decode($data->payload, true);
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

        if ($oldUserCreation == null) {
            $this->userCreationRepository->create([
                "uid" => $user["uid"],
                "status" => UserCreationStatus::Solicitaded,
                "payload" => json_encode($user),
                "message" => null
            ]);
        } else {
            $this->userCreationRepository->update($user["uid"], [
                "status" => UserCreationStatus::Solicitaded,
                "payload" => json_encode($user),
                "message" => null
            ]);
        }
    }

    public function updatePayloadByUid(string $uid, $user)
    {
        $creationUserBd = $this->getByUid($uid, false);

        $this->userCreationRepository->update($uid, [
            "status" => $creationUserBd->status,
            "payload" => json_encode($user),
            "message" => $creationUserBd->message
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
