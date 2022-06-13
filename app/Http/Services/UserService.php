<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Models\Api\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Exception;

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
        $user["bar_code"] = $this->barcodeService->generateBase64($user["enrollment_id"]);

        $this->repository->createUser($user);

        return $user;
    }

    public function getUserByUsername(string $uid): \App\Models\User
    {
        $user = $this->repository->getUserByUsername($uid);

        if (empty($user))
            throw new Exception("Não há usuário cadastrado com esse username");

        return $user;
    }

    public function deleteUserByUsername(string $uid): bool
    {
        $user = $this->getUserByUsername($uid);

        return $this->repository->deleteUserByUsername($user->uid);
    }

    public function updateUser(string $uid, $data): User {
        $user = $this->getUserByUsername($uid);

        return $this->repository->updateUserByUsername($user->uid, $data);
    }
}
