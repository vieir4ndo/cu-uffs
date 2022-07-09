<?php

namespace App\Jobs\User;

use App\Enums\UserCreationStatus;
use App\Helpers\StorageHelper;
use App\Services\AiPassportPhotoService;
use App\Services\UserCreationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class ValidateAndSaveProfilePhotoJob implements ShouldQueue
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
    public function handle(UserCreationService $userCreationService, AiPassportPhotoService $aiPassportPhotoService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::ValidatingProfilePhoto);

            $userDb = $userCreationService->getByUid($this->uid);

            $photoValidated = $aiPassportPhotoService->validatePhoto($userDb->payload["profile_photo"]);
            $photoValidatedPath = StorageHelper::saveProfilePhoto($this->uid, $photoValidated);

            $user = [
                "uid" => $this->uid,
                "password" => $userDb->payload["password"],
                "profile_photo" => $photoValidatedPath,
                "enrollment_id" => $userDb->payload["enrollment_id"],
                "birth_date" => $userDb->payload["birth_date"],
                "name" => $userDb->payload["name"],
                "email" => $userDb->payload["email"],
                "type" => $userDb->payload["type"],
                "course" => $userDb->payload["course"],
                "status_enrollment_id" => $userDb->payload["status_enrollment_id"],
            ];

            $userCreationService->updatePayloadByUid($this->uid, $user);

            GenerateAndSaveBarCodeJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
