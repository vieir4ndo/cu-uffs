<?php

namespace App\Http\Controllers\Api\V0;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IMenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private IMenuService $service;

    public function __construct(IMenuService $service)
    {
        $this->service = $service;
    }

    public function createMenu(){

    }

    public function updateMenu(Request $request, $data){

    }

    public function deleteMenu(Request $request, $data){

    }

    public function getMenu(Request $request, $data){

    }
}
