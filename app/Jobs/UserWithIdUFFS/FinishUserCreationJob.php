<?php

namespace App\Jobs\UserWithIdUFFS;

use App\Enums\UserCreationStatus;
use App\Repositories\UserRepository;
use App\Services\UserPayloadService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class FinishUserCreationJob implements ShouldQueue
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
    public function handle(UserPayloadService $userCreationService, UserRepository $repository)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userDb = $userCreationService->getByUid($this->uid);

            $user = [
                "uid" => $this->uid,
                "password" => Hash::make($userDb->payload["password"]),
                "profile_photo" => $userDb->payload["profile_photo"],
                "enrollment_id" => $userDb->payload["enrollment_id"],
                "birth_date" => Carbon::parse($userDb->payload["birth_date"]),
                "name" => $userDb->payload["name"],
                "email" => $userDb->payload["email"],
                "type" => $userDb->payload["type"],
                "course" => $userDb->payload["course"],
                "bar_code" => $userDb->payload["bar_code"],
                "active" => true,
                "status_enrollment_id" => $userDb->payload["status_enrollment_id"],
            ];

            $repository->createUser($user);

            $userCreationService->deletePayloadByUid($this->uid);
            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Suceed);

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
