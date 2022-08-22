<?php

namespace App\Http\Controllers;

use App\Http\Validators\TicketValidator;
use App\Interfaces\Services\IUserService;
use App\Interfaces\Services\ITicketService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Enums\TicketOrEntryType;
use RealRashid\SweetAlert\Facades\Alert;

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
                'date_time' => now(),
                'amount' => $request->amount,
                'enrollment_id' => $request->enrollment_id
            ];

            $validation = Validator::make($data, TicketValidator::insertTicketsWithEnrollmentIdRules());
            if ($validation->fails()) {
                Alert::error('Erro', Arr::flatten($validation->errors()->all()));
                return back();
            }

            unset($data['enrollment_id']);
            $this->ticketService->insertTicket($request->enrollment_id, $data);

            Alert::success('Sucesso', 'Venda registrada com sucesso!');
            return back();
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
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

            Alert::success('Sucesso', 'Venda registrada com sucesso!');
            return back();
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
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

            Alert::success('Sucesso', 'Venda registrada com sucesso!');
            return back();
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }
}
