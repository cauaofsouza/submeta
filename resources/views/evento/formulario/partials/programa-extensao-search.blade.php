<label for="programa_extensao" class="col-form-label" style="font-weight: bold">
    Solicitar Vínculo a Programa de Extensão
</label>

<div class="row">
    <div class="col-md-9">
        <input
                type="text"
                id="programa_extensao"
                class="form-control"
                placeholder="Digite o nome do programa"
                autocomplete="off"
        >
    </div>
    <div class="col-md-3">
        <button type="button" id="btnVincularPrograma" class="btn btn-secondary" style="width: 100%;">
            Vincular Programa
        </button>
    </div>
</div>

<input
        type="hidden"
        name="programa_extensao_id"
        id="programa_extensao_id"
>

<div id="resultado-programas" class="list-group"></div>
{{--abaixo ocorre a pesquisa do programa de extensao enquanto o usuario digita--}}
<script>
    $('#programa_extensao').on('keyup', function () {

        let termo = $(this).val();

        if (termo.length < 3) {
            $('#resultado-programas').html('');
            return;
        }

        $.ajax({
            url: '{{ route("programa.buscar") }}',
            type: 'GET',
            data: {
                busca: termo
            },
            success: function (response) {

                let html = '';

                response.forEach(function(programas) {

                    html += `
                    <button
                        type="button"
                        class="list-group-item list-group-item-action item-programa"
                        data-id="${programas.id}"
                        data-nome="${programas.nome}"
                    >
                        ${programas.nome}
                    </button>
                `;
                });

                $('#resultado-programas').html(html);
            }
        });
    });

    $(document).on('click', '.item-programa', function () {

        $('#programa_extensao').val($(this).data('nome'));

        $('#programa_extensao_id').val($(this).data('id'));

        $('#resultado-programas').html('');


        //isso muda a cor ao botao (agora há um programa selecionado)
        $('#btnVincularPrograma')
            .removeClass('btn-secondary')
            .addClass('btn-primary')
            .prop('disabled', false);
    });
    $('#btnVincularPrograma').on('click', function () {

        let programaId = $('#programa_extensao_id').val();

        if (!programaId) {
            alert('Selecione um programa antes de vincular.');
            return;
        }

        $.ajax({
            url: '/programa-extensao/' + programaId + '/solicitarVinculo', // matches $id = programa id
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                trabalho_id: {{ $trabalhoId }},
                button: true
            },
            success: function (response) {
                alert(response.mensagem);
                location.reload();
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Erro ao vincular programa.';
                alert(msg);
            }
        });
    });
</script>