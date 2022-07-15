<?php

namespace App\Jobs;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Services\IdUffsService;
use App\Services\UserPayloadService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class ValidateEnrollmentIdAtIdUFFSJob implements ShouldQueue
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
        $this->className = get_class((object)This::class);
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserPayloadService $userPayloadService, IdUffsService $idUffsService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::ValidatingEnrollmentId);

            $userDb = $userPayloadService->getByUid($this->uid);

            $user = $userDb->payload;

            if (in_array($userDb->operation, [Operation::UserCreationWithoutIdUFFS->value, Operation::UserCreationWithIdUFFS->value]) && $user["enrollment_id"]) {

                $user_data_from_enrollment = $idUffsService->isActive($user["enrollment_id"], $user["name"]);

                if (empty($user_data_from_enrollment)) {
                    throw new Exception("Enrollment_id is not active or does not belong to the IdUFFS informed.");
                }

                $user["type"] = $user_data_from_enrollment["type"];
                $user["course"] = $user_data_from_enrollment["course"];
                $user["status_enrollment_id"] = $user_data_from_enrollment["status_enrollment_id"];

                $userPayloadService->updatePayloadByUid($this->uid, $user);
            }
            ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        }
        catch (\Exception $e){
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
