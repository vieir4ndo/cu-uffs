<?php

namespace App\Jobs;

use App\Enums\UserOperationStatus;
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
    public function handle(UserPayloadService $userPayloadService, UserRepository $repository)
    {
        try {
            Log::info("Starting job {$this->className}");

            $user = $userPayloadService->getByUid($this->uid)->payload;

            $user["password"] = Hash::make($user["password"]);
            $user["birth_date"] = Carbon::parse($user["birth_date"]);
            $user["active"] = true;

            $repository->createUser($user);

            $userPayloadService->deletePayloadByUid($this->uid);
            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Suceed);

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");

            $userPayloadService->updateStatusAndMessageByUid($this->uid, UserOperationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
