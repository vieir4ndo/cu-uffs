<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IMenuService;
use App\Models\Api\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    private IMenuService $service;

    public function __construct(IMenuService $service)
    {
        $this->service = $service;
    }

    public function createMenu(Request $request){
        try {
            $menu = [
                "salad_1" => $request->salad_1,
                "salad_2" => $request->salad_2,
                "salad_3" => $request->salad_3,
                "grains_1" => $request->grains_1,
                "grains_2" => $request->grains_2,
                "grains_3" => $request->grains_3,
                "side_dish" => $request->side_dish,
                "mixture" => $request->mixture,
                "vegan_mixture" => $request->vegan_mixture,
                "dessert" => $request->dessert,
                "date" => Carbon::parse($request->date),
                "ru_employee_id" => $request->user()->id
            ];

            $validation = Validator::make($menu, \MenuValidator::createMenuRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->createMenu($menu);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function updateMenu(Request $request, $date){
        try {
            $menu = [
                "salad_1" => $request->salad_1,
                "salad_2" => $request->salad_2,
                "salad_3" => $request->salad_3,
                "grains_1" => $request->grains_1,
                "grains_2" => $request->grains_2,
                "grains_3" => $request->grains_3,
                "side_dish" => $request->side_dish,
                "mixture" => $request->mixture,
                "vegan_mixture" => $request->vegan_mixture,
                "dessert" => $request->dessert,
            ];

            $menu = array_filter($menu);

            $validation = Validator::make($menu, \MenuValidator::updateMenuRules());

            if ($validation->fails()) {
                return ApiResponse::badRequest($validation->errors()->all());
            }

            $this->service->updateMenu($menu, $date);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function deleteMenu($date){
        try {
            $menu = $this->service->deleteMenu($date);

            return ApiResponse::ok(null);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }

    public function getMenu(){
        try {
            $menus = $this->service->getMenu();

            return ApiResponse::ok($menus);
        } catch (Exception $e) {
            return ApiResponse::badRequest($e->getMessage());
        }
    }
}
