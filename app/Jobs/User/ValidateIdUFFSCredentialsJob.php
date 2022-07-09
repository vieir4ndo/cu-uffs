<?php

namespace App\Jobs\User;

use App\Enums\UserCreationStatus;
use App\Services\IdUffsService;
use App\Services\UserCreationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

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
        $this->className = get_class((object)This::class);
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserCreationService $userCreationService, IdUffsService $idUffsService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::ValidatingIdUFFSCredentials);

            $userDb = $userCreationService->getByUid($this->uid);

            $user_data_from_auth = $idUffsService->authWithIdUFFS($this->uid, $userDb->payload["password"]);

            if (!$user_data_from_auth) {
                throw new Exception("The IdUFFS password does not match the one informed.");
            }

            $user = [
                "uid" => $this->uid,
                "password" => $user_data_from_auth["password"],
                "profile_photo" => $userDb->payload["profile_photo"],
                "enrollment_id" => $userDb->payload["enrollment_id"],
                "birth_date" => $userDb->payload["birth_date"],
                "name" => $user_data_from_auth["name"],
                "email"=>$user_data_from_auth["email"]
            ];

            $userCreationService->updatePayloadByUid($this->uid, $user);

            ValidateEnrollmentIdAtIdUFFSJob::dispatch($this->uid);
            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
