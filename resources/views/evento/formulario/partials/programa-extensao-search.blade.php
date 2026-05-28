<label for="programa_extensao" class="col-form-label" style="font-weight: bold">
    Solicitar Vínculo a Programa de Extensão
</label>

<input
        type="text"
        id="programa_extensao"
        class="form-control"
        placeholder="Digite o nome do programa"
        autocomplete="off"
>

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
            url: '{{ route("evento.buscar") }}',
            type: 'GET',
            data: {
                busca: termo
            },
            success: function (response) {

                let html = '';

                response.forEach(function(programa) {

                    html += `
                    <button
                        type="button"
                        class="list-group-item list-group-item-action item-programa"
                        data-id="${programa.id}"
                        data-nome="${programa.titulo}"
                    >
                        ${programa.titulo}
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
    });
</script>