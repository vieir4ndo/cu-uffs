<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IUserPayloadRepository;
use App\Models\UserPayload;

class UserPayloadRepository implements IUserPayloadRepository
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
        return UserPayload::where("uid", $uid)
            ->join('operations', 'user_payloads.operation', '=', 'operations.id')
            ->join('user_operation_statuses', 'user_payloads.status', '=', 'user_operation_statuses.id')
            ->select('user_payloads.uid', 'user_operation_statuses.description as status', 'user_payloads.message', 'operations.description as operation', 'user_payloads.updated_at')
            ->first();
    }

    public function update($uid, $data)
    {
        return UserPayload::where('uid', $uid)->update($data);
    }

    public function delete($uid){
        return UserPayload::where('uid', $uid)->delete();
    }
}
