<?php

namespace App\Jobs;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Services\UserPayloadService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FinishCreateOrUpdateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private string $uid;
    private string $className;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->className = FinishCreateOrUpdateUserJob::class;
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserPayloadService $userPayloadService, UserService $userService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayload = $userPayloadService->getByUid($this->uid);

            $user = $userPayload->payload;

            if (in_array($userPayload->operation, [Operation::UserCreationWithoutIdUFFS->value, Operation::UserCreationWithIdUFFS->value])) {

                $user["password"] = Hash::make($user["password"]);
                $user["birth_date"] = Carbon::parse($user["birth_date"]);
                $user["active"] = true;

            } else {
                $userDb = $userService->getUserByUsername($this->uid, false);

                $user = [
                    "uid" => $this->uid,
                    "profile_photo" => array_key_exists("profile_photo", $user) ? $user["profile_photo"] : $userDb->profile_photo,
                    "enrollment_id" => array_key_exists("enrollment_id", $user) ? $user["enrollment_id"] : $userDb->enrollment_id,
                    "birth_date" => array_key_exists("birth_date", $user) ? Carbon::parse($user["birth_date"]) : $userDb->birth_date,
                    "course" => array_key_exists("course", $user) ? $user["course"] : $userDb->course,
                    "bar_code" => array_key_exists("bar_code", $user) ? $user["bar_code"] : $userDb->bar_code,
                    "status_enrollment_id" => array_key_exists("status_enrollment_id", $user) ? $user["status_enrollment_id"] : $userDb->status_enrollment_id,
                    "type" => array_key_exists("type", $user) ? $user["type"] : $userDb->type,
                    "name" => array_key_exists("name", $user) ? $user["name"] : $userDb->name,
                    "email" => array_key_exists("email", $user) ? $user["email"] : $userDb->email,
                ];
            }

            $userService->createOrUpdate($user);

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Suceed);

            $userPayloadService->deleteByUid($this->uid);

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
