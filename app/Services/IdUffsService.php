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
            'email' => $user_data->email,
            'password' => $password
        ];
    }

    public function isActive(string $enrollment_id)
    {
        $response = $this->client->get($this->activeUserApi);

        $viewState = StringHelper::getText('/id\="javax.faces.ViewState" value\="(.*?)"/i', $response->getBody());

        $captcha = $this->captchaMonsterService->breakRecaptcha($this->activeUserApi, $this->googleKey);

        $response = $this->client->post("{$this->activeUserApi}",
            [
                RequestOptions::FORM_PARAMS => $this->getIsActivePayload($enrollment_id, $viewState, $captcha),
                RequestOptions::HEADERS => $response->getHeaders()
            ]
        );

        if (!StringHelper::checkIfContains($response->getBody(), "Vínculo ativo")) {
            throw new Exception("User is not active at IdUFFS");
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

    private function getIsActiveHeaders($cookie)
    {
        return [
            "Content-Type" => "text/html;charset=UTF-8",
            "Vary" => "Accept-Encoding",
            "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Accept-Encoding" => "gzip, deflate, br",
            "Accept-Language" => "en-US,en;q=0.9,pt;q=0.8",
            "Cache-Control" => "max-age=0",
            "Host" => "sci.uffs.edu.br",
            "Origin" => "https://sci.uffs.edu.br",
            "Referer" => "https://sci.uffs.edu.br//validar_vinculo.jsf",
            "sec-ch-ua" => '.Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
            "sec-ch-ua-mobile" => "?0",
            "sec-ch-ua-platform" => "Windows",
            "Sec-Fetch-Dest" => "document",
            "Sec-Fetch-Mode" => "navigate",
            "Sec-Fetch-Site" => "same-origin",
            "Sec-Fetch-User" => "?1",
            "Upgrade-Insecure-Requests" => "1",
            "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36",
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
