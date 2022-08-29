<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    <div class="py-10 sm:px-6 lg:px-8">
        <div class="bg-ccuffs overflow-hidden sm:rounded-lg p-3 sm:p-10">
            <form name="create-user" id="create-user" class="default-form" method="post"
                  action="{{ route('web.room.createOrUpdate') }}">
                @csrf
                <div class="flex flex-col items-center">
                    <div class="w-1/2">
                        <div class="w-full flex flex-wrap -mx-3 mb-3">
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="name">Nome</label>
                                <input id="name" name="name" type="text" required="true" value="{{ $room->name ?? '' }}">
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="description">Descrição</label>
                                <input id="description" name="description" type="text" required="true" value="{{ $room->description ?? '' }}">
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="capacity">Capacidade</label>
                                <input id="capacity" name="capacity" type="text" required="true" value="{{ $room->capacity ?? '' }}">
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="block_id">Bloco</label>
                                @livewire('blocks-search-bar')
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="responsable_id">Responsável</label>
                                @livewire('responsable-search-bar')
                            </div>
                            <div class="w-full px-3 mb-6 md:mb-5">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select appearance-none"
                                        aria-label="Default select example" required="true">
                                    <option value="1">Ativo</option>
                                    <option {{ (isset($room->status_room) and $room->status_room) ? '' : 'selected' }} value="0">Inativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end -mx-3 p-3">
                            <input id="id" name="id" type="hidden" value="{{ $room->id ?? '' }}">
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
