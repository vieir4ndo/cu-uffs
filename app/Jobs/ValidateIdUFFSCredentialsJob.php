<?php

namespace App\Jobs;

use App\Enums\UserOperationStatus;
use App\Interfaces\Services\IIdUffsService;
use App\Interfaces\Services\IUserPayloadService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class ValidateIdUFFSCredentialsJob implements ShouldQueue
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
        $this->className = ValidateIdUFFSCredentialsJob::class;
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

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::ValidatingIdUFFSCredentials);

            $user = $userPayloadService->getByUid($this->uid)->payload;

            $user_data_from_auth = $idUffsService->authWithIdUFFS($this->uid, $user["password"]);

            if (!$user_data_from_auth) {
                throw new Exception("The IdUFFS password does not match the one informed.");
            }

            $user["password"] = $user_data_from_auth["password"];
            $user["name"] = $user_data_from_auth["name"];
            $user["email"] = $user_data_from_auth["email"];

            $userPayloadService->updatePayloadByUid($this->uid, $user);

            ValidateEnrollmentIdAtIdUFFSJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        } catch (\Exception | Throwable $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
