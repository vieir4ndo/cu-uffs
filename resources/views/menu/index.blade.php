<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ __('Cardápios') }}
    </h2>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <div class="flex items-end flex-wrap -mx-3 mb-6">
        <div class="relative">
          <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
          </div>
          <input datepicker id="datepickerId" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
        </div>
        <div id="filter-submit" class="mx-3">
          <button class="shadow bg-ccuffs-primary focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
            Filtrar
          </button>
        </div>

        <div id="create-menu-btn" class="flex-grow text-right mx-3">
          <a href="{{ route('web.menu.create') }}" class="shadow bg-ccuffs-secondary focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
            <div class="flex items-center">
              <x-fas-plus-circle class="w-5 h-5 mr-3"/>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
              @foreach ($data as $menu)
                <table class="my-5 table-fixed">
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
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </x-slot>
</x-app-layout>