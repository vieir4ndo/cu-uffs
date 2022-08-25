<?php

namespace App\Services;

use App\Interfaces\Services\IAiPassportPhotoService;
use Exception;
use GuzzleHttp\RequestOptions;

class AiPassportPhotoService implements IAiPassportPhotoService
{
    private $client;
    private string $apiUrl = "https://api.aipassportphoto.com/api/get-signed-url?specCode=brazil-idphoto";

    public function __construct()
    {
        $this->client = new HttpClient();
    }

    /**
     * @throws Exception
     */
    public function validatePhoto($base64Photo): string
    {
        try {

            if (env("SHOULD_VALIDATE_PROFILE_PHOTO", false)) {
                $res = $this->client->get($this->apiUrl);

                if ($res->getStatusCode() != 200) {
                }

                $signedUrl = json_decode($res->getBody())->signedUrl;

                $base64Photo = explode(',', $base64Photo)[1];

                $res = $this->client->post(
                    $signedUrl,
                    json_encode([
                        "imageBase64" => "{$base64Photo}",
                        "specCode" => "brazil-idphoto"
                    ])
                );

                $jsonResponse = json_decode($res->getBody());

                if ($jsonResponse->message != "GOOD") {
                    throw new Exception("A foto de perfil informada não segue as normativas da UFFS.");
                }

                $res = $this->client->request('GET', $jsonResponse->photoUrl);

                return "data:image/png;base64," . base64_encode($res->getBody());
            } else {
                return $base64Photo;
            }
        } catch (Exception $e) {
            throw new Exception("Não foi possível validar sua foto de perfil nesse momento, por favor tente novamente.");
        }
    }
}
