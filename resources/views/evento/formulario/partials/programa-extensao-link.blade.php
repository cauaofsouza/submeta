
    <div class="form-group col-md-12" style="margin-top: 10px">

        <label class="col-form-label" style="font-weight: bold">
            Programa de Extensão Vinculado
        </label>

        <div class="list-group">

            <a
                    href="{{ route('programa.visualizar', ['id' => $projeto->programaDeExtensao->id]) }}"
                    target="_blank"
                    class="list-group-item list-group-item-action"
            >
                {{ $projeto->programaDeExtensao->nome }}

                @if($trabalho->programa_extensao_status)
                    <span class="badge badge-secondary float-right">
                    {{ ucfirst($projeto->programa_extensao_status) }}
                </span>
                @endif
            </a>

        </div>

    </div>


