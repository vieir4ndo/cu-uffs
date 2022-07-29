<?php

namespace App\Jobs;

use App\Services\IdUffsService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateUserEnrollmentIdStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $uid;
    protected string $className;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->className = UpdateUserEnrollmentIdStatusJob::class;
        $this->uid = $uid;
    }


    /**
     * Execute the job.
     *
     * @param UserService $userService
     * @param IdUffsService $idUffsService
     * @return void
     * @throws \Exception
     */
    public function handle(UserService $userService, IdUffsService $idUffsService)
    {
        Log::info("Starting job {$this->className}");

        $user = $userService->getUserByUsername($this->uid, false);

        $user_data_from_enrollment = $idUffsService->isActive($user->enrollment_id, $user->name);

        $data = [
            "status_enrollment_id" => !empty($user_data_from_enrollment)
        ];

        $userService->updateUser($user, $data);

        Log::info("Finished job {$this->className}");
    }
}

