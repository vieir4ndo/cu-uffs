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

    public function __construct(UserRepository $userRepository, BarcodeService $barcodeService, AiPassportPhotoService $aiPassportPhotoService)
    {
        $this->repository = $userRepository;
        $this->barcodeService = $barcodeService;
        $this->aiPassportPhotoService = $aiPassportPhotoService;
    }

    /**
     * @throws Exception
     */
    public function createUser($user)
    {
        if (in_array($user["type"], config("user.users_auth_iduffs"))) {
            $this->validateAtIdUffs($user["uid"], $user["password"]);
        }

        $user["profile_photo"] = $this->aiPassportPhotoService->validatePhoto($user["profile_photo"]);

        $user["password"] = Hash::make($user["password"]);
        $user["profile_photo"] = StorageHelper::saveProfilePhoto($user["uid"], $user["profile_photo"]);
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
        $user = $this->getUserByUsername($uid);

        if (isset($data["profile_photo"])) {
            StorageHelper::deleteProfilePhoto($uid);
            $data["profile_photo"] = StorageHelper::saveProfilePhoto($uid, $data["profile_photo"]);
        }

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

    private function validateAtIdUffs($uid, $password)
    {
        $credentials = [
            'user' => $uid,
            'password' => $password,
        ];

        $auth = new AuthIdUFFS();
        $user_data = $auth->login($credentials);

        if (!$user_data) {
            throw new Exception("The IdUFFS password does not match the one informed.");
        }
    }

}
