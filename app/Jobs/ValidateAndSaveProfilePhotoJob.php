<?php

namespace App\Jobs;

use App\Enums\UserOperationStatus;
use App\Helpers\OperationHelper;
use App\Interfaces\Services\IAiPassportPhotoService;
use App\Interfaces\Services\IUserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $this->className = ValidateAndSaveProfilePhotoJob::class;
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IUserPayloadService $userPayloadService, IAiPassportPhotoService $aiPassportPhotoService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::ValidatingProfilePhoto);

            $userDb = $userPayloadService->getByUid($this->uid);

            $user = $userDb->payload;

            if (OperationHelper::IsUpdateUserOperation($userDb->operation) && !array_key_exists("profile_photo", $user)) {
                Log::info("Update does not require profile photo validation");
            } else {

                $photoValidated = $aiPassportPhotoService->validatePhoto($user["profile_photo"]);

                $user["profile_photo"] = $photoValidated;

                $userPayloadService->updatePayloadByUid($this->uid, $user);
            }

            GenerateAndSaveBarCodeJob::dispatch($this->uid);

            Log::info("Finished job {$this->className}");
        } catch (\Exception | Throwable $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
