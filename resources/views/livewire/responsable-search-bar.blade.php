<div class="relative">

    <select name="responsable_id" id="responsable_id" class="form-select appearance-none" required="true">
        @if(!empty($responsables))
            @foreach($responsables as $responsable)
                <option value="{{$responsable['id']}}">{{$responsable['name']}}</option>
            @endforeach
                <option value="none" selected disabled hidden>Selecione um Responsável</option>
        @else
            <option value="false" selected disabled hidden>Cadastre um Responsável!</option>
        @endif
    </select>

</div>
