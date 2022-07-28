<?php

namespace App\Services;


use App\Repositories\TicketRepository;

class TicketService
{
    private TicketRepository $repository;
    private UserService $userService;

    public function __construct(
        TicketRepository         $ticketRepository,
        UserService $userService
    )
    {
        $this->repository = $ticketRepository;
        $this->userService = $userService;
    }

    public function insertTicket($enrollment_id, $data){
        $user = $this->userService->getUserByEnrollmentId($enrollment_id, false);

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
