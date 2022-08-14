<?php

namespace App\Services;

use App\Enums\TicketOrEntryType;
use App\Enums\UserType;
use App\Interfaces\Repositories\IEntryRepository;
use App\Interfaces\Services\IEntryService;
use App\Interfaces\Services\IUserService;

class EntryService implements IEntryService
{
    private IEntryRepository $repository;
    private IUserService $userService;
    private $visitorEnrollmentId;
    private $thirdPartyEmployeeEnrollmentId;

    public function __construct(
        IEntryRepository         $entryRepository,
        IUserService $userService
    )
    {
        $this->repository = $entryRepository;
        $this->userService = $userService;
        $this->visitorEnrollmentId = config("ticket.visitor_enrollment_id");
        $this->thirdPartyEmployeeEnrollmentId = config("ticket.third_party_employee_enrollment_id");
    }

    public function insertEntry($enrollment_id, $data){
        if ($enrollment_id != $this->visitorEnrollmentId and $enrollment_id != $this->thirdPartyEmployeeEnrollmentId){

            $user = $this->userService->getUserByEnrollmentId($enrollment_id, false);

            if ($user->active == false){
                throw new \Exception("User is not active.");
            }

            if ($user->status_enrollment_id == false){
                throw new \Exception("User's enrollment id is not active.");
            }

            if ($user->ticket_amount == 0){
                throw new \Exception("User has no tickets available.");
            }

            $data["user_id"] = $user->id;
            $data["type"] = ($user->type == UserType::Employee->value) ? TicketOrEntryType::Employee->value : TicketOrEntryType::Student->value;

            $this->userService->updateTicketAmount($user->uid, -1);
        }
        else {
            $data["type"] = ($enrollment_id == $this->visitorEnrollmentId) ? TicketOrEntryType::Visitor->value : TicketOrEntryType::ThirdPartyEmployee->value;
        }

        $this->repository->insert($data);
    }

    public function getEntriesByUsername(string $uid){
        $user = $this->userService->getUserByUsername($uid);

        $result = $this->repository->getEntriesById($user->id);

        return  $result;
    }

}
