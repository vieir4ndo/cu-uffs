<?php

namespace App\Jobs\UserUpdate;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Jobs\UserCreation\ValidateAndSaveProfilePhotoJob;
use App\Jobs\UserCreation\ValidateEnrollmentIdAtIdUFFSJob;
use App\Jobs\UserCreation\ValidateIdUFFSCredentialsJob;
use App\Services\UserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class StartUserUpdateJob implements ShouldQueue
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

            if ($userDb->operation == Operation::UserUpdateWithIdUFFS->value) {
                ValidateEnrollmentIdAtIdUFFSJob::dispatch($this->uid);
            } else {
                ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
            }

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");
            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
