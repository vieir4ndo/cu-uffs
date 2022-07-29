<?php

namespace App\Services;

use App\Interfaces\Repositories\IEntryRepository;
use App\Interfaces\Services\IEntryService;
use App\Interfaces\Services\IUserService;

class EntryService implements IEntryService
{
    private IEntryRepository $repository;
    private IUserService $userService;

    public function __construct(
        IEntryRepository         $entryRepository,
        IUserService $userService
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

    public function getEntriesByUsername(string $uid){
        $user = $this->userService->getUserByUsername($uid);

        $result = $this->repository->getEntriesById($user->id);

        return ["entries" => $result];
    }

}
