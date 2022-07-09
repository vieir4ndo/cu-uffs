<?php

namespace App\Jobs\UserWithIdUFFS;

use App\Enums\UserCreationStatus;
use App\Services\UserPayloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;

class StartUserCreationJob implements ShouldQueue
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
        $this->className = get_class((object)This::class);
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserPayloadService $userCreationService)
    {
        try {
            Log::info("Starting job {$this->className}");

            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Starting);

            ValidateIdUFFSCredentialsJob::dispatch($this->uid);

            Log::info("Finished job {$this->className}");
        } catch (\Exception $e) {
            Log::error("Error on job {$this->className}");
            $userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at {$this->className} with message: {$e->getMessage()}");
        }
    }
}
