<?php

namespace App\Http\Livewire;

use App\Http\Validators\AuthValidator;
use App\Interfaces\Services\IAuthService;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AiPassportPhotoService;
use App\Services\AuthService;
use App\Services\BarcodeService;
use App\Services\CaptchaMonsterService;
use App\Services\IdUffsService;
use App\Services\MailjetService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UserTable extends PowerGridComponent
{
    private IUserService $service;
    private IAuthService $authService;

    use ActionButton;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $repository = new UserRepository();
        $barcodeService = new BarcodeService();
        $aiPassportPhotoService = new AiPassportPhotoService();
        $this->service = new UserService($repository, $barcodeService, $aiPassportPhotoService);
        $captchaMonsterService = new CaptchaMonsterService();
        $idUffsService = new IdUffsService($captchaMonsterService);
        $mailJetService = new MailjetService();
        $this->authService = new AuthService($this->service, $mailJetService, $idUffsService);
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
        return User::query()->where('type', [config('user.users_without_iduffs')]);
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
            ->addColumn('email')
            ->addColumn('active', function (User $model) {
                return ($model->active ? 'SIM' : 'NÃO');
              });
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
            Column::make('LOGIN', 'uid')
                ->sortable()
                ->searchable(),

            Column::make('NOME', 'name')
                ->sortable()
                ->searchable(),

            Column::make('E-MAIL', 'email')
                ->sortable()
                ->searchable(),

            Column::make('ATIVO', 'active')
                ->sortable(),
        ];
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
           Button::make('web.user.forgot-password', 'Alteração de Senha')
               ->class('default-button bg-ccuffs-primary')
               ->emit('forgotPassword', ['uid' => 'uid']),
           Button::add("activate")
               ->caption('Ativar')
               ->class('default-button bg-ccuffs-primary')
               ->emit('enableUser', ['uid' => 'uid']),
           Button::add("deactivate")
               ->caption('Desativar')
               ->class('default-button bg-ccuffs-tertiary')
               ->emit('disableUser', ['uid' => 'uid'])
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


    public function actionRules(): array
    {
       return [
            Rule::button('activate')
                ->when(function (User $model) {
                    return $model->active;
                })
                ->disable(),
           Rule::button('deactivate')
               ->when(function (User $model) {
                   return !$model->active;
               })
               ->disable(),
        ];
    }

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'disableUser',
                "enableUser",
                'forgotPassword'
            ]);
    }

    public function enableUser(array $data): void
    {
        try {
            $user = [
                "active" => true,
            ];

            $this->service->deactivateUser($data['uid'], $user);

            //Alert::success('Sucesso', "Usuário {$operation} com sucesso!");
            $this->dispatchBrowserEvent('showAlert', ['message' => "Usuário ativado com sucesso!"]);
        } catch (Exception $e) {
            //Alert::error('Erro', $e->getMessage());
            $this->dispatchBrowserEvent("showAlert", ["message" => $e->getMessage()]);
        }
    }

    public function disableUser(array $data): void
    {
        try {
            $user = [
                "active" => false,
            ];

            $this->service->deactivateUser($data['uid'], $user);

            //Alert::success('Sucesso', "Usuário {$operation} com sucesso!");
            $this->dispatchBrowserEvent('showAlert', ['message' => "Usuário desativado com sucesso!"]);
        } catch (Exception $e) {
            //Alert::error('Erro', $e->getMessage());
            $this->dispatchBrowserEvent("showAlert", ["message" => $e->getMessage()]);
        }
    }

    public function forgotPassword(array $data): void{
        try {
            $validation = Validator::make(["uid" => $data["uid"]], AuthValidator::forgotPasswordRules());

            if ($validation->fails()) {
                //Alert::error('Erro', Arr::flatten($validation->errors()->all()));
                $this->dispatchBrowserEvent("showAlert", ["message" => Arr::flatten($validation->errors()->all())]);
            }

            $this->authService->forgotPassword($data["uid"]);

            //Alert::success('Sucesso', 'Solicitação para recuperar registrada com sucesso!');
            $this->dispatchBrowserEvent('showAlert', ['message' => 'Solicitação para recuperar registrada com sucesso!']);
        } catch (Exception $e) {
            //Alert::error('Erro', $e->getMessage());
            $this->dispatchBrowserEvent("showAlert", ["message" => $e->getMessage()]);
        }
    }
}
