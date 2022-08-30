<div class="relative">

    <select name="block_id" id="block_id" class="form-select appearance-none" required="true">
        @if(!empty($blocks))
            @foreach($blocks as $block)
                <option value="{{$block['id']}}">{{$block['name']}}</option>
            @endforeach
            <option value="none" selected disabled hidden>Selecione um Bloco</option>
        @else
            <option value="false" selected disabled hidden>Cadastre um bloco!</option>
        @endif
    </select>

</div>
