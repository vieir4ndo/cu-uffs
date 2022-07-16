<?php

namespace App\Repositories;

use App\Enums\UserOperationStatus;
use App\Models\UserPayload;

class UserPayloadRepository
{
    public function create($data)
    {
        return UserPayload::create($data);
    }

    public function updateOrCreate($data)
    {
        return UserPayload::updateOrCreate(
            ["uid" => $data["uid"]],
            [
                "status" => $data["status"],
                "payload" => $data["payload"],
                "message" => $data["message"],
                "operation" => $data["operation"]
            ]
        );
    }

    public function getByUid(string $uid)
    {
        return UserPayload::where("uid", $uid)->first();
    }

    public function getStatusByUid(string $uid)
    {
        return UserPayload::where("uid", $uid)->select('uid', 'status', 'message', 'operation', 'updated_at')->first();
    }

    public function update($uid, $data)
    {
        return UserPayload::where('uid', $uid)->update($data);
    }

    public function delete($uid){
        return UserPayload::where('uid', $uid)->delete();
    }
}
