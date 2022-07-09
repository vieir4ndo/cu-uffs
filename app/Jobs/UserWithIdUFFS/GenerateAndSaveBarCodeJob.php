<?php

namespace App\Jobs\UserWithIdUFFS;

use App\Enums\UserCreationStatus;
use App\Helpers\StorageHelper;
use App\Services\BarcodeService;
use App\Services\UserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class GenerateAndSaveBarCodeJob implements ShouldQueue
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
    public function handle(UserPayloadService $userCreationService, BarcodeService $barcodeService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::GeneratingBarCode);

            $userDb = $userCreationService->getByUid($this->uid);

            $barcodePath = StorageHelper::saveBarCode($this->uid, $barcodeService->generateBase64($userDb->payload["enrollment_id"]));

            $user = [
                "uid" => $this->uid,
                "password" => $userDb->payload["password"],
                "profile_photo" => $userDb->payload["profile_photo"],
                "enrollment_id" => $userDb->payload["enrollment_id"],
                "birth_date" => $userDb->payload["birth_date"],
                "name" => $userDb->payload["name"],
                "email" => $userDb->payload["email"],
                "type" => $userDb->payload["type"],
                "course" => $userDb->payload["course"],
                "bar_code" => $barcodePath,
                "status_enrollment_id" => $userDb->payload["status_enrollment_id"],
            ];

            $userCreationService->updatePayloadByUid($this->uid, $user);

            FinishUserCreationJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
