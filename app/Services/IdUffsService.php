<?php

namespace App\Services;

use App\Enums\UserType;
use App\Helpers\StringHelper;
use App\Interfaces\Services\ICaptchaMonsterService;
use App\Interfaces\Services\IIdUffsService;
use CCUFFS\Auth\AuthIdUFFS;
use Exception;
use Illuminate\Support\Facades\Hash;

class IdUffsService implements IIdUffsService
{
    private $client;
    private $activeUserApi = "https://sci.uffs.edu.br/validar_vinculo.jsf";
    private $googleKey = "6Lfx__cSAAAAAMxp5uRHBvfjcjHBdtvizuoGpDpg";
    private ICaptchaMonsterService $captchaMonsterService;

    public function __construct(ICaptchaMonsterService $captchaMonsterService)
    {
        $this->captchaMonsterService = $captchaMonsterService;
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
        return (env('SHOULD_VALIDATE_ENROLLMENT_ID')) ? $this->scrapForEnrollmentIdValidation($enrollment_id, $name) : $this->validateEnrollmentIdLocally($enrollment_id);
    }

    private function scrapForEnrollmentIdValidation($enrollment_id, $name)
    {
        $response = $this->client->get($this->activeUserApi);

        $viewState = StringHelper::getText('/id\="javax.faces.ViewState" value\="(.*?)"/i', $response->getBody());

        $captcha = $this->captchaMonsterService->breakRecaptcha($this->activeUserApi, $this->googleKey);

        $response = $this->client->post("{$this->activeUserApi}", $this->getIsActivePayload($enrollment_id, $viewState, $captcha));

        if (!StringHelper::checkIfContains($response->getBody(), "Vínculo ativo") || !StringHelper::checkIfContains($response->getBody(), $name)) {
            return null;
        }

        if (StringHelper::checkIfContains($response->getBody(), '<p class="descricaoVinculo">Estudante</p>')) {

            if (array_keys(config('course.chapeco'), substr($enrollment_id, 3, 4))) {
                return null;
            }

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

    private function validateEnrollmentIdLocally($enrollment_id)
    {
        $courses_chapeco = array_keys(config('course.chapeco'));

        if (!in_array(substr($enrollment_id, 3, 4), $courses_chapeco)) {
            if (!$this->isEnrollmentIdStudentType($enrollment_id)) {
                return [
                    "status_enrollment_id" => false,
                    "type" => UserType::Employee->value,
                    "course" => null
                ];
            }
            return null;
        } else {
            return [
                "status_enrollment_id" => false,
                "type" => UserType::Student->value,
                "course" => config('course.chapeco')[substr($enrollment_id, 3, 4)]
            ];
        }
    }

    private function isEnrollmentIdStudentType($enrollment_id)
    {
        $allCourses = [];
        array_push($allCourses, array_keys(config('course.laranjeiras')));
        array_push($allCourses, array_keys(config('course.realeza')));
        array_push($allCourses, array_keys(config('course.cerro_lago')));
        array_push($allCourses, array_keys(config('course.erechim')));
        array_push($allCourses, array_keys(config('course.passo_fundo')));

        return in_array(substr($enrollment_id, 3, 4), $allCourses);
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
