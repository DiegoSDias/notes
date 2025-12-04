@extends('layouts.main_layout')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col">

                @include('top_bar')

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="mb-0">Comunidade</h2>
                    {{-- opcional: botão para criar nota pública --}}
                    {{-- <a href="{{ route('admin.notes.create') }}" class="btn btn-primary">+ Nova Nota</a> --}}
                </div>
                <p class="text-muted">
                    Aqui aparecem as notas que os usuários marcaram como públicas.
                </p>

                <hr class="my-4">

                @if ($notes->isEmpty())
                    <p class="text-center text-secondary opacity-50 mt-5">
                        Ainda não há notas públicas na comunidade.
                    </p>
                @else
                    @foreach ($notes as $note)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="me-3">
                                        <strong class="d-block">{{ $note->title }}</strong>
                                        <small class="text-muted">
                                            por {{ $note->user->email ?? 'Usuário' }} — {{ $note->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    @php
                                        // Se seu projeto usa sessão para papel (role):
                                        $isAdmin = strtolower((string) session('user.role')) === 'admin';
                                    @endphp

                                    @if ($isAdmin)
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('edit', ['id' => Crypt::encrypt($note['id'])]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Editar
                                            </a>

                                            <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteNoteModal-{{ $note->id }}">
                                                Excluir
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <p class="mb-0">{{ $note->text }}</p>
                            </div>
                        </div>

                        {{-- Modal de exclusão --}}
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
                    @endforeach

                    {{-- Paginação (se usar paginate() no controller) --}}
                    @if(method_exists($notes, 'links'))
                        <div class="mt-3">
                            {{ $notes->withQueryString()->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection
