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
                            <div date-rangepicker class="flex items-center mb-6">
                                <div class="default-datepicker-field relative">
                                    <div class="icon">
                                        <x-fas-calendar-day />
                                    </div>
                                    <input name="init_date" type="text" required="true" placeholder="Data de ínicio" autocomplete="off">
                                </div>
                                <span class="mx-4 text-gray-500">até</span>
                                <div class="default-datepicker-field relative">
                                    <div class="icon">
                                        <x-fas-calendar-day />
                                    </div>
                                    <input name="final_date" type="text" required="true" placeholder="Data de fim" autocomplete="off">
                                </div>
                            </div>

                            <div class="w-full px-3">
                                <button type="submit" class="default-button bg-ccuffs-primary mx-2">
                                    Imprimir
                                </button>
                                <button data-modal-toggle="entry-report-modal" type="button" class="default-button white mx-2">
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

                    <form name="ticket-report-modal" id="ticket-report-modal" class="default-form" method="post" action="{{ route('web.report.redirect-ticket-report') }}">
                        @csrf
                        <div class="flex flex-wrap justify-end -mx-3 mb-3">
                            <div date-rangepicker class="flex items-center mb-6">
                                <div class="default-datepicker-field relative">
                                    <div class="icon">
                                        <x-fas-calendar-day />
                                    </div>
                                    <input name="init_date" type="text" required="true" placeholder="Data de ínicio" autocomplete="off">
                                </div>
                                <span class="mx-4 text-gray-500">até</span>
                                <div class="default-datepicker-field relative">
                                    <div class="icon">
                                        <x-fas-calendar-day />
                                    </div>
                                    <input name="final_date" type="text" required="true" placeholder="Data de fim" autocomplete="off">
                                </div>
                            </div>
                            <div class="w-full px-3">
                                <button type="submit" class="default-button bg-ccuffs-secondary mx-4">
                                    Imprimir
                                </button>
                                <button data-modal-toggle="ticket-report-modal" type="button" class="default-button white mx-2">
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
