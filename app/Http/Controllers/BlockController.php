<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\IBlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    private IBlockService $service;

    public function __construct(IBlockService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('block.index');
    }

    public function create()
    {
        return view('block.form', [
            'title' => 'Novo Bloco'
        ]);
    }

    public function edit($id)
    {
        return view('block.form', [
            'title' => 'Editar Bloco',
            'block' => $this->service->getBlockById($id)
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            $block = [
                "name" => $request->name,
                "description" => $request->description,
                "status_block" => $request->status,
            ];

            if (isset($request->id)) {
                $block['id'] = $request->id;
            }

            $validation = Validator::make($block, $this->createOrUpdateBlockRules());

            // if ($validation->fails()) {
            //$errors = $validation->errors()->all(); with errors
            // }

            $this->service->createBlock($block);

            return redirect()->route('web.block.index');
        } catch (Exception $e) {
            //return $this->index(); with errors $e->getMessage();
        }
    }

    private static function createOrUpdateBlockRules()
    {
        return [
            "id" => ['string'],
            "name" => ['required', 'string'],
            "description" => ['string'],
            "status" => ['required', 'string']
        ];
    }
}
