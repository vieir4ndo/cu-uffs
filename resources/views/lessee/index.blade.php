<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __("Locatários") }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex items-end items-center flex-wrap -mx-3 mb-6">
            <div id="create-user-btn" class="flex-grow text-right mx-3 mt-3 md:mt-0">
                <a href="javascript:;" data-modal-toggle="lessee-modal" class="default-button bg-green-400" type="button">
                    <div class="flex items-center">
                        <x-fas-plus-circle/>
                        Permitir Locatário
                    </div>
                </a>
            </div>
        </div>

        <div id="lessee-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
            <div class="modal max-w-lg">
                <div class="modal-body">
                    <button type="button" class="close-modal-btn" data-modal-toggle="lessee-modal">
                        <x-fas-times />
                        <span class="sr-only">Fechar janela</span>
                    </button>

                    <div class="modal-content">
                        <h3 class="text-lg text-gray-900 text-left mb-5">
                            Permitir ao usuário agendar salas
                        </h3>

                        <form name="lessee-form" id="lessee-form" class="default-form white" method="post" action="{{ route('web.lessee.changeLesseePermission') }}">
                            @csrf
                            <div class="flex flex-wrap justify-end -mx-3 mb-3">
                                <div class="w-full px-3 mb-6 md:mb-5">
                                    <label for="uid">Usuário IdUffs</label>
                                    <select name="uid" id="uid" class="select2">
                                        <option value="" disabled selected >Selecione um usuário</option>
                                        @foreach($users as $user)
                                            <option id="{{ $user['uid'] }}" value="{{ $user['uid'] }}" data-permission=true>
                                                {{ $user['uid'] . " - " . $user['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="is_lessee" value=1>

                                <button type="submit" class="text-white bg-ccuffs-primary focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                    Permitir
                                </button>
                                <button data-modal-toggle="lessee-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-ccuffs overflow-hidden sm:rounded-lg">
            <div class="p-3 sm:p-10">
                @livewire('lessee-table')
            </div>
        </div>
    </div>
</x-app-layout>
