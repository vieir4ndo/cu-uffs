<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Interfaces\Services\IIdUffsService;
use CCUFFS\Auth\AuthIdUFFS;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Hash;

class IdUffsService implements IIdUffsService
{
    private $client;
    private $activeUserApi = "https://sci.uffs.edu.br/validar_vinculo.jsf";
    private $googleKey = "6Lfx__cSAAAAAMxp5uRHBvfjcjHBdtvizuoGpDpg";
    private $captchaMonsterService;

    public function __construct()
    {
        $this->captchaMonsterService = new CaptchaMonsterService();
        $this->client = new Client();
    }

    public function authWithIdUFFS(string $uid, string $password)
    {
        $credentials = [
            'user' => $uid,
            'password' => $password,
        ];

        $auth = new AuthIdUFFS();
        $user_data = $auth->login($credentials);

        if (!$user_data) {
            return null;
        }

        $password = Hash::make($user_data->pessoa_id);

        return [
            'password' => $password
        ];
    }

    public function isActive(string $enrollment_id): bool
    {
        $response = $this->client->get($this->activeUserApi);

        $viewState = StringHelper::getText('/id\="javax.faces.ViewState" value\="(.*?)"/i', $response->getBody());

        $captcha = $this->captchaMonsterService->breakRecaptcha($this->activeUserApi, $this->googleKey);

        $response = $this->client->post($this->activeUserApi,
            [
                RequestOptions::BODY => $this->getIsActivePayload($enrollment_id, $viewState, $captcha)
            ]
        );

        return StringHelper::checkIfContains($response->getBody(), "Vínculo ativo");
    }

    private function getIsActivePayload(string $enrollment_id, string $viewState, string $recaptcha)
    {
        return [
            'formValidar' => 'formValidar',
            'formValidar:codigoDeBarras' => $enrollment_id,
            'formValidar:botaoValidar' => '',
            'g-recaptcha-response' => $recaptcha,
            'javax.faces.ViewState' => $viewState
        ];
    }

    public function validateAtIdUffs($uid, $password)
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
