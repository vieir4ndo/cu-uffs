<?php

namespace App\Jobs;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Services\UserPayloadService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class StartCreateOrUpdateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $uid;
    protected string $className;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->className = StartCreateOrUpdateUserJob::class;
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

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Starting);

            $userPayload = $userPayloadService->getByUid($this->uid);
            $user = $userPayload->payload;

            switch ($userPayload->operation) {
                case Operation::UserCreationWithIdUFFS->value:
                    ValidateIdUFFSCredentialsJob::dispatch($this->uid);
                    break;
                case Operation::UserCreationWithoutIdUFFS->value:
                    $user["enrollment_id"] = bin2hex(random_bytes(5));
                    $user["status_enrollment_id"] = true;
                    $userPayloadService->updatePayloadByUid($this->uid, $user);
                    ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
                    break;
                case Operation::UserUpdateWithIdUFFS->value:
                    $userDb = $userService->getUserByUsername($this->uid);
                    $user["name"] = $userDb->name;
                    $userPayloadService->updatePayloadByUid($this->uid, $user);
                    ValidateEnrollmentIdAtIdUFFSJob::dispatch($this->uid);
                    break;
                case Operation::UserUpdateWithoutIdUFFS->value:
                    ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
                    break;
                default:
                    throw new \Exception('Invalid operation.');
            }

            Log::info("Finished job {$this->className}");
        } catch (\Exception | Throwable $e) {
            Log::error("Error on job {$this->className}");
            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
