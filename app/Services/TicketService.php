<?php

namespace App\Services;

use App\Enums\TicketOrEntryType;
use App\Enums\UserType;
use App\Interfaces\Repositories\ITicketRepository;
use App\Interfaces\Services\ITicketService;
use App\Interfaces\Services\IUserService;
use Carbon\Carbon;

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
            throw new \Exception("Usuário não está ativo.");
        }

        if ($user->status_enrollment_id == false){
            throw new \Exception("Usuário possui matrícula inativa.");
        }

        $data["user_id"] = $user->id;
        $data["type"] = ($user->type == UserType::Employee->value) ? TicketOrEntryType::Employee->value : TicketOrEntryType::Student->value;

        $this->userService->updateTicketAmount($user->uid, $data["amount"]);

        $this->repository->insert($data);
    }

    public function insertTicketForVisitors($data){
        $this->repository->insert($data);
    }

    public function getTicketsByUsername($uid){
        $user = $this->userService->getUserByUsername($uid);

        $result = $this->repository->getTicketsById($user->id);

        return $result;
    }

    public function getTicketBalance($uid){
        $user = $this->userService->getUserByUsername($uid, false);

        return ["ticket_amount"=> $user->ticket_amount];
    }


    public function generateReport($init_date, $final_date)
    {
        $initdate = Carbon::parse($init_date);
        $finaldate = Carbon::parse($final_date);;

        $difference = $finaldate->diffInDays($initdate);

        $entries = $this->calculateTicketsForReport($initdate, $difference);

        $partials = $this->calculatePartials($entries);

        $averages = $this->calculateAverages($partials, $difference);

        return [
            "init_date" => $init_date,
            "final_date" => $final_date,
            "tickets" => $entries,
            "partial_total" => $partials,
            "totals" => [
                "lunch" => $partials["total_lunch"],
                "dinner" => $partials["total_dinner"],
                "day" => $partials["total"],
            ],
            "averages" => $averages,
            "averages_by_day_of_the_week" => $this->calculateAveragesByDayOfTheWeek($entries),
            "emission_date" => now()->format("d-m-Y")
        ];
    }

    private function calculateTicketsForReport($init_date, $differece)
    {
        $all_tickets = [];

        for ($i = 0; $i <= $differece; $i++) {
            $date_to_look_for = $init_date;
            $entries = $this->repository->getTicketsInInterval($date_to_look_for->setTime(0,0,0)->toDateTimeString(), $date_to_look_for->setTime(23, 59, 59)->toDateTimeString());

            $ticket = [
                "day" => $init_date->format("D d-m-Y"),
                "student_lunch" => 0,
                "employee_lunch" => 0,
                "third_party_employee_lunch" => 0,
                "visitor_lunch" => 0,
                "total_lunch" => 0,
                "student_dinner" => 0,
                "employee_dinner" => 0,
                "third_party_employee_dinner" => 0,
                "visitor_dinner" => 0,
                "total_dinner" => 0,
                "student_total" => 0,
                "employee_total" => 0,
                "third_party_employee_total" => 0,
                "visitor_total" => 0,
                "total" => 0
            ];

            foreach ($entries as $value) {
                $date_to_validate = $init_date;
                $date = Carbon::parse($value->date_time);

                if ($date->greaterThanOrEqualTo($date_to_validate->setTime(13, 30, 0)))
                {
                    switch (intval($value->type)) {
                        case TicketOrEntryType::Student->value:
                            $ticket["student_lunch"] = $ticket["student_lunch"] + $value->amount;
                            $ticket["student_total"] = $ticket["student_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::Employee->value:
                            $ticket["employee_lunch"] = $ticket["employee_lunch"] + $value->amount;
                            $ticket["employee_total"] = $ticket["employee_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::ThirdPartyEmployee->value:
                            $ticket["third_party_employee_lunch"] = $ticket["third_party_employee_lunch"] + $value->amount;
                            $ticket["third_party_employee_total"] = $ticket["third_party_employee_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::Visitor->value:
                            $ticket["visitor_lunch"] = $ticket["visitor_lunch"] + $value->amount;
                            $ticket["visitor_total"] = $ticket["visitor_total"] + $value->amount;
                            break;
                    }

                    $ticket["total_lunch"] = $ticket["total_lunch"] + $value->amount;
                }
                else
                {
                    switch (intval($value->type)) {
                        case TicketOrEntryType::Student->value:
                            $ticket["student_dinner"] = $ticket["student_dinner"] + $value->amount;
                            $ticket["student_total"] = $ticket["student_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::Employee->value:
                            $ticket["employee_dinner"] = $ticket["employee_dinner"] + $value->amount;
                            $ticket["employee_total"] = $ticket["employee_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::ThirdPartyEmployee->value:
                            $ticket["third_party_employee_dinner"] = $ticket["third_party_employee_dinner"] + $value->amount;
                            $ticket["third_party_employee_total"] = $ticket["third_party_employee_total"] + $value->amount;
                            break;
                        case TicketOrEntryType::Visitor->value:
                            $ticket["visitor_dinner"] = $ticket["visitor_dinner"] + $value->amount;
                            $ticket["visitor_total"] = $ticket["visitor_total"] + $value->amount;
                            break;
                    }

                    $ticket["total_dinner"] = $ticket["total_dinner"] + $value->amount;
                }

                $ticket["total"] = $ticket["total"] + $value->amount;
            }

            $all_tickets[] = $ticket;

            $init_date->addDays(1);
        }

        return $all_tickets;
    }

    private function calculatePartials($tickets)
    {
        $partials = [
            "student_lunch" => 0,
            "employee_lunch" => 0,
            "third_party_employee_lunch" => 0,
            "visitor_lunch" => 0,
            "total_lunch" => 0,
            "student_dinner" => 0,
            "employee_dinner" => 0,
            "third_party_employee_dinner" => 0,
            "visitor_dinner" => 0,
            "total_dinner" => 0,
            "student_total" => 0,
            "employee_total" => 0,
            "third_party_employee_total" => 0,
            "visitor_total" => 0,
            "total" => 0,
        ];

        foreach ($tickets as $ticket) {
            $partials["student_lunch"] += $ticket["student_lunch"];
            $partials["employee_lunch"] += $ticket["employee_lunch"];
            $partials["third_party_employee_lunch"] += $ticket["third_party_employee_lunch"];
            $partials["visitor_lunch"] += $ticket["visitor_lunch"];
            $partials["total_lunch"] += $ticket["total_lunch"];
            $partials["student_dinner"] += $ticket["student_dinner"];
            $partials["employee_dinner"] += $ticket["employee_dinner"];
            $partials["third_party_employee_dinner"] += $ticket["third_party_employee_dinner"];
            $partials["visitor_dinner"] += $ticket["visitor_dinner"];
            $partials["total_dinner"] += $ticket["total_dinner"];
            $partials["student_total"] += $ticket["student_total"];
            $partials["employee_total"] += $ticket["employee_total"];
            $partials["third_party_employee_total"] += $ticket["third_party_employee_total"];
            $partials["visitor_total"] += $ticket["visitor_total"];
            $partials["total"] += $ticket["total"];
        }

        return $partials;
    }

    private function calculateAverages($partials, $difference)
    {
        $total_lunch = $partials["total_lunch"];
        $total_dinner = $partials["total_dinner"];
        $total = $partials["total"];

        return ["lunch" => $total_lunch / $difference,
            "dinner" => $total_dinner / $difference,
            "day" => $total / $difference
        ];

    }

    private function calculateAveragesByDayOfTheWeek($tickets)
    {
        $dayOfTheWeek = [
            0 => "Domingo",
            1 => "Segunda-Feira",
            2 => "Terça-Feira",
            3 => "Quarta-Feira",
            4 => "Quinta-Feira",
            5 => "Sexta-Feira",
            6 => "Sábado",
        ];

        $averagesByDayOfTheWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $averagesByDayOfTheWeek[] = [
                "day" => $dayOfTheWeek[$i],
                "student_lunch" => 0,
                "employee_lunch" => 0,
                "third_party_employee_lunch" => 0,
                "visitor_lunch" => 0,
                "total_lunch" => 0,
                "student_dinner" => 0,
                "employee_dinner" => 0,
                "third_party_employee_dinner" => 0,
                "visitor_dinner" => 0,
                "total_dinner" => 0,
                "student_total" => 0,
                "employee_total" => 0,
                "third_party_employee_total" => 0,
                "visitor_total" => 0,
                "total" => 0,
            ];
        }

        foreach ($tickets as $ticket) {

            $day = Carbon::parse($ticket["day"])->dayOfWeek;

            $averagesByDayOfTheWeek[$day]["day"] = $dayOfTheWeek[Carbon::parse($ticket["day"])->dayOfWeek];
            $averagesByDayOfTheWeek[$day]["student_lunch"] += $ticket["student_lunch"];
            $averagesByDayOfTheWeek[$day]["employee_lunch"] += $ticket["employee_lunch"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_lunch"] += $ticket["third_party_employee_lunch"];
            $averagesByDayOfTheWeek[$day]["visitor_lunch"] += $ticket["visitor_lunch"];
            $averagesByDayOfTheWeek[$day]["total_lunch"] += $ticket["total_lunch"];
            $averagesByDayOfTheWeek[$day]["student_dinner"] += $ticket["student_dinner"];
            $averagesByDayOfTheWeek[$day]["employee_dinner"] += $ticket["employee_dinner"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_dinner"] += $ticket["third_party_employee_dinner"];
            $averagesByDayOfTheWeek[$day]["visitor_dinner"] += $ticket["visitor_dinner"];
            $averagesByDayOfTheWeek[$day]["total_dinner"] += $ticket["total_dinner"];
            $averagesByDayOfTheWeek[$day]["student_total"] += $ticket["student_total"];
            $averagesByDayOfTheWeek[$day]["employee_total"] += $ticket["employee_total"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_total"] += $ticket["third_party_employee_total"];
            $averagesByDayOfTheWeek[$day]["visitor_total"] += $ticket["visitor_total"];
            $averagesByDayOfTheWeek[$day]["total"] += $ticket["total"];
        }

        return $averagesByDayOfTheWeek;
    }
}
