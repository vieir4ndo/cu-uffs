<?php

namespace App\Jobs;

use App\Interfaces\Services\IIdUffsService;
use App\Interfaces\Services\IUserService;
use App\Services\IdUffsService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

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
    public function handle(IUserService $userService, IIdUffsService $idUffsService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $user = $userService->getUserByUsername($this->uid, false);

            $user_data_from_enrollment = $idUffsService->isActive($user->enrollment_id, $user->name);

            $data = [
                "status_enrollment_id" => !empty($user_data_from_enrollment)
            ];

            $userService->updateUser($user, $data);

            Log::info("Finished job {$this->className}");
        }catch (\Exception | Throwable $e) {
            Log::error("Error on job {$this->className}");
        }

    }
}

