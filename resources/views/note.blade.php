<div class="row">
    <div class="col">
        <div class="card p-4">
            <div class="row">
                <div class="col">
                    <h4 class="text-info">{{ $note['title'] }}</h4>

                    <small class="text-secondary">
                        <span class="opacity-75 me-2">Criado em:</span>
                        <strong>{{ date('Y-m-d H:i:s', strtotime($note['created_at'])) }}</strong>
                    </small>

                    @if ($note['created_at'] != $note['updated_at'])
                        <small class="text-secondary ms-5">
                            <span class="opacity-75 me-2">Atualizado em:</span>
                            <strong>{{ date('Y-m-d H:i:s', strtotime($note['updated_at'])) }}</strong>
                        </small>
                    @endif
                </div>

                <div class="col text-end">
                    <a href="{{ route('edit', ['id' => Crypt::encrypt($note['id'])]) }}"
                       class="btn btn-outline-secondary btn-sm mx-1" title="Editar">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>

                    {{-- Botão que abre o modal de confirmação --}}
                    <button class="btn btn-outline-danger btn-sm mx-1"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteNoteModal-{{ $note['id'] }}"
                            title="Excluir">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <hr>
            <p class="text-secondary mb-0">{{ $note['text'] }}</p>
        </div>
    </div>
</div>

{{-- Modal de confirmação de exclusão (GET) --}}
<div class="modal fade" id="deleteNoteModal-{{ $note['id'] }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body text-center">
                <p class="fw-semibold mb-1">
                    Tem certeza que deseja excluir a nota
                    <span class="text-primary">{{ $note['title'] }}</span>?
                </p>
                <p class="text-muted small mb-0">Essa ação não poderá ser desfeita.</p>
            </div>

            <div class="modal-footer d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form action="{{ route('delete', ['id' => Crypt::encrypt($note['id'])]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Excluir</button>
                </form>
                
            </div>

        </div>
    </div>
</div>
