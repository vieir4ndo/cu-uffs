<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use CCUFFS\Auth\AuthIdUFFS;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Helpers\StorageHelper;

class UserService implements IUserService
{
    private UserRepository $repository;
    private BarcodeService $barcodeService;
    private AiPassportPhotoService $aiPassportPhotoService;
    private IdUffsService $idUffsService;

    public function __construct(
        UserRepository         $userRepository,
        BarcodeService         $barcodeService,
        AiPassportPhotoService $aiPassportPhotoService,
        IdUffsService          $idUffsService
    )
    {
        $this->repository = $userRepository;
        $this->barcodeService = $barcodeService;
        $this->aiPassportPhotoService = $aiPassportPhotoService;
        $this->idUffsService = $idUffsService;
    }

    /**
     * @throws Exception
     */
    public function createUser($user)
    {
        if (in_array($user["type"], config("user.users_auth_iduffs"))) {
            $this->idUffsService->isActive($user["enrollment_id"]);
            $this->idUffsService->validateAtIdUffs($user["uid"], $user["password"]);
        }

        if (in_array($user["type"], config("user.users_generate_enrollment_id"))) {
            $user["enrollment_id"] = bin2hex(random_bytes(5));
        }

        $user["profile_photo"] = $this->aiPassportPhotoService->validatePhoto($user["profile_photo"]);
        $user["profile_photo"] = StorageHelper::saveProfilePhoto($user["uid"], $user["profile_photo"]);

        $user["password"] = Hash::make($user["password"]);

        $user["bar_code"] = StorageHelper::saveBarCode($user["uid"], $this->barcodeService->generateBase64($user["enrollment_id"]));

        $this->repository->createUser($user);

        return $this->getUserByUsername($user["uid"]);
    }

    public function getUserByUsername(string $uid, $withFiles = true): \App\Models\User
    {
        $user = $this->repository->getUserByUsername($uid);

        if (empty($user))
            throw new Exception("User not found.");

        if ($withFiles) {
            $user->profile_photo = StorageHelper::getFile($user->profile_photo);
            $user->bar_code = StorageHelper::getFile($user->bar_code);
        }

        return $user;
    }

    public function getUserByUsernameFirstOrDefault(string $uid, $withFiles = true): ?\App\Models\User
    {
        $user = $this->repository->getUserByUsername($uid);

        if (empty($user))
            return null;

        if ($withFiles) {
            $user->profile_photo = StorageHelper::getFile($user->profile_photo);
            $user->bar_code = StorageHelper::getFile($user->bar_code);
        }

        return $user;
    }

    public function deleteUserByUsername(string $uid): bool
    {
        $user = $this->getUserByUsername($uid);

        StorageHelper::deleteProfilePhoto($user->uid);
        StorageHelper::deleteBarCode($user->uid);

        $user->tokens()->delete();

        return $this->repository->deleteUserByUsername($user->uid);
    }

    public function updateUser(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        if (isset($data["enrollment_id"]) and $data["enrollment_id"] != $user->enrollment_id) {
            StorageHelper::deleteBarCode($uid);
            $data["bar_code"] = StorageHelper::saveBarCode($user->uid, $this->barcodeService->generateBase64($data["enrollment_id"]));
        }

        if (isset($data["profile_photo"])) {
            StorageHelper::deleteProfilePhoto($uid);
            $data["profile_photo"] = $this->aiPassportPhotoService->validatePhoto($data["profile_photo"]);
            $data["profile_photo"] = StorageHelper::saveProfilePhoto($user->uid, $data["profile_photo"]);
        }

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

    public function deactivateUser(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

}
