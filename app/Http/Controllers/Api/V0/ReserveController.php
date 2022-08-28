<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IReserveService;
use App\Models\Api\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller
{
    private IReserveService $service;

    public function __construct(IReserveService $service) {
        $this->service = $service;
    }

    public function createReserve(Request $request) {
        try {
            // Converter datas para dd-mm-yyyy e depois para yyyy-mm-dd
            $begin = date('Y-m-d H:i:s',
                strtotime(str_replace('/', '-', $request->begin))
            );
            $end = date('Y-m-d H:i:s',
                strtotime(str_replace('/', '-', $request->end))
            );

            $reserve = [
                "begin" => $begin,
                "end" => $end,
                "description" => $request->description,
                "room_id" => $request->room_id,
                "ccr_id" => $request->ccr_id,
                "locator_id" => $request->user()->id,
            ];

            $validation = Validator::make(array_filter($reserve), $this->createReserveRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createReserve($reserve);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteReserve(Request $request, $id) {
        try {
            // TODO: Mover para o middleware
            $reserve = $this->service->getReserveById($id);

            if ($reserve->locator_id != $request->user()->id) {
                return ApiResponse::forbidden();
            }

            $this->service->deleteReserve($id);
            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function changeRequestStatus(Request $request, $id) {
        try {
            // TODO: Mover para o middleware
            $reserve = $this->service->getReserveById($id);

            if ($request->user()->id != $reserve->responsable_id) {
                return ApiResponse::forbidden('Você não tem permissão para realizar esta ação');
            }

            if ($reserve->status != 0) {
                $statusString = $reserve->status == 1 ? 'aprovado' : 'negado';
                return ApiResponse::badRequest('Este agendamento já foi ' . $statusString . '.');
            }

            $reserve = [
                "status" => $request->new_status,
                "observation" => $request->observation
            ];

            $validation = Validator::make(array_filter($reserve), $this->changeReserveStatusRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateReserve($reserve, $id);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getLocatorReserves(Request $request) {
        try {
            $reserves = $this->service->getReservesByLocatorId($request->user()->id);

            foreach ($reserves as $reserve) {
                $reserve->begin = date('d/m/Y H:i', strtotime($reserve->begin));
                $reserve->end = date('d/m/Y H:i', strtotime($reserve->end));
            }

            return ApiResponse::ok($reserves);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getResponsableRequests(Request $request) {
        try {
            $reserves = $this->service->getRequestsByResponsableID($request->user()->id);

            foreach ($reserves as $reserve) {
                $reserve->begin = date('d/m/Y H:i', strtotime($reserve->begin));
                $reserve->end = date('d/m/Y H:i', strtotime($reserve->end));
            }

            return ApiResponse::ok($reserves);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getReserveById(Request $request, $id) {
        try {
            // TODO: Mover para o middleware
            $reserve = $this->service->getReserveById($id);

            if (!in_array($request->user()->id, [$reserve->locator_id, $reserve->responsable_id])) {
                return ApiResponse::forbidden();
            }

            $reserve->begin = date('d/m/Y H:i', strtotime($reserve->begin));
            $reserve->end = date('d/m/Y H:i', strtotime($reserve->end));

            return ApiResponse::ok($reserve);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    private function createReserveRules() {
        return [
            "begin" => ['required', 'date_format:Y-m-d H:i:s'],
            "end" => ['required', 'date_format:Y-m-d H:i:s'],
            "description" => ['string'],
            "room_id" => ['required', 'integer'],
            "ccr_id" => ['integer'],
            "locator_id" => ['required', 'integer'],
        ];
    }

    private function changeReserveStatusRules() {
        return [
            "status" => ['required', 'integer'],
            "observation" => ['string'],
        ];
    }

}
