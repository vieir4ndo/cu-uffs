<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\ITicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Enums\TicketOrEntryType;

class SellController extends Controller
{
    private IUserService $userService;
    private ITicketService $ticketService;

    public function __construct(IUserService $userService, ITicketService $ticketService)
    {
        $this->userService = $userService;
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('restaurant.sell.index', [
            'users'=> $users
        ]);
    }

    public function sellTicket(Request $request){
        try {
            $data = [
                'third_party_cashier_employee_id' => $request->user()->id,
                "date_time" => now(),
                'amount' => $request->amount,
                'enrollment_id' => $request->enrollment_id
            ];

            $validation = Validator::make($data, $this->insertTicketsRules());
            // if ($validation->fails()) {
            // $errors = $validation->errors()->all(); with errors
            // }

            unset($data["enrollment_id"]);

            $this->ticketService->insertTicket($request->enrollment_id, $data);

            return $this->index();

        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function sellVisitorTicket(Request $request){
        try {
            $data = [
                'third_party_cashier_employee_id' => $request->user()->id,
                "date_time" => now(),
                "amount" => 1,
                "type" => TicketOrEntryType::Visitor->value
            ];

            $this->ticketService->insertTicketForVisitors($data);

            return $this->index();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function sellThirdPartyTicket(Request $request){
        try {
            $data = [
                'third_party_cashier_employee_id' => $request->user()->id,
                "date_time" => now(),
                "amount" => 1,
                "type" => TicketOrEntryType::ThirdPartyEmployee->value
            ];

            $this->ticketService->insertTicketForVisitors($data);

            return $this->index();
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function insertTicketsRules(){
        return [
            'amount' => ['required','integer', 'min:0', 'not_in:0'],
            'enrollment_id' => ['required']
        ];
    }
}
