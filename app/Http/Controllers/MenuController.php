<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\IMenuService;
use App\Models\Api\ApiResponse;
use App\Models\Menu;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    private IMenuService $service;

    public function __construct(IMenuService $service)
    {
        $this->service = $service;
    }

    public function index() {
        $data = $this->service->getLatestMenu();

        return view('menu.index', [
            'data' => $data
        ]);
    }

    public function create() {
        $title = 'Novo Cardápio';

        return view('menu.form', [
            'title' => $title
        ]);
    }

    public function edit($id) {
        $title = 'Editar Cardápio';
        $menu = DB::select("select * from menus where id=" . $id)[0];
        $menu->date = date('d/m/Y', strtotime($menu->date));

        return view('menu.form', [
            'title' => $title,
            'menu' => $menu
        ]);
    }

    public function createOrUpdate(Request $request){
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
                //$errors = $validation->errors()->all(); with errors
            }

            $this->service->createMenu($menu);

            return $this->index();
        }
        catch (Exception $e){
            //return $this->index(); with errors $e->getMessage();
        }
    }
}
