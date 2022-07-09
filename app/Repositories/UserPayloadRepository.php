<?php

namespace App\Repositories;

use App\Models\UserPayload;

class UserPayloadRepository
{
    public function create($data)
    {
        return UserPayload::create($data);
    }

    public function getByUid(string $uid)
    {
        return UserPayload::where("uid", $uid)->first();
    }

    public function getStatusAndMessageByUid(string $uid){
        return UserPayload::where("uid", $uid)->select('uid', 'status', 'message')->first();
    }
    public function update($uid, $data)
    {
        return UserPayload::where('uid', $uid)->update($data);
    }
}
