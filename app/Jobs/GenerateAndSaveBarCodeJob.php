<?php

namespace App\Jobs;

use App\Enums\UserOperationStatus;
use App\Helpers\OperationHelper;
use App\Interfaces\Services\IBarcodeService;
use App\Interfaces\Services\IUserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        $this->className = GenerateAndSaveBarCodeJob::class;
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(IUserPayloadService $userPayloadService, IBarcodeService $barcodeService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::GeneratingBarCode);

            $userDb = $userPayloadService->getByUid($this->uid);

            $user = $userDb->payload;

            if (OperationHelper::IsUpdateUserOperation($userDb->operation) && !array_key_exists("enrollment_id", $user)) {
                Log::info("Update does not require bar code generation");
            } else {
                $barcodePath = $barcodeService->generateBase64($user["enrollment_id"]);

                $user["bar_code"] = $barcodePath;

                $userPayloadService->updatePayloadByUid($this->uid, $user);
            }

            FinishCreateOrUpdateUserJob::dispatch($this->uid);

            Log::info("Finished job {$this->className}");
        } catch (\Exception | Throwable $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
