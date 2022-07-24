<?php

namespace App\Services;

use App\Enums\UserType;
use App\Interfaces\Services\IAuthService;
use App\Models\User;
use CCUFFS\Auth\AuthIdUFFS;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService implements IAuthService
{
    private UserService $service;
    private User $user;
    private MailjetService $mailjetService;
    private IdUffsService $idUffsService;

    public function __construct(UserService $userService, MailjetService $mailjetService, IdUffsService $idUffsService)
    {
        $this->service = $userService;
        $this->mailjetService = $mailjetService;
        $this->idUffsService = $idUffsService;
    }

    public function login($uid, $password)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
            $data = $this->idUffsService->authWithIdUFFS($uid, $password);

            if ($data == null) {
                throw new Exception("The password is incorrect.");
            }

            $this->service->updateUser($this->user, $data);
        } else {
            if (!Hash::check($password, $this->user->password)) {
                throw new Exception("The password is incorrect.");
            }
        }

        $this->user->tokens()->delete();
        return $this->user->createToken($uid)->plainTextToken;
    }

    public function forgotPassword(string $uid): void
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
            throw new Exception("User not allowed to reset password. Please continue at <a href='https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/'>IdUFFS</a>.");
        }

        $this->user->tokens()->delete();
        $token = $this->user->createToken($uid)->plainTextToken;

        $redirectTo = env("APP_URL") . "/reset-password?uid={$uid}&token={$token}";

        $message = "Token: {$token} </br>Uid: {$uid}</br>Mandem esses caras e a nova senha pra api /reset-password/{uid} e se quiserem um layout bonitinho aqui, favo encaminhar, aguardo url do front pra concatenar e direicionar o pessoal pra lá.";
        $subject = "Recuperação de senha " . env("APP_NAME");

        $this->mailjetService->send($this->user->email, $this->user->name, $subject, $message);
    }

    public function resetPassword(string $uid, string $newpassword)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
            throw new Exception("User not allowed to reset password. Please continue at <a href='https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/'>IdUFFS</a>.");
        }

        $data = [
            'password' => Hash::make($newpassword)
        ];

        $this->user->update($data);
    }
}
