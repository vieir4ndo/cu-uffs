<?php

namespace App\Interfaces\Services;

interface IUserPayloadService
{
    function getByUid(string $uid, bool $shouldReturnPayloadAsArray = true);
    function getStatusAndMessageByUid(string $uid);
    function create($user, $operation) : bool;
    function updatePayloadByUid(string $uid, $user);
    function deletePayloadByUid(string $uid);
    function updateStatusAndMessageByUid(string $uid, $status, $message = null);
    function deleteByUid(string $uid);
}
