<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap mb-3">
                @if (Auth::user()->type == \App\Enums\UserType::RUEmployee->value)
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                        <div class="default-card bg-ccuffs-primary md:mx-15">
                            <a href="javascript:;" data-modal-toggle="entry-report-modal">
                                <div class="card-content">
                                    <h3 class="card-title mb-3 md:mr-5">
                                        Relatórios de Entradas
                                    </h3>
                                    <x-fas-chevron-right />
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->type == \App\Enums\UserType::ThirdPartyCashierEmployee->value or Auth::user()->type == \App\Enums\UserType::RUEmployee->value)
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                        <div class="default-card bg-ccuffs-secondary md:mx-15">
                            <a href="javascript:;" data-modal-toggle="ticket-report-modal">
                                <div class="card-content">
                                    <h3 class="card-title mb-3 md:mr-5">
                                        Relatórios de Vendas
                                    </h3>
                                    <x-fas-chevron-right />
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="entry-report-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
        <div class="modal max-w-lg">
            <div class="modal-body">
                <button type="button" class="close-modal-btn" data-modal-toggle="entry-report-modal">
                    <x-fas-times />
                    <span class="sr-only">Fechar janela</span>
                </button>

                <div class="modal-content">
                    <h3 class="text-lg text-gray-900 text-left mb-5">
                        Relatório de Entradas
                    </h3>

                    <form name="entry-report-form" id="entry-report-form" class="default-form white" method="post" action="{{ route('web.report.redirect-entry-report') }}">
                        @csrf
                        <div class="flex flex-wrap justify-end -mx-3 mb-3">
                            <div date-rangepicker class="flex items-center">
                                <div class="relative">
                                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">
                                </div>
                                <span class="mx-4 text-gray-500">to</span>
                                <div class="relative">
                                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input name="end" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">
                                </div>
                            </div>

                            <input name="initDate" type="text">
                            <input name="finalDate" type="text">

                            <div class="w-full px-3 mb-6 md:mb-5">

                                <button type="submit" class="text-white bg-ccuffs-primary focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                    Imprimir
                                </button>
                                <button data-modal-toggle="entry-report-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="ticket-report-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
        <div class="modal max-w-lg">
            <div class="modal-body">
                <button type="button" class="close-modal-btn" data-modal-toggle="ticket-report-modal">
                    <x-fas-times />
                    <span class="sr-only">Fechar janela</span>
                </button>

                <div class="modal-content">
                    <h3 class="text-lg text-gray-900 text-left mb-5">
                        Relatório de Vendas
                    </h3>

                    <form name="ticket-report-modal" id="ticket-report-modal" class="default-form" method="post">
                        @csrf
                        <div class="flex flex-wrap justify-end -mx-3 mb-3">
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="enrollment_id">Período</label>
                                <select>
                                    <option>Mensal</option>
                                    <option>Semestral</option>
                                    <option>Anual</option>
                                </select>
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">

                                <button type="submit" class="text-white bg-ccuffs-secondary focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                    Imprimir
                                </button>
                                <button data-modal-toggle="ticket-report-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
