<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index() {
        $data = DB::select("select * from menus order by id desc limit 1");

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
}
