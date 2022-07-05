<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Interfaces\Services\IIdUffsService;
use CCUFFS\Auth\AuthIdUFFS;
use Exception;
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
        $this->client = new HttpClient();
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
            'email' => $user_data->email,
            'password' => $password
        ];
    }

    /**
     * @throws Exception
     */
    public function isActive(string $enrollment_id)
    {
        if (env('SHOULD_VALIDATE_ENROLLMENT_ID', true)) {
            $response = $this->client->get($this->activeUserApi);

            $viewState = StringHelper::getText('/id\="javax.faces.ViewState" value\="(.*?)"/i', $response->getBody());

            $captcha = $this->captchaMonsterService->breakRecaptcha($this->activeUserApi, $this->googleKey);

            $response = $this->client->post("{$this->activeUserApi}", $this->getIsActivePayload($enrollment_id, $viewState, $captcha));

            if (!StringHelper::checkIfContains($response->getBody(), "Vínculo ativo")) {
                throw new Exception("Enrollment_id is not active at IdUFFS.");
            }
        }
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

    /**
     * @throws Exception
     */
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
