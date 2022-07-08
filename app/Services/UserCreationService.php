<?php

namespace App\Services;

use App\Enums\UserCreationStatus;
use App\Helpers\StorageHelper;
use App\Models\User;
use App\Models\UserCreation;
use App\Repositories\UserCreationRepository;

class UserCreationService
{
    private $userCreationRepository;

    public function __construct(UserCreationRepository $repository)
    {
        $this->userCreationRepository = $repository;
    }

    public function getByUid(string $uid){
        return $this->userCreationRepository->getByUid($uid);
    }

    /**
     * @throws \Exception
     */
    public function create($user): void
    {
        $oldUserCreation = $this->userCreationRepository->getByUid($user["uid"]);

        if ($oldUserCreation != null and $oldUserCreation->status == UserCreationStatus::Suceed->value){
            throw new \Exception("User already has an account.");
        }

        if ($oldUserCreation == null) {
            $this->userCreationRepository->create([
                "uid" => $user["uid"],
                "status" => UserCreationStatus::Solicitaded,
                "payload" => $user,
            ]);
        }
        else {
            $this->userCreationRepository->update($user["uid"], [
                "status" => UserCreationStatus::Solicitaded,
                "payload" => $user,
                "message" => null
            ]);
        }
    }

    public function updatePayloadByUid(string $uid, $user)
    {
        $data = [
            "payload" => json_encode($user),
        ];

        $this->userCreationRepository->update($uid, $data);
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
