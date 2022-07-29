<?php

namespace App\Services;

use App\Interfaces\Repositories\ITicketRepository;
use App\Interfaces\Services\ITicketService;
use App\Interfaces\Services\IUserService;

class TicketService implements ITicketService
{
    private ITicketRepository $repository;
    private IUserService $userService;

    public function __construct(ITicketRepository $ticketRepository, IUserService $userService )
    {
        $this->repository = $ticketRepository;
        $this->userService = $userService;
    }

    public function insertTicket($enrollment_id, $data){
        $user = $this->userService->getUserByEnrollmentId($enrollment_id, false);

        if ($user->active == false){
            throw new \Exception("User is not active.");
        }

        if ($user->status_enrollment_id == false){
            throw new \Exception("User's enrollment id is not active.");
        }

        $data["user_id"] = $user->id;

        $this->userService->updateTicketAmount($user->uid, $data["amount"]);

        $this->repository->insert($data);
    }

    public function insertTicketForVisitors($data){
        $this->repository->insert($data);
    }

    public function getTicketsByUsername($uid){
        $user = $this->userService->getUserByUsername($uid);

        $result = $this->repository->getTicketsById($user->id);

        return ["tickets" => $result];
    }

    public function getTicketBalance($uid){
        $user = $this->userService->getUserByUsername($uid, false);

        return ["ticket_amount"=> $user->ticket_amount];
    }
}
