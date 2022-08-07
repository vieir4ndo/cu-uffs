<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />
        <form id="reset-password" onsubmit="onSubmit();">

            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mt-4">
                <x-jet-label for="new_password" value="{{ __('Nova Senha') }}" />
                <x-jet-input id="new_password" class="block mt-1 w-full" type="password" name="new_password" required autocomplete="new_password" />
            </div>
            <div class="mt-4">
                <x-jet-label for="new_password_confirmation" value="{{ __('Confirmação Nova Senha') }}" />
                <x-jet-input id="new_password_confirmation" class="block mt-1 w-full" type="password" name="new_password" required autocomplete="new_password_confirmation" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-jet-button type="submit">
                    {{ __('Alterar Senha') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

<script type="text/javascript">
    function onSubmit() {
        debugger;
            let new_password = $('#new_password').val();
            if ($('#new_password_confirmation').val() !== new_password) {
                return;
            }
            $.ajax({
                url: {{ route('api.v0.auth.resetPassword') }},
                type: 'POST',
                data: {
                    new_password: new_password,
                },
                contentType: 'application/json',
                headers: {
                    "Authorization": "Bearer " + $('#token').val()
                },
                async: false
            });
    }
</script>
