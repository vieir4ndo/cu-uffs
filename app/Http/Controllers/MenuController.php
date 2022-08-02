<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index() {
        $data = DB::select("select * from menus");

        return view('menu.index', [
            'data' => $data
        ]);
    }

    public function create() {
        return view('menu.create');
    }
}
