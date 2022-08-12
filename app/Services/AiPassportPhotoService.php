<?php

namespace App\Services;

use App\Interfaces\Services\IAiPassportPhotoService;
use GuzzleHttp\RequestOptions;

class AiPassportPhotoService implements IAiPassportPhotoService
{
    private $client;
    private string $apiUrl = "https://api.aipassportphoto.com/api/get-signed-url?specCode=brazil-idphoto";

    public function __construct()
    {
        $this->client = new HttpClient();
    }

    public function validatePhoto($base64Photo): string
    {
        if (env("SHOULD_VALIDATE_PROFILE_PHOTO", false)) {
            $res = $this->client->get($this->apiUrl);

            if ($res->getStatusCode() != 200) {
                throw new \Exception("Could not validate profile photo at this moment, please try again later.");
            }

            $signedUrl = json_decode($res->getBody())->signedUrl;

            $res = $this->client->withHeaders([
                "authority" => "idphotoapi.idphotoapp.com",
                "method" => "POST",
                "path" => str_replace('https://idphotoapi.idphotoapp.com', '', $signedUrl),
                "scheme" => "https",
                "accept" => "application/json, text/plain, */*",
                "accept-encoding" => "gzip, deflate, br",
                "accept-language" => "en-US,en;q=0.9,pt;q=0.8",
                "content-length" => "355670",
                "content-type" => "application/json;charset=UTF-8",
                "origin" => "https://aipassportphoto.com",
                "referer" => "https://aipassportphoto.com/",
                "sec-ch-ua" => '"Chromium";v="104", " Not A;Brand";v="99", "Google Chrome";v="104"',
                "sec-ch-ua-mobile" => "?0",
                "sec-ch-ua-platform" => "Windows",
                "sec-fetch-dest" => "empty",
                "sec-fetch-mode" => "cors",
                "sec-fetch-site" => "cross-site",
                "user-agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36",
            ])->post(
                $signedUrl,
                json_encode([
                    "imageBase64" => "{$base64Photo}",
                    "specCode" => "brazil-idphoto"
                ])
            );

            $jsonResponse = json_decode($res->getBody());

            if ($jsonResponse->message != "GOOD") {
                throw new \Exception("Profile photo does not follow the guidelines: {$jsonResponse->message}.");
            }

            $res = $this->client->request('GET', $jsonResponse->photoUrl);

            return "data:image/png;base64," . base64_encode($res->getBody());
        } else {
            return $base64Photo;
        }
    }
}
