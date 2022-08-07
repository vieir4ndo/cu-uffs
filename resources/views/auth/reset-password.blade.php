<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form name="reset-password" method="POST" action="{{route('web.auth.resetPassword')}}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mt-4">
                <x-jet-label for="new_password" value="{{ __('Nova Senha') }}" />
                <x-jet-input id="new_password" class="block mt-1 w-full" type="password" name="new_password" required autocomplete="new_password" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-jet-button type="submit">
                    {{ __('Alterar Senha') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
