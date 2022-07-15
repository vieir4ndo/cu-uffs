<?php

namespace App\Jobs;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Services\UserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class StartUserCreationJob implements ShouldQueue
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
        $this->className = get_class((object)This::class);
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserPayloadService $userPayloadService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Starting);

            $userDb = $userPayloadService->getByUid($this->uid);

            switch ($userDb->operation) {
                case Operation::UserCreationWithIdUFFS->value:
                    ValidateIdUFFSCredentialsJob::dispatch($this->uid);
                    break;
                case Operation::UserUpdateWithIdUFFS->value:
                    ValidateEnrollmentIdAtIdUFFSJob::dispatch($this->uid);
                    break;
                case Operation::UserCreationWithoutIdUFFS->value:
                    $user = $userDb->payload;

                    $user["enrollment_id"] = bin2hex(random_bytes(5));
                    $user["status_enrollment_id"] = true;

                    $userPayloadService->updatePayloadByUid($this->uid, $user);

                    ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
                    break;
                case Operation::UserUpdateWithoutIdUFFS->value:
                    ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
                    break;
            }

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");
            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
