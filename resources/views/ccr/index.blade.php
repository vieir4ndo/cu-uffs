<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("CCR's") }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex items-end items-center flex-wrap -mx-3 mb-6">
            <div id="create-user-btn" class="flex-grow text-right mx-3 mt-3 md:mt-0">
                <a href="{{ route('web.ccr.create') }}" class="default-button bg-green-400" type="button">
                    <div class="flex items-center">
                        <x-fas-plus-circle/>
                        Novo CCR
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-ccuffs overflow-hidden sm:rounded-lg">
            <div class="p-3 sm:p-10">
                @livewire('ccr-table')
            </div>
        </div>
    </div>
</x-app-layout>
