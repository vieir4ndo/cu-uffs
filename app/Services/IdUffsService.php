<?php

namespace App\Services;

use App\Enums\UserType;
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
            'name' => $user_data->name,
            'email' => $user_data->email,
            'password' => $password
        ];
    }

    /**
     * @throws Exception
     */
    public function isActive(string $enrollment_id, string $name)
    {
        $response = $this->client->get($this->activeUserApi);

        $viewState = StringHelper::getText('/id\="javax.faces.ViewState" value\="(.*?)"/i', $response->getBody());

        $captcha = $this->captchaMonsterService->breakRecaptcha($this->activeUserApi, $this->googleKey);

        $response = $this->client->post("{$this->activeUserApi}", $this->getIsActivePayload($enrollment_id, $viewState, $captcha));

        if (!StringHelper::checkIfContains($response->getBody(), "Vínculo ativo") || !StringHelper::checkIfContains($response->getBody(),  $name)) {
            return null;
        }

        if (StringHelper::checkIfContains($response->getBody(), '<p class="descricaoVinculo">Estudante</p>')) {
            return [
                "status_enrollment_id" => true,
                "type" => UserType::Student->value,
                "course" => StringHelper::getText("/Matrícula {$enrollment_id} - (.*?)<\/span>/i", $response->getBody())
            ];
        } else {
            return [
                "status_enrollment_id" => true,
                "type" => UserType::Employee->value,
                "course" => null
            ];
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

}
