<?php

namespace App\Http\Controllers;

use App\Http\Validators\MenuValidator;
use App\Interfaces\Services\IMenuService;
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

    public function index($date = null)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $date ??= now();
        $menu = $this->service->getMenuByDate($date);

        return view('restaurant.menu.index', [
            'menu' => $menu,
            'date' => date('d/m/Y', strtotime($date))
        ]);
    }

    public function create()
    {
        $title = 'Novo Cardápio';

        return view('restaurant.menu.form', [
            'title' => $title
        ]);
    }

    public function edit($id)
    {
        $title = 'Editar Cardápio';
        $menu = $this->service->getMenuById($id);
        $menu->date = date('d/m/Y', strtotime($menu->date));

        return view('restaurant.menu.form', [
            'title' => $title,
            'menu' => $menu
        ]);
    }

    public function filter(Request $request)
    {
        // Converter data para dd-mm-yyyy
        $date = str_replace('/', '-', $request->date);
        // e depois formatar para yyyy-mm-dd
        $formatted_date = date('Y-m-d', strtotime($date));

        return $this->index($date);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            // Converter data para dd-mm-yyyy
            $date = str_replace('/', '-', $request->date);
            // e depois formatar para yyyy-mm-dd
            $formatted_date = date('Y-m-d', strtotime($date));

            $menu = [
                "salad_1"        => $request->salad_1,
                "salad_2"        => $request->salad_2,
                "salad_3"        => $request->salad_3,
                "grains_1"       => $request->grains_1,
                "grains_2"       => $request->grains_2,
                "grains_3"       => $request->grains_3,
                "side_dish"      => $request->side_dish,
                "mixture"        => $request->mixture,
                "vegan_mixture"  => $request->vegan_mixture,
                "dessert"        => $request->dessert,
                "date"           => $formatted_date,
                "ru_employee_id" => $request->user()->id
            ];

            $validation = Validator::make($menu, MenuValidator::createMenuRules());

            // if ($validation->fails()) {
            //$errors = $validation->errors()->all(); with errors
            // }

            $this->service->createMenu($menu);

            return redirect()->route('web.menu.index');
        } catch (Exception $e) {
            //return $this->index(); with errors $e->getMessage();
        }
    }

    public function delete($date)
    {
        $this->service->deleteMenu($date);

        return redirect()->route('web.menu.index');
    }
}
