<?php

namespace App\Http\Livewire;

use App\Interfaces\Services\IUserService;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AiPassportPhotoService;
use App\Services\BarcodeService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class LesseeTable extends PowerGridComponent
{
    private IUserService $service;

    use ActionButton;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $repository = new UserRepository();
        $barcodeService = new BarcodeService();
        $aiPassportPhotoService = new AiPassportPhotoService();
        $this->service = new UserService($repository, $barcodeService, $aiPassportPhotoService);
    }

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
    * PowerGrid datasource.
    *
    * @return Builder<\App\Models\User>
    */
    public function datasource(): Builder
    {
        return User::query()->where('is_lessee', '=', '1');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('uid')
            ->addColumn('name')
            ->addColumn('email');
   }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('UID', 'uid')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('NOME', 'name')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('E-MAIL', 'email')
                ->sortable()
                ->searchable()
                ->makeInputText(),
        ]
;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

     /**
     * PowerGrid User Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            Button::add("removePermission")
                ->class('default-button bg-ccuffs-tertiary')
                ->caption('Remover Permissão')
                ->emit('removePermission', ['uid' => 'uid'])
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

     /**
     * PowerGrid User Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($user) => $user->id === 1)
                ->hide(),
        ];
    }
    */

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'removePermission'
            ]);
    }

    public function removePermission(array $data): void
    {
        try {
            $permission = [
                "is_lessee" => false,
            ];

            $this->service->changeLesseePermission($data['uid'], $permission);

            $this->dispatchBrowserEvent('showAlert', ['message' => "Permissão removida com sucesso!"]);
        } catch (Exception $e) {
            //Alert::error('Erro', $e->getMessage());
            $this->dispatchBrowserEvent("showAlert", ["message" => $e->getMessage()]);
        }
    }
}
