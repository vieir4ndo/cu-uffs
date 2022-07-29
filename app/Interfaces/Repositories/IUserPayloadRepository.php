<?php

namespace App\Interfaces\Repositories;

interface IUserPayloadRepository
{
    function updateOrCreate($data);
    function getByUid(string $uid);
    function getStatusByUid(string $uid);
    function update($uid, $data);
    function delete($uid);
}
