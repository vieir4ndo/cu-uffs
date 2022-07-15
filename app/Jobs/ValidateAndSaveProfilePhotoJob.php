<?php

namespace App\Jobs;

use App\Enums\Operation;
use App\Enums\UserOperationStatus;
use App\Helpers\StorageHelper;
use App\Services\AiPassportPhotoService;
use App\Services\UserPayloadService;
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
    public function handle(UserPayloadService $userPayloadService, AiPassportPhotoService $aiPassportPhotoService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::ValidatingProfilePhoto);

            $userDb = $userPayloadService->getByUid($this->uid);

            $user = $userDb->payload;

            if (in_array($userDb->operation, [Operation::UserCreationWithoutIdUFFS->value, Operation::UserCreationWithIdUFFS->value]) && !$user["profile_photo"]){

                $photoValidated = $aiPassportPhotoService->validatePhoto($user["profile_photo"]);
                $photoValidatedPath = StorageHelper::saveProfilePhoto($this->uid, $photoValidated);

                $user["profile_photo"] = $photoValidatedPath;

                $userPayloadService->updatePayloadByUid($this->uid, $user);
            }

            GenerateAndSaveBarCodeJob::dispatch($this->uid);

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
