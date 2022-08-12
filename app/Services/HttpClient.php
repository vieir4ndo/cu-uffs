<?php

namespace App\Services;

use App\Interfaces\Services\IHttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;

class HttpClient implements IHttpClient
{
    private $client;

    private $headers = [];

    private $timeout = 500;

    private $allowRedirects = true;

    private $doNotThrowExceptionStatusCodes = [400, 417, 422, 409];

    public function __construct()
    {
        $this->client = new Client([
            RequestOptions::VERIFY => false,
            RequestOptions::COOKIES => true,
            RequestOptions::DECODE_CONTENT => true
        ]);
    }

    /**
     * @throws \Exception
     */
    public function request($method, $uri, $data = [], $referer = null)
    {
        if ($referer) {
            $this->withReferer($referer);
        }

        try {
            $response = $this->client->request($method, $uri, $this->getOptions($data, $uri));
        } catch (ClientException | RequestException $e) {
            $response = $e->getResponse();

            if (!$response || !in_array($response->getStatusCode(), $this->doNotThrowExceptionStatusCodes)) {
                throw $e;
            }
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

    public function withHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function withReferer($referer)
    {
        $this->headers['Referer'] = $referer;

        return $this;
    }

    private function getOptions($data, &$uri)
    {
        $options = [];

        if (is_string($data)) {
            $options[RequestOptions::BODY] = $data;
        } else {
            $options[RequestOptions::FORM_PARAMS] = $data;
        }

        $options[RequestOptions::ON_STATS] = function (TransferStats $stats) use (&$uri) {
            $effectiveUri = $stats->getEffectiveUri();

            $uriParts = [
                'scheme' => $effectiveUri->getScheme(),
                'host' => $effectiveUri->getHost(),
                'port' => $effectiveUri->getPort() ? ':' . $effectiveUri->getPort() : '',
                'path' => $effectiveUri->getPath(),
                'query' => $effectiveUri->getQuery() ? '?' . $effectiveUri->getQuery() : '',
            ];

            $uri = $uriParts['scheme'] . '://' . $uriParts['host'] . $uriParts['port'] . $uriParts['path'] . $uriParts['query'];
        };


        if ($this->timeout) {
            $options[RequestOptions::TIMEOUT] = $this->timeout;
        }

        if ($this->headers) {
            $options[RequestOptions::HEADERS] = $this->headers;
        }

        if (!isset($this->headers['User-Agent'])) {
            $options[RequestOptions::HEADERS]['User-Agent'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0';
        }

        $options[RequestOptions::ALLOW_REDIRECTS] = ($this->allowRedirects) ? ['referer' => true] : false;

        $options[RequestOptions::VERIFY] = false;

        $options[RequestOptions::DEBUG] = false;

        return $options;
    }

    public function get($uri, $referer = null)
    {
        return $this->request('GET', $uri, [], $referer);
    }

    public function post($uri, $data = [], $referer = null)
    {
        return $this->request('POST', $uri, $data, $referer);
    }
}
