<?php

namespace App\Http\Controllers;

use App\Interfaces\Services\ICCRService;
use App\Jobs\StartCreateOrUpdateUserJob;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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

            if ($validation->fails()) {
                Alert::error('Erro', Arr::flatten($validation->errors()->all()));
                return back();
            }

            $this->service->createCCR($ccr);

            Alert::success('Sucesso', 'CCR cadastrado com sucesso!');
            return redirect()->route('web.ccr.index');
        } catch (Exception $e) {
            Alert::error('Erro', $e->getMessage());
            return back();
        }
    }

    private static function createOrUpdateCCRRules()
    {
        return [
            "id" => ['string'],
            "name" => ['required', 'string'],
            "status_ccr" => ['required', 'boolean']
        ];
    }
}
