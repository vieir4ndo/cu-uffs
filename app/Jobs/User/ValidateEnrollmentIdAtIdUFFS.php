<?php

namespace App\Jobs\User;

use App\Enums\UserCreationStatus;
use App\Repositories\UserCreationRepository;
use App\Services\IdUffsService;
use App\Services\UserCreationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ValidateEnrollmentIdAtIdUFFS implements ShouldQueue
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
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::ValidatingEnrollmentId);

            $userDb = $this->userCreationService->getByUid($this->uid);

            $user_data_from_enrollment = $this->idUffsService->isActive($userDb->payload["enrollment_id"]);

            if (empty($user_data_from_enrollment)){
                throw new Exception("Enrollment_id is not active at IdUFFS.");
            }

            $userDb->payload["type"] = $user_data_from_enrollment["type"];
            $userDb->payload["course"] = $user_data_from_enrollment["course"];

            $this->userCreationService->updatePayloadByUid($this->uid, $userDb);

            //ValidateAndSaveProfilePhoto::dispatch($this->uid);
        }
        catch (\Exception $e){
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at ValidatingEnrollmentIdAtIdUFFS with message: {$e->getMessage()}.");
        }
    }
}
