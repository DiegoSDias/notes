@extends('layouts.main_layout')

@section('content')
<div class="container mt-5">
    @include('top_bar')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Editar meu perfil</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Voltar</a>
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

    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ops! Algo deu errado:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        // Usa $user do controller, senão pega da sessão (pode ser array).
        $user = $user ?? session('user');
        $userId = data_get($user, 'id');
    @endphp

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('user.update', ['user' => Crypt::encrypt($user['id'])]) }}">
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', data_get($user, 'name')) }}"
                        required
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- E-mail --}}
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', data_get($user, 'email')) }}"
                        required
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Alterar senha (opcional)</h5>

                {{-- Senha atual --}}
                <div class="mb-3">
                    <label for="current_password" class="form-label">Senha atual</label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        placeholder="Informe sua senha atual para confirmar a alteração">
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Nova senha --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Nova senha</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Deixe em branco para não alterar">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Confirmar nova senha --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Repita a nova senha">
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                </div>
            </form>

            {{-- Linha separadora + ação perigosa --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="text-danger small">Esta ação é permanente e não poderá ser desfeita.</div>
                <button type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteAccountModal">
                    Excluir minha conta
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmação de exclusão da conta --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Excluir conta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <p class="mb-2">
          Tem certeza que deseja <strong>excluir sua conta</strong>?<br>
          Essa ação é permanente e removerá seus dados.
        </p>

        <form id="deleteAccountForm" method="POST" action="{{ route('user.destroy', ['user' => Crypt::encrypt($user['id'])]) }}">
            @csrf
            @method('DELETE')

            {{-- Confirmação de senha (opcional, mas recomendado) --}}
            <div class="mb-3">
                <label for="delete_password" class="form-label">Confirme sua senha</label>
                <input type="password"
                       id="delete_password"
                       name="password"
                       class="form-control"
                       placeholder="Digite sua senha para confirmar">
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="deleteAccountForm" class="btn btn-danger">
          Excluir definitivamente
        </button>
      </div>

    </div>
  </div>
</div>
@endsection
