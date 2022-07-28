<?php

namespace App\Services;

use App\Repositories\EntryRepository;

class EntryService
{
    private EntryRepository $repository;
    private UserService $userService;

    public function __construct(
        EntryRepository         $entryRepository,
        UserService $userService
    )
    {
        $this->repository = $entryRepository;
        $this->userService = $userService;
    }

    public function insertEntry($enrollment_id, $data){

        if ($enrollment_id != config("visitor.enrollment_id")){
            $user = $this->userService->getUserByEnrollmentId($enrollment_id, false);

            $data["user_id"] = $user->id;

            $this->userService->updateTicketAmount($user->uid, -1);
        }

        $this->repository->insert($data);
    }

}
