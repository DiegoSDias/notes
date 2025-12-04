@extends('layouts.main_layout')

@section('content')
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Cadastrar Novo Usuário</h2>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    {{-- Mensagens de sucesso/erro --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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


    <div class="card shadow-sm">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('registerSubmit') }}">
                @csrf

                {{-- Nome --}}
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="form-control">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="form-control">
                </div>

                {{-- Senha --}}
                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password"
                           name="password"
                           required
                           class="form-control">
                </div>

                {{-- Confirmar senha --}}
                <div class="mb-4">
                    <label class="form-label">Confirmar Senha</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="form-control">
                </div>

                {{-- Botão salvar --}}
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-4">
                        <i class="fa-solid fa-check me-1"></i> Salvar Usuário
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
