<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ __('Cardápios') }}
    </h2>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <div class="flex items-end flex-wrap -mx-3 mb-6">
        <div id="select-month" class="mx-3">
          <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="grid-last-name">
            Mês
          </label>
          <div class="inline-block relative w-64">
            <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
              @foreach (range(1, 12) as $month)
                <option>{{ strftime('%B', mktime(0, 0, 0, $month)) }}</option>
              @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
          </div>
        </div>
        <div id="select-year" class="mx-3">
          <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="grid-last-name">
            Ano
          </label>
          <div class="inline-block relative w-64">
            <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
              @foreach (range(2022, 2020) as $year)
                <option>{{ $year }}</option>
              @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
          </div>
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