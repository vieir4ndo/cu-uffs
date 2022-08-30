<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    <div class="py-10 sm:px-6 lg:px-8">
        <div class="bg-ccuffs overflow-hidden sm:rounded-lg p-3 sm:p-10">
            <form name="create-user" id="create-user" class="default-form" method="post"
                  action="{{ route('web.ccr.createOrUpdate') }}">
                @csrf
                <div class="flex flex-col items-center">
                    <div class="w-1/2">
                        <div class="w-full flex flex-wrap -mx-3 mb-3">
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="name">Nome</label>
                                <input id="name" name="name" type="text" required="true" value="{{ $ccr->name ?? '' }}">
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="status_ccr">Status</label>
                                <select name="status_ccr" id="status_ccr" class="form-select appearance-none" required="true">
                                    <option value="1">Ativo</option>
                                    <option {{ (isset($ccr->status_ccr) and $ccr->status_ccr) ? '' : 'selected' }} value="0">Inativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end -mx-3 p-3">
                            <input id="id" name="id" type="hidden" value="{{ $ccr->id ?? '' }}">
                            <button class="default-button bg-ccuffs-primary" type="submit">
                                <div class="flex items-center">
                                    <x-fab-telegram-plane/>
                                    Enviar
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
