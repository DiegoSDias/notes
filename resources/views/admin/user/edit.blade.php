@extends('layouts.main_layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            {{-- Cabeçalho --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Editar Usuário</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    Voltar
                </a>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Erros de validação --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-semibold mb-2">Ops! Algo deu errado.</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Nome --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                            >
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- E-mail --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tipo de usuário --}}
                        <div class="mb-3">
                            <label for="role" class="form-label">Tipo de usuário</label>
                            <select
                                id="role"
                                name="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required
                            >
                                <option value="">Selecione...</option>
                                <option value="admin" {{ old('role', $user->role ?? null) === 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="user" {{ old('role', $user->role ?? null) === 'user' ? 'selected' : '' }}>
                                    Padrão
                                </option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Nova senha (opcional) --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova senha (opcional)</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Deixe em branco para manter a atual"
                            >
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Confirmar nova senha --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="form-control"
                            >
                        </div>

                        {{-- Ações --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Atualizar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
