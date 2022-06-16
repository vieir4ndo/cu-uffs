<?php

namespace App\Http\Services;

use App\Enums\UserType;
use App\Models\User;
use CCUFFS\Auth\AuthIdUFFS;
use Illuminate\Support\Facades\Hash;
use Mailjet\Client;
use Mailjet\Resources;
use PHPUnit\Util\Exception;

class AuthService
{
    private UserService $service;
    private User $user;
    private Client $mailJetClient;


    public function __construct(UserService $userService)
    {
        $this->service = $userService;
        $this->mailJetClient = new Client(env("MAILJET_SECRETKEY", "xpto"), env("MAILJET_PUBLICKEY", "xpto"), true, ['version' => 'v3.1']);
    }

    public function login($uid, $password)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if ($this->user->type == UserType::RUEmployee->value or $this->user->type == UserType::default->value) {
            $data = $this->authWithIdUFFS($uid, $password);
            $this->user->update($data);
        } else {
            if (!Hash::check($password, $this->user->password)) {
                throw new Exception("The password is incorrect.");
            }
        }

        $this->user->tokens()->delete();
        return $this->user->createToken($uid)->plainTextToken;
    }

    public function authWithIdUFFS($uid, $password)
    {
        $credentials = [
            'user' => $uid,
            'password' => $password,
        ];

        $auth = new AuthIdUFFS();
        $user_data = $auth->login($credentials);

        if (!$user_data) {
            throw new Exception("The password is incorrect.");
        }

        $password = Hash::make($user_data->pessoa_id);

        return [
            'password' => $password
        ];
    }

    public function forgotPassword(string $uid) : void
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        if ($this->user->type != UserType::ThirdPartyEmployee->value) {
            throw new Exception("User not allowed to reset password. Please continue at <a href='https://id.uffs.edu.br/id/XUI/?realm=/#passwordReset/'>IdUFFS</a>.");
        }

        $this->user->tokens()->delete();
        $token = $this->user->createToken($uid)->plainTextToken;

        $redirectTo = env("APP_URL") . "/reset-password?uid={$uid}&token={$token}";

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => env("MAILJET_SENDEREMAIL"),
                        'Name' => env("APP_NAME")
                    ],
                    'To' => [
                        [
                            'Email' => $this->user->email,
                            "Name" => $this->user->name
                        ]
                    ],
                    'Subject' => "Recuperação de senha ". env("APP_NAME"),
                    'HTMLPart' => "Token: {$token} </br>Uid: {$uid}</br>Mandem esses caras e a nova senha pra api /reset-password/{uid} e se quiserem um layout bonitinho aqui, favo encaminhar, aguardo url do front pra concatenar e direicionar o pessoal pra lá."
                ]
            ]
        ];

        $response = $this->mailJetClient->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()){
            throw new Exception("It wasn't possible to send your email. Please try again later.");
        }

    }

    public function resetPassword(string $uid, string $newpassword)
    {
        $this->user = $this->service->getUserByUsername($uid, false);

        $data = [
            'password' => Hash::make($newpassword)
        ];

        $this->user->update($data);
    }
}
