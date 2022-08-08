<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ $title }}
    </h2>

    <div class="py-10 sm:px-6 lg:px-8">
      <div class="bg-ccuffs overflow-hidden sm:rounded-lg p-3 sm:p-10">
        <form name="add-blog-post-form" id="add-blog-post-form" class="default-form" method="POST" action="{{ route('web.menu.createOrUpdate') }}">
          @csrf
          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-5">
              <label for="date">Data</label>
              <div class="default-datepicker-field relative">
                <div class="icon">
                  <x-fas-calendar-day />
                </div>
                <input datepicker id="date" name="date" type="text" required="true" value="{{ $menu->date ?? '' }}" >
              </div>
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="salad-1">Salada 1</label>
              <input id="salad-1" name="salad_1" type="text" required="true" value="{{ $menu->salad_1 ?? '' }}">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="salad-2">Salada 2</label>
              <input id="salad-2" name="salad_2" type="text" required="true" value="{{ $menu->salad_2 ?? '' }}">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="salad-3">Salada 3</label>
              <input id="salad-3" name="salad_3" type="text" required="true" value="{{ $menu->salad_3 ?? '' }}">
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="grain-1">Grão 1</label>
              <input id="grain-1" name="grains_1" type="text" required="true" value="{{ $menu->grains_1 ?? '' }}">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="grain-2">Grão 2</label>
              <input id="grain-2" name="grains_2" type="text" required="true" value="{{ $menu->grains_2 ?? '' }}">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label for="grain-3">Grão 3</label>
              <input id="grain-3" name="grains_3" type="text" required="true" value="{{ $menu->grains_3 ?? '' }}">
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="side-dish">Guarnição</label>
              <input id="side-dish" name="side_dish" type="text" required="true" value="{{ $menu->side_dish ?? '' }}">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="mixture">Carne</label>
              <input id="mixture" name="mixture" type="text" required="true" value="{{ $menu->mixture ?? '' }}">
            </div>
          </div>


          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="vegan_mixture">Opção Vegetariana</label>
              <input id="vegan_mixture" type="text" name="vegan_mixture" required="true" value="{{ $menu->vegan_mixture ?? '' }}">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="dessert">Sobremesa</label>
              <input id="dessert" type="text" required="true" name="dessert" value="{{ $menu->dessert ?? '' }}">
            </div>
          </div>

          <div class="flex justify-end -mx-3 p-3">
            <button class="default-button bg-ccuffs-primary" type="submit">
              <div class="flex items-center">
                <x-fab-telegram-plane />
                Enviar
              </div>
            </button>
          </div>
        </form>
      </div>
    </div>
  </x-slot>
</x-app-layout>
