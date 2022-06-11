<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService{

    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function createUser(User $user)
    {
        $data = [
            'uid' => $user->uid,
            'email' => $user->email,
            'name' => $user->name,
            'password' => Hash::make($user->password)
        ];

       $this->repository->createUser($data);

        return $user;
    }

    public function getUserByUsername(string $username) : \App\Models\User{
        return $this->repository->getUserByUsername($username);
    }
}
