<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class AiPassportPhotoService
{
    private $client;
    private string $apiUrl = "https://api.aipassportphoto.com/api/get-signed-url?specCode=brazil-idphoto";

    public function __construct()
    {
        $this->client = new Client();
    }

    public function validatePhoto($base64Photo)
    {
        $res = $this->client->get($this->apiUrl);

        if ($res->getStatusCode() != 200) {
            throw new \Exception("Could not validate profile photo at this moment, please try again later.");
        }

        $signedUrl = json_decode($res->getBody())->signedUrl;

        //dump($signedUrl);

        $res = $this->client->post($signedUrl,
            [
                RequestOptions::JSON => [
                    "imageBase64" => "data:image/jpeg;base64, {$base64Photo}",
                    "specCode" => "brazil-idphoto"
                ]
            ]
        );

        $jsonResponse = json_decode($res->getBody());

        if ($jsonResponse->message != "GOOD") {
            throw new \Exception("Profile photo does not follow the guidelines: {$jsonResponse->message}.");
        }

        $res = $this->client->request('GET', $jsonResponse->photoUrl);

        return base64_encode($res->getBody());
    }

}
