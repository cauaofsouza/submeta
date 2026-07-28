<!-- Modal -->
<div class="modal fade" id="exampleModal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{ $id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel{{ $id }}">Deletar {{ $tipoLabel ?? 'item' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja deletar {{ $tipoLabel ?? 'o item' }} [{{ $nome }}]?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="POST" action="{{ route($rota, $id) }}" class="text-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">
                        Deletar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>