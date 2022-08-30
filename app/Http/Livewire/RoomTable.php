<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class RoomTable extends PowerGridComponent
{
    use ActionButton;
    public string $primaryKey = 'rooms.id';
    public string $sortField = 'rooms.id';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

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
    * @return Builder<\App\Models\Room>
    */
    public function datasource(): Builder
    {
        return Room::query()
            ->leftJoin('blocks', 'rooms.block_id', '=', 'blocks.id')
            ->leftJoin('users', 'rooms.responsable_id', '=', 'users.id')
            ->select(
                'rooms.id as id',
                'rooms.name',
                'rooms.description',
                'rooms.capacity',
                'rooms.status_room',
                'blocks.name as block',
                'users.name as responsable'
            );
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
            ->addColumn('rooms.id')
            ->addColumn('name')
            ->addColumn('description')
            ->addColumn('capacity')
            ->addColumn('status', function (Room $model) {
                return ($model->status_room ? 'Ativo' : 'Inativo');
            })
            ->addColumn('responsable')
            ->addColumn('block');
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

            Column::make('NOME', 'name')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('DESCRIÇÃO', 'description')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('CAPACIDADE', 'capacity')
                ->sortable()
                ->searchable()
                ->makeInputRange(),

            Column::make('RESPONSÁVEL', 'responsable')
                ->sortable()
                ->searchable()
                ->makeInputRange(),

            Column::make('BLOCO', 'block')
                ->sortable()
                ->searchable()
                ->makeInputRange(),

            Column::make('STATUS', 'status')
                ->sortable()
                ->searchable(),

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
     * PowerGrid Room Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            Button::make('edit', 'Editar')
                ->class('bg-green-400 cursor-pointer text-white px-2 py-1.5 m-1 rounded text-sm')
                ->route('web.room.edit', ['id' => 'id'])
                ->target('_self')
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
     * PowerGrid Room Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($room) => $room->id === 1)
                ->hide(),
        ];
    }
    */
}
