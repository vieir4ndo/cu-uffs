<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ __('Novo Cardápio') }}
    </h2>

    <div class="py-10 sm:px-6 lg:px-8">
      <div class="bg-ccuffs overflow-hidden sm:rounded-lg p-3 sm:p-10">
        <form class="default-form">
          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="text-gray-100 text-sm font-medium mb-2" for="salad-1">
                Salada 1
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="salad-1" type="text" required="true">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="salad-2">
                Salada 2
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="salad-2" type="text" required="true">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="salad-3">
                Salada 3
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="salad-3" type="text" required="true">
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="grain-1">
                Grão 1
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grain-1" type="text" required="true">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="grain-2">
                Grão 2
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grain-2" type="text" required="true">
            </div>
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="grain-3">
                Grão 3
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grain-3" type="text" required="true">
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="side-dish">
                Guarnição
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="side-dish" type="text" required="true">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="mixture">
                Carne
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="mixture" type="text" required="true">
            </div>
          </div>


          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="vegan_mixture">
                Opção Vegetariana
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="vegan_mixture" type="text" required="true">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label class="block tracking-wide text-gray-100 text-sm font-medium mb-2" for="dessert">
                Sobremesa
              </label>
              <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="dessert" type="text" required="true">
            </div>
          </div>

          <div class="flex justify-end -mx-3 p-3">
            <button class="shadow bg-ccuffs-primary focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
              <div class="flex items-center">
                <x-fab-telegram-plane class="w-5 h-5 mr-3"/>
                Enviar
              </div>
            </button>
          </div>


        </form>
      </div>
    </div>
  </x-slot>
</x-app-layout>