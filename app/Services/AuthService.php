<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Interfaces\Services\IAuthService;
use App\Interfaces\Services\IIdUffsService;
use App\Interfaces\Services\IMailjetService;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService
{
    private IUserService $service;
    private User $user;
    private IMailjetService $mailjetService;
    private IIdUffsService $idUffsService;

    public function __construct(IUserService $userService, IMailjetService $mailjetService, IIdUffsService $idUffsService)
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
                throw new BadRequestException("A senha informada está incorreta.");
            }

            $this->service->updateUser($this->user, $data);
        } else {
            if (!Hash::check($password, $this->user->password)) {
                throw new BadRequestException("A senha informada está incorreta.");
            }
        }

        $this->user->tokens()->delete();
        return $this->user->createToken($uid)->plainTextToken;
    }

    public function forgotPassword(string $uid): void
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
            throw new BadRequestException("Usuário não pode alterar sua senha nessa plataforma. Por favor continue em <a href='https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/'>IdUFFS</a>.");
        }

        $this->user->tokens()->delete();

        $token = $this->user->createToken($uid)->plainTextToken;

        $redirectTo = env("APP_URL") . "/reset-password?uid={$uid}&token={$token}";

        $subject = "Recuperação de Senha " . env("APP_NAME");

        $message = strval(view('email.reset-password', ['name' => $this->user->name, 'route' => $redirectTo, 'subject' => $subject]));

        $this->mailjetService->send($this->user->email, $this->user->name, $subject, $message);
    }

    public function resetPassword(string $uid, string $newpassword)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if (in_array($this->user->type, config("user.users_auth_iduffs"))) {
            throw new BadRequestException("Usuário não pode alterar sua senha nessa plataforma. Por favor continue em <a href='https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/'>IdUFFS</a>.");
        }

        $data = [
            'password' => Hash::make($newpassword)
        ];

        $this->user->update($data);
    }
}
