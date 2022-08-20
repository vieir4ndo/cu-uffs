<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-200 leading-tight">
      {{ $title }}
    </h2>
  </x-slot>
    <div class="py-10 sm:px-6 lg:px-8">
      <div class="bg-ccuffs overflow-hidden sm:rounded-lg p-3 sm:p-10">
        <form name="create-user" id="create-user" class="default-form" method="post" action="{{ route('web.user.form') }}">
          @csrf

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="name">Nome</label>
              <input id="name" name="name" type="text" required="true">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="type">Tipo</label>
              <select name="type" id="type" class="form-select appearance-none" aria-label="Default select example" required="true">
                <option value="" disabled selected >Selecione o tipo do usuário</option>
                <option value="4">Caixa do Restaurante</option>
              </select>
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="uid">Login</label>
              <input id="uid" name="uid" type="text" required="true">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="password">Senha</label>
              <input id="password" name="password" type="password" required="true">
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="email">E-mail</label>
              <input id="email" name="email" type="email" required="true">
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="birth_date">Data de Nascimento</label>
              <div class="default-datepicker-field relative">
                <div class="icon">
                  <x-fas-calendar-day />
                </div>
                <input datepicker id="date" name="birth_date" type="text" required="true">
              </div>
            </div>
          </div>

          <div class="flex flex-wrap -mx-3 mb-3">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-5">
              <label for="profile_photo">Foto de Perfil</label>
              <input id="profile_photo" name="profile_photo" type="file" accept="image/*">
            </div>
          </div>

          <!-- PROFILE PHOTO -->

          <div class="flex justify-end -mx-3 p-3">
            <button class="default-button bg-ccuffs-primary" type="submit">
              <div class="flex items-center">
                <x-fab-telegram-plane />
                Enviar
              </div>
            </button>
          </div>
        </form>
      </div>
    </div>
</x-app-layout>

<script type="text/javascript">
  var field = document.getElementById('profile_photo');

  field.addEventListener("change", (e) => {
    debugger
    var files = e.target.files[0];
  })
</script>