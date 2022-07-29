<?php

namespace App\Services;

use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IAiPassportPhotoService;
use App\Interfaces\Services\IBarcodeService;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Helpers\StorageHelper;

class UserService implements IUserService
{
    private IUserRepository $repository;
    private IBarcodeService $barcodeService;
    private IAiPassportPhotoService $aiPassportPhotoService;

    public function __construct(
        IUserRepository         $userRepository,
        IBarcodeService         $barcodeService,
        IAiPassportPhotoService $aiPassportPhotoService,
    )
    {
        $this->repository = $userRepository;
        $this->barcodeService = $barcodeService;
        $this->aiPassportPhotoService = $aiPassportPhotoService;
    }

    public function createUserWithoutIdUFFS($user)
    {
        $user["enrollment_id"] = bin2hex(random_bytes(5));

        return $this->createUserBase($user);
    }

    /**
     * @param $user
     * @return User
     * @throws Exception
     */
    private function createUserBase($user): User
    {
        $user["profile_photo"] = $this->aiPassportPhotoService->validatePhoto($user["profile_photo"]);
        $user["profile_photo"] = StorageHelper::saveProfilePhoto($user["uid"], $user["profile_photo"]);

        $user["bar_code"] = StorageHelper::saveBarCode($user["uid"], $this->barcodeService->generateBase64($user["enrollment_id"]));

        $user["password"] = Hash::make($user["password"]);

        $user["birth_date"] = Carbon::parse($user["birth_date"]);

        $user["active"] = true;

        $this->repository->createOrUpdate($user);

        return $this->getUserByUsername($user["uid"]);
    }

    public function createOrUpdate($user){
        $this->repository->createOrUpdate($user);
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

    public function updateUserWithIdUFFS(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        if (!in_array($user->type, config('user.users_auth_iduffs'))) {
            $app_url = env('app_url');
            throw new Exception("Cannot update user through this endpoint, please use {$app_url}/api/user.");
        }

        return $this->updateUser($user, $data);
    }

    public function updateUserWithoutIdUFFS(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        if (in_array($user->type, config('user.users_auth_iduffs'))) {
            $app_url = env('app_url');
            throw new Exception("Cannot update user through this endpoint, please use {$app_url}/api/user/iduffs.");
        }

        return $this->updateUser($user, $data);
    }


    public function updateUser(User $user, $data): User
    {
        if (isset($data["birth_date"])) {
            $data["birth_date"] = Carbon::parse($data["birth_date"]);
        }

        if (isset($data["enrollment_id"]) and $data["enrollment_id"] != $user->enrollment_id) {
            StorageHelper::deleteBarCode($user->uid);
            $data["bar_code"] = StorageHelper::saveBarCode($user->uid, $this->barcodeService->generateBase64($data["enrollment_id"]));
        }

        if (isset($data["profile_photo"])) {
            StorageHelper::deleteProfilePhoto($user->uid);
            $data["profile_photo"] = $this->aiPassportPhotoService->validatePhoto($data["profile_photo"]);
            $data["profile_photo"] = StorageHelper::saveProfilePhoto($user->uid, $data["profile_photo"]);
        }

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($user->uid);
    }

    public function deactivateUser(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

    public function getAllUsersWithIdUFFS(){
        return $this->repository->getAllUsersWithIdUFFS();
    }

    public function changeUserType(string $uid, $data): User
    {
        $user = $this->getUserByUsername($uid, false);

        $this->repository->updateUserByUsername($user->uid, $data);

        return $this->getUserByUsername($uid);
    }

    public function getUserByEnrollmentId(string $enrollment_id, bool $withFiles = true) : User {
        $user = $this->repository->getUserByEnrollmentId($enrollment_id);

        if (empty($user))
            throw new Exception("User not found.");

        if ($withFiles) {
            $user->profile_photo = StorageHelper::getFile($user->profile_photo);
            $user->bar_code = StorageHelper::getFile($user->bar_code);
        }

        return $user;
    }

    public function updateTicketAmount($uid, $amount){
        $user = $this->repository->getUserByUsername($uid);

        return $this->repository->updateUserByUsername($uid, ["ticket_amount" => $user->ticket_amount + $amount]);
    }
}
