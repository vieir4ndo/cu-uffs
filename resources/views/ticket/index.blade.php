<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Fichas de Refeição') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (Auth::user()->type == 5)
            <div class="flex flex-wrap mb-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                    <div class="default-card bg-ccuffs-primary md:mx-15">
                        <a href="javascript:;" data-modal-toggle="insert-ticket-modal">
                            <div class="card-content">
                                <h3 class="card-title mb-3 md:mr-5">
                                    Registrar venda para </br> Estudante/Servidor
                                    </h5>
                                    <x-fas-chevron-right />
                            </div>
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                    <div class="default-card bg-ccuffs-secondary md:mx-15">
                        <a href="javascript:;" data-modal-toggle="register-visitor-ticket-modal">
                            <div class="card-content">
                                <h3 class="card-title mb-3 md:mr-5">
                                    Registrar venda para </br> Visitantes
                                    </h5>
                                    <x-fas-chevron-right />
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div id="insert-ticket-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
                <div class="modal max-w-lg">
                    <div class="modal-body">
                        <button type="button" class="close-modal-btn" data-modal-toggle="insert-ticket-modal">
                            <x-fas-times />
                            <span class="sr-only">Fechar janela</span>
                        </button>

                        <div class="modal-content">
                            <h3 class="text-lg text-gray-900 text-left mb-5">
                                Registrar venda para Estudante/Servidor
                            </h3>

                            <form name="insert-ticket-form" id="insert-ticket-form" class="default-form white" method="post" >
                                @csrf
                                <div class="flex flex-wrap justify-end -mx-3 mb-3">
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                                        <label for="enrollment_id">Matrícula/SIAPE</label>
                                        <input id="enrollment" name="enrollment_id" type="text" required="true">
                                    </div>
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
                                        <label for="amount">Quantidade de Tickets</label>
                                        <input id="amount" name="amount" type="number" min="1" required="true">
                                    </div>

                                    <button type="submit" class="text-white bg-ccuffs-primary focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                        Registrar
                                    </button>
                                    <button data-modal-toggle="insert-ticket-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="register-visitor-ticket-modal" tabindex="-1" class="default-modal hidden overflow-y-auto h-modal">
                <div class="modal max-w-lg">
                    <div class="modal-body">
                        <button type="button" class="close-modal-btn" data-modal-toggle="register-visitor-ticket-modal">
                            <x-fas-times />
                            <span class="sr-only">Fechar janela</span>
                        </button>

                        <div class="modal-content">
                            <h3 class="text-lg text-gray-900 text-left mb-5">
                                Registrar venda para Visitante
                            </h3>

                            <form name="register-visitor-ticket-form" id="register-visitor-ticket-form" class="default-form" method="post" >
                                @csrf
                                <div class="flex flex-wrap justify-end -mx-3 mb-3">
                                    <button type="submit" class="text-white bg-ccuffs-secondary focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                        Registrar
                                    </button>
                                    <button data-modal-toggle="register-visitor-ticket-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

</x-app-layout>
