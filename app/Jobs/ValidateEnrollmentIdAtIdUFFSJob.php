<?php

namespace App\Jobs;

use App\Enums\UserOperationStatus;
use App\Helpers\OperationHelper;
use App\Interfaces\Services\IIdUffsService;
use App\Interfaces\Services\IUserPayloadService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        $this->className = ValidateEnrollmentIdAtIdUFFSJob::class;
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IUserPayloadService $userPayloadService, IIdUffsService $idUffsService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::ValidatingEnrollmentId);

            $userDb = $userPayloadService->getByUid($this->uid);

            $user = $userDb->payload;

            if (OperationHelper::IsUpdateUserOperation($userDb->operation) && !array_key_exists("enrollment_id", $user)) {
                Log::info("Update does not require enrollment id validation");
            } else {

                $user_data_from_enrollment = $idUffsService->isActive($user["enrollment_id"], $user["name"]);

                if (empty($user_data_from_enrollment)) {
                    throw new Exception("Enrollment_id is not active or does not belong to the IdUFFS informed.");
                }

                $user["type"] = array_key_exists('type', $user) ? $user["type"] : $user_data_from_enrollment["type"];
                $user["course"] = $user_data_from_enrollment["course"];
                $user["status_enrollment_id"] = $user_data_from_enrollment["status_enrollment_id"];

                $userPayloadService->updatePayloadByUid($this->uid, $user);
            }
            ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
