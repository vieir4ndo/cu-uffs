<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ICCRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CCRController extends Controller
{
    private ICCRService $service;

    public function __construct(ICCRService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('ccr.index');
    }

    public function create()
    {
        return view('ccr.form', [
            'title' => 'Novo CCR'
        ]);
    }

    public function edit($id)
    {
        return view('ccr.form', [
            'title' => 'Editar CCR',
            'ccr' => $this->service->getCCRById($id)
        ]);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            $ccr = [
                "name" => $request->name,
                "status_ccr" => $request->status_ccr
            ];

            if (isset($request->id)) {
                $ccr['id'] = $request->id;
            }

            $validation = Validator::make($ccr, $this->createOrUpdateCCRRules());

            // if ($validation->fails()) {
            //$errors = $validation->errors()->all(); with errors
            // }

            $this->service->createCCR($ccr);

            return redirect()->route('web.ccr.index');
        } catch (Exception $e) {
            //return $this->index(); with errors $e->getMessage();
        }
    }

    private static function createOrUpdateCCRRules()
    {
        return [
            "id" => ['string'],
            "name" => ['required', 'string'],
            "status_ccr" => ['required', 'string']
        ];
    }
}
