<?php

namespace App\Services;

use App\Enums\TicketOrEntryType;
use App\Enums\UserType;
use App\Interfaces\Repositories\IEntryRepository;
use App\Interfaces\Services\IEntryService;
use App\Interfaces\Services\IUserService;
use Carbon\Carbon;


class EntryService implements IEntryService
{
    private IEntryRepository $repository;
    private IUserService $userService;
    private $visitorEnrollmentId;
    private $thirdPartyEmployeeEnrollmentId;

    public function __construct(
        IEntryRepository $entryRepository,
        IUserService     $userService
    )
    {
        $this->repository = $entryRepository;
        $this->userService = $userService;
        $this->visitorEnrollmentId = config("ticket.visitor_enrollment_id");
        $this->thirdPartyEmployeeEnrollmentId = config("ticket.third_party_employee_enrollment_id");
    }

    public function insertEntry($enrollment_id, $data)
    {
        if ($enrollment_id != $this->visitorEnrollmentId and $enrollment_id != $this->thirdPartyEmployeeEnrollmentId) {

            $user = $this->userService->getUserByEnrollmentId($enrollment_id, false);

            if ($user->active == false) {
                throw new \Exception("User is not active.");
            }

            if ($user->status_enrollment_id == false) {
                throw new \Exception("User's enrollment id is not active.");
            }

            if ($user->ticket_amount == 0) {
                throw new \Exception("User has no tickets available.");
            }

            $entryDate = Carbon::parse($data["date_time"]);

            if ($this->getLastEntryById($user->id) != null) {
                $lastEntryDate = Carbon::parse($this->getLastEntryById($user->id)->date_time);

                if ($entryDate->diffInHours($lastEntryDate) < 4.5) {
                    throw new \Exception("User has already entered the restaurant in this period.");
                }
            }

            $data["user_id"] = $user->id;
            $data["type"] = ($user->type == UserType::Employee->value) ? TicketOrEntryType::Employee->value : TicketOrEntryType::Student->value;

            $this->userService->updateTicketAmount($user->uid, -1);
        } else {
            $data["type"] = ($enrollment_id == $this->visitorEnrollmentId) ? TicketOrEntryType::Visitor->value : TicketOrEntryType::ThirdPartyEmployee->value;
        }

        $this->repository->insert($data);
    }

    public function getEntriesByUsername(string $uid)
    {
        $user = $this->userService->getUserByUsername($uid);

        $result = $this->repository->getEntriesById($user->id);

        return $result;
    }

    public function generateReport($init_date, $final_date)
    {
        $initdate = Carbon::parse($init_date);
        $finaldate = Carbon::parse($final_date);;

        $difference = $finaldate->diffInDays($initdate);

        $entries = $this->calculateEntriesForReport($initdate, $difference);

        $partials = $this->calculatePartials($entries);

        $averages = $this->calculateAverages($partials, $difference);

        return [
            "init_date" => $init_date,
            "final_date" => $final_date,
            "entries" => $entries,
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

    private function calculateEntriesForReport($init_date, $differece)
    {
        $all_entries = [];

        for ($i = 0; $i <= $differece; $i++) {
            $date_to_look_for = $init_date;
            $entries = $this->repository->getEntriesInInterval($date_to_look_for->setTime(0, 0, 0)->toDateTimeString(), $date_to_look_for->setTime(23, 59, 59)->toDateTimeString());

            $entry = [
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

                if ($date->greaterThanOrEqualTo($date_to_validate->setTime(13, 30, 0))) {
                    switch (intval($value->type)) {
                        case TicketOrEntryType::Student->value:
                            $entry["student_lunch"] = $entry["student_lunch"] + 1;
                            $entry["student_total"] = $entry["student_total"] + 1;
                            break;
                        case TicketOrEntryType::Employee->value:
                            $entry["employee_lunch"] = $entry["employee_lunch"] + 1;
                            $entry["employee_total"] = $entry["employee_total"] + 1;
                            break;
                        case TicketOrEntryType::ThirdPartyEmployee->value:
                            $entry["third_party_employee_lunch"] = $entry["third_party_employee_lunch"] + 1;
                            $entry["third_party_employee_total"] = $entry["third_party_employee_total"] + 1;
                            break;
                        case TicketOrEntryType::Visitor->value:
                            $entry["visitor_lunch"] = $entry["visitor_lunch"] + 1;
                            $entry["visitor_total"] = $entry["visitor_total"] + 1;
                            break;
                    }

                    $entry["total_lunch"] = $entry["total_lunch"] + 1;
                } else {
                    switch (intval($value->type)) {
                        case TicketOrEntryType::Student->value:
                            $entry["student_dinner"] = $entry["student_dinner"] + 1;
                            $entry["student_total"] = $entry["student_total"] + 1;
                            break;
                        case TicketOrEntryType::Employee->value:
                            $entry["employee_dinner"] = $entry["employee_dinner"] + 1;
                            $entry["employee_total"] = $entry["employee_total"] + 1;
                            break;
                        case TicketOrEntryType::ThirdPartyEmployee->value:
                            $entry["third_party_employee_dinner"] = $entry["third_party_employee_dinner"] + 1;
                            $entry["third_party_employee_total"] = $entry["third_party_employee_total"] + 1;
                            break;
                        case TicketOrEntryType::Visitor->value:
                            $entry["visitor_dinner"] = $entry["visitor_dinner"] + 1;
                            $entry["visitor_total"] = $entry["visitor_total"] + 1;
                            break;
                    }

                    $entry["total_dinner"] = $entry["total_dinner"] + 1;
                }

                $entry["total"] = $entry["total"] + 1;
            }

            $all_entries[] = $entry;

            $init_date->addDays(1);
        }

        return $all_entries;
    }

    private function calculatePartials($entries)
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

        foreach ($entries as $entry) {
            $partials["student_lunch"] += $entry["student_lunch"];
            $partials["employee_lunch"] += $entry["employee_lunch"];
            $partials["third_party_employee_lunch"] += $entry["third_party_employee_lunch"];
            $partials["visitor_lunch"] += $entry["visitor_lunch"];
            $partials["total_lunch"] += $entry["total_lunch"];
            $partials["student_dinner"] += $entry["student_dinner"];
            $partials["employee_dinner"] += $entry["employee_dinner"];
            $partials["third_party_employee_dinner"] += $entry["third_party_employee_dinner"];
            $partials["visitor_dinner"] += $entry["visitor_dinner"];
            $partials["total_dinner"] += $entry["total_dinner"];
            $partials["student_total"] += $entry["student_total"];
            $partials["employee_total"] += $entry["employee_total"];
            $partials["third_party_employee_total"] += $entry["third_party_employee_total"];
            $partials["visitor_total"] += $entry["visitor_total"];
            $partials["total"] += $entry["total"];
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

    private function calculateAveragesByDayOfTheWeek($entries)
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

        foreach ($entries as $entry) {

            $day = Carbon::parse($entry["day"])->dayOfWeek;

            $averagesByDayOfTheWeek[$day]["day"] = $dayOfTheWeek[Carbon::parse($entry["day"])->dayOfWeek];
            $averagesByDayOfTheWeek[$day]["student_lunch"] += $entry["student_lunch"];
            $averagesByDayOfTheWeek[$day]["employee_lunch"] += $entry["employee_lunch"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_lunch"] += $entry["third_party_employee_lunch"];
            $averagesByDayOfTheWeek[$day]["visitor_lunch"] += $entry["visitor_lunch"];
            $averagesByDayOfTheWeek[$day]["total_lunch"] += $entry["total_lunch"];
            $averagesByDayOfTheWeek[$day]["student_dinner"] += $entry["student_dinner"];
            $averagesByDayOfTheWeek[$day]["employee_dinner"] += $entry["employee_dinner"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_dinner"] += $entry["third_party_employee_dinner"];
            $averagesByDayOfTheWeek[$day]["visitor_dinner"] += $entry["visitor_dinner"];
            $averagesByDayOfTheWeek[$day]["total_dinner"] += $entry["total_dinner"];
            $averagesByDayOfTheWeek[$day]["student_total"] += $entry["student_total"];
            $averagesByDayOfTheWeek[$day]["employee_total"] += $entry["employee_total"];
            $averagesByDayOfTheWeek[$day]["third_party_employee_total"] += $entry["third_party_employee_total"];
            $averagesByDayOfTheWeek[$day]["visitor_total"] += $entry["visitor_total"];
            $averagesByDayOfTheWeek[$day]["total"] += $entry["total"];
        }

        return $averagesByDayOfTheWeek;
    }

    public function getLastEntryById(string $id)
    {
        return $this->repository->getLastEntryById($id);
    }

}
