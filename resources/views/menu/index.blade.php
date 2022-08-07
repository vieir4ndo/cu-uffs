<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ __('Cardápios') }}
    </h2>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <div class="flex items-end items-center flex-wrap -mx-3 mb-6">
        <div class="default-datepicker-field relative mx-3">
          <div class="icon">
            <x-fas-calendar-day />
          </div>
          <input datepicker id="date" type="text" placeholder="Selecione uma data" class="rounded-lg" >
        </div>
        <div id="filter-submit" class="mx-3">
          <button class="default-button bg-ccuffs-primary" type="button">
            Filtrar
          </button>
        </div>

        <div id="create-menu-btn" class="flex-grow text-right mx-3">
          <a href="{{ route('web.menu.create') }}" class="default-button bg-green-400" type="button">
            <div class="flex items-center">
              <x-fas-plus-circle />
              Novo cardápio
            </div>
          </a>
        </div>
      </div>

      <div class="bg-ccuffs overflow-hidden sm:rounded-lg">
        <div class="p-3 sm:p-10">
          @if (count($data) == 0)
            <p class="my-4 text-gray-400">Não há nenhum cardápio cadastrado.</p>
          @else
            @foreach ($data as $menu)
              <div class="flex flex-wrap justify-center">
                <table class="table-fixed mx-3">
                  <thead>
                    <tr>
                      <th class="border border-ccuffs-ui bg-ccuffs-primary px-4 py-2">{{ date('d/m - l', strtotime($menu->date)) }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->salad_1       }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->salad_2       }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->salad_3       }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->grains_1      }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->grains_2      }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->grains_3      }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->side_dish     }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->mixture       }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->vegan_mixture }}</td></tr>
                    <tr><td class="border border-ccuffs-ui bg-white text-center px-4 py-2">{{ $menu->dessert       }}</td></tr>
                  </tbody>
                </table>

                <div class="actions">
                  <a href="{{ route('web.menu.edit', ['id' => $menu->id]) }}" class="default-button block mb-4 bg-ccuffs-secondary" type="button">
                    <div class="flex items-center">
                      <x-fas-pen />
                      Editar cardápio
                    </div>
                  </a>
                  <a href="javascript:;" class="default-button block bg-ccuffs-tertiary" type="button" data-modal-toggle="delete-menu-modal">
                    <div class="flex items-center">
                      <x-fas-trash-alt />
                      Excluir cardápio
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </x-slot>
</x-app-layout>

<div id="delete-menu-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
  <div class="modal">
    <div class="modal-body">
      <button type="button" class="close-modal-btn" data-modal-toggle="delete-menu-modal">
        <x-fas-times />
        <span class="sr-only">Fechar janela</span>
      </button>

      <div class="modal-content">
        <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
          Tem certeza que deseja excluir esse cardápio?
        </h3>
        <button data-modal-toggle="delete-menu-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
          Excluir
        </button>
        <button data-modal-toggle="delete-menu-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</div>