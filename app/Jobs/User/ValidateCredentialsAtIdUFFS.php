<?php

namespace App\Jobs\User;

use App\Enums\UserCreationStatus;
use App\Helpers\StorageHelper;
use App\Repositories\UserCreationRepository;
use App\Services\IdUffsService;
use App\Services\UserCreationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateCredentialsAtIdUFFS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private UserCreationService $userCreationService;
    private string $uid;
    private IdUffsService $idUffsService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $uid)
    {
        $this->uid = $uid;
        $userCreationRepository = new UserCreationRepository();
        $this->userCreationService = new UserCreationService($userCreationRepository);
        $this->idUffsService = new IdUffsService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::ValidatingIdUFFSCredentials);

            $userDb = $this->userCreationService->getByUid($this->uid);

            $user = $userDb->payload;

            $user_data_from_auth = $this->idUffsService->authWithIdUFFS($this->uid, $userDb->payload["password"]);

            if (!$user_data_from_auth) {
                throw new Exception("The IdUFFS password does not match the one informed.");
            }

            $userDb->payload["name"] = $user_data_from_auth["name"];
            $userDb->payload["email"] = $user_data_from_auth["email"];
            $userDb->payload["password"] = $user_data_from_auth["password"];

            $this->userCreationService->updatePayloadByUid($this->uid, $userDb);

            ValidateEnrollmentIdAtIdUFFS::dispatch($this->uid);
        }
        catch (\Exception $e){
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at ValidatingCredentialsAtIdUFFS with message: {$e->getMessage()}.");
        }
    }
}
