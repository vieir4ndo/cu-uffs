<?php

namespace App\Jobs\UserWithIdUFFS;

use App\Enums\UserCreationStatus;
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
    public function handle(UserPayloadService $userCreationService, IdUffsService $idUffsService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::ValidatingEnrollmentId);

            $userDb = $userCreationService->getByUid($this->uid);

            $user_data_from_enrollment = $idUffsService->isActive($userDb->payload["enrollment_id"], $userDb->payload["name"]);

            if (empty($user_data_from_enrollment)){
                throw new Exception("Enrollment_id is not active or does not belong to the IdUFFS informed.");
            }

            $user = [
                "uid" => $this->uid,
                "password" => $userDb->payload["password"],
                "profile_photo" => $userDb->payload["profile_photo"],
                "enrollment_id" => $userDb->payload["enrollment_id"],
                "birth_date" => $userDb->payload["birth_date"],
                "name" => $userDb->payload["name"],
                "email"=>$userDb->payload["email"],
                "type" => $user_data_from_enrollment["type"],
                "course"=>  $user_data_from_enrollment["course"],
                "status_enrollment_id" => $user_data_from_enrollment["status_enrollment_id"]
            ];

            $userCreationService->updatePayloadByUid($this->uid, $user);

            ValidateAndSaveProfilePhotoJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        }
        catch (\Exception $e){
            Log::error("Error on job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
