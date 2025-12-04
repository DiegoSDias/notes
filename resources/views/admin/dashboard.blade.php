@extends('layouts.main_layout')

@section('content')
<style>
    /* Toques sutis de visual sem depender de libs extras */
    .kpi-card { border: 1px solid rgba(0,0,0,.06); }
    .kpi-icon {
        width: 40px; height: 40px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: .65rem;
        background: var(--bs-light);
    }
    .section-title {
        font-size: .95rem; letter-spacing: .04em; text-transform: uppercase;
        color: var(--bs-secondary); margin: .5rem 0 1rem; font-weight: 600;
    }
    .soft-divider { border-top: 1px dashed rgba(0,0,0,.12); margin: 1.25rem 0; }
</style>

<div class="container mt-5">
    @include('top_bar')

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <div>
            <h2 class="mb-1">Painel Administrativo</h2>
            <div class="text-muted">Visão geral do sistema, métricas e atalhos rápidos.</div>
        </div>

        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-people-fill me-1"></i> Usuários
            </a>
            <a href="{{ route('community') }}" class="btn btn-dark">
                <i class="bi bi-stickies-fill me-1"></i> Notas públicas
            </a>
        </div>
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

    @php
        $publicPct  = $totalNotes > 0 ? round(($totalPublicNotes / $totalNotes) * 100) : 0;
        $trashPct   = $totalNotes > 0 ? round(($totalDeletedNotes / $totalNotes) * 100) : 0;
    @endphp

    {{-- KPIs --}}
    <div class="section-title">Visão geral</div>
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="text-muted small">Usuários cadastrados</div>
                        <div class="h3 mb-0">{{ $totalUsers }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card kpi-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <div class="text-muted small">Total de notas</div>
                        <div class="h3 mb-0">{{ $totalNotes }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon"><i class="bi bi-globe"></i></div>
                        <div>
                            <div class="text-muted small">Notas públicas</div>
                            <div class="h3 mb-0">{{ $totalPublicNotes }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Percentual</span><span>{{ $publicPct }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $publicPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="kpi-icon"><i class="bi bi-trash3"></i></div>
                        <div>
                            <div class="text-muted small">Na lixeira</div>
                            <div class="h3 mb-0">{{ $totalDeletedNotes }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Percentual</span><span>{{ $trashPct }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $trashPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="soft-divider"></div>

    {{-- Papéis & Ações --}}
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-semibold">Distribuição de papéis</div>
                        <span class="badge text-bg-light">Acesso</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <div class="text-muted">Admins</div>
                        <div class="fw-semibold">{{ $admins }}</div>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <div class="text-muted">Usuários padrão</div>
                        <div class="fw-semibold">{{ $regulars }}</div>
                    </div>

                    <div class="mt-3 small text-muted">
                        Dica: revise permissões periodicamente.
                    </div>
                </div>
            </div>
        </div>

        {{-- Atalhos bonitinhos --}}
        <div class="col-12 col-lg-8">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="kpi-icon"><i class="bi bi-person-gear"></i></div>
                                <div>
                                    <div class="fw-semibold mb-1">Gerenciar Usuários</div>
                                    <div class="text-muted small">
                                        Criar, editar, definir papéis e remover acessos.
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6">
                    <a href="{{ route('community') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="kpi-icon"><i class="bi bi-stickies"></i></div>
                                <div>
                                    <div class="fw-semibold mb-1">Notas Públicas</div>
                                    <div class="text-muted small">
                                        Revisar, editar e excluir conteúdo da comunidade.
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
