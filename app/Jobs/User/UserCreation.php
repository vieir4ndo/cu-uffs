<?php

namespace App\Jobs\User;

use App\Enums\UserCreationStatus;
use App\Repositories\UserCreationRepository;
use App\Services\UserCreationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userCreationService;
    private $uid;
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Starting);

            ValidateCredentialsAtIdUFFS::dispatch($this->uid);
        }
        catch (\Exception $e){
            $this->userCreationService->updateStatusAndMessageByUid($this->uid, UserCreationStatus::Failed, "Failed at Starting with message: {$e->getMessage()}.");
        }
    }
}
