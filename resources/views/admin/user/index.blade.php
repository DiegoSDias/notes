@extends('layouts.main_layout')

@section('content')
<div class="container py-4">
    {{-- Cabeçalho --}}
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-2 mt-sm-0">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3">
        <div>
            <h2 class="mb-1">Gestão de Usuários</h2>
            <p class="text-muted mb-0">Visualize, cadastre e gerencie os usuários do sistema.</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mt-2 mt-sm-0">
            + Novo Usuário
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Filtro de busca --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-sm-8 col-lg-10">
                        <label for="search" class="form-label mb-1">Buscar usuário</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Nome, e-mail ou tipo..."
                            class="form-control"
                        >
                    </div>
                    <div class="col-12 col-sm-4 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Buscar</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 d-none d-lg-inline-block">
                            Limpar
                        </a>
                    </div>
                </div>
            </form>

            {{-- Tabela --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Tipo</th>
                            <th>Último login</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-muted">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Padrão</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->last_login ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-outline-primary me-1">
                                        Editar
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal-{{ $user->id }}">
                                        Excluir
                                    </button>

                                    {{-- Modal de confirmação --}}
                                    <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar exclusão</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza que deseja excluir o usuário
                                                    <strong>{{ $user->name }}</strong>?
                                                    <div class="small text-muted mt-2">Essa ação não poderá ser desfeita.</div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nenhum usuário encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação (requer paginate() no controller) --}}
            @if(method_exists($users, 'links'))
                <div class="pt-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
