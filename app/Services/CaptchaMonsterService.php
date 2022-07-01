<?php

namespace App\Services;

use App\Interfaces\Services\ICaptchaMonsterService;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CaptchaMonsterService implements ICaptchaMonsterService
{
    private $apiUrl = "https://api.capmonster.cloud";
    private $apiKey;
    private $client;

    public function __construct()
    {
        $this->apiKey = env("CAPTCHA_MONSTER_KEY");
        $this->client = new Client();
    }

    public function breakRecaptcha(string $websiteUrl, string $websiteKey): string
    {
        $taskId = $this->createTask($websiteUrl, $websiteKey);

        $captcha = $this->getTaskById($taskId);

        return $captcha->gRecaptchaResponse;
    }

    private function createTask(string $websiteUrl, string $websiteKey)
    {
        $res = $this->client->post("{$this->apiUrl}/createTask",
            [
                RequestOptions::JSON => [
                    "clientKey" => $this->apiKey,
                    "task" => [
                        "type" => "NoCaptchaTaskProxyless",
                        "websiteURL" => $websiteUrl,
                        "websiteKey" => $websiteKey
                    ]
                ]
            ]
        );

        $jsonResponse = json_decode($res->getBody());

        if ($jsonResponse->errorId != 0) {
            throw new \Exception("There was a problem breaking your captcha: {$jsonResponse->errorId}");
        }

        return $jsonResponse->taskId;
    }

    private function getTaskById(string $taskId)
    {

        do {
            $res = $this->client->post("{$this->apiUrl}/getTaskResult",
                [
                    RequestOptions::JSON => [
                        "clientKey" => $this->apiKey,
                        "taskId" => $taskId
                    ]
                ]
            );

            if ($res->getStatusCode()!= 200){
                throw new \Exception("Cannot break captcha at this moment.");
            }

            $jsonResponse = json_decode($res->getBody());

        } while($jsonResponse->status != 'ready');

        if ($jsonResponse->errorId){
            throw new \Exception("There was a problem breaking your captcha: {$jsonResponse->errorId}");
        }

        return $jsonResponse->solution;
    }


}
