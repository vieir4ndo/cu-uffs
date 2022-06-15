<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Helpers\StorageHelper;

class UserService
{
    private UserRepository $repository;
    private BarcodeService $barcodeService;

    public function __construct(UserRepository $userRepository, BarcodeService $barcodeService)
    {
        $this->repository = $userRepository;
        $this->barcodeService = $barcodeService;
    }

    public function createUser($user)
    {
        $user["password"] = Hash::make($user["password"]);
        $user["profile_photo"] = StorageHelper::saveProfilePhoto($user["uid"], $user["profile_photo"]);
        $user["bar_code"] = StorageHelper::saveBarCode($user["uid"], $this->barcodeService->generateBase64($user["enrollment_id"]));

        $this->repository->createUser($user);

        return $user;
    }

    public function getUserByUsername(string $uid): \App\Models\User
    {
        $user = $this->repository->getUserByUsername($uid);

        if (empty($user))
            throw new Exception("Não há usuário cadastrado com esse username");

        $user->profile_photo = StorageHelper::getFile($user->profile_photo);
        $user->bar_code = StorageHelper::getFile($user->bar_code);

        return $user;
    }

    public function deleteUserByUsername(string $uid): bool
    {
        $user = $this->getUserByUsername($uid);

        StorageHelper::deleteProfilePhoto($uid);
        StorageHelper::deleteBarCode($uid);

        return $this->repository->deleteUserByUsername($user->uid);
    }

    public function updateUser(string $uid, $data): User {
        $user = $this->getUserByUsername($uid);

        if (isset($data["profile_photo"])){
            StorageHelper::deleteProfilePhoto($uid);
            $data["profile_photo"] = StorageHelper::saveProfilePhoto($uid, $data["profile_photo"]);
        }

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

}
