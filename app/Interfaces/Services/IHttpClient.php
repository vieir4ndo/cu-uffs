<?php

namespace App\Interfaces\Services;

interface IHttpClient
{
    function request($method, $uri, $data = [], $referer = null);
    function withReferer($referer);
    function get($uri, $referer = null);
    function post($uri, $data = [], $referer = null);
}
