<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ __('Vendas') }}
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-ccuffs overflow-hidden sm:rounded-lg">
      <div class="p-3 sm:p-10">
        @livewire('entry-table')
      </div>
    </div>
  </div>
</x-app-layout>
