<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUserEnrollmentIdStatusJob;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserEnrollmentIdStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-user-enrollment-id-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User Enrollment Id UserOperationStatus';

    private string $className;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->className = UpdateUserEnrollmentIdStatusCommand::class;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(UserService $userService)
    {
        Log::info("Starting command {$this->className}");

        $users = $userService->getAllUsersWithIdUFFS();

        foreach ($users as $user){
            UpdateUserEnrollmentIdStatusJob::dispatch($user->uid);
        }

        Log::info("Finished command {$this->className}");
    }
}
