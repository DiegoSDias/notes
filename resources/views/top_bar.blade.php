                <div class="row mb-3 align-items-center">
                    <div class="col">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Notes logo">
                        </a>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end align-items-center">
                            @php
                                // Se seu projeto usa sessão para papel (role):
                                $isAdmin = strtolower((string) session('user.role')) === 'admin';
                            @endphp
                            @if($isAdmin)
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-warning me-3">
                                    <i class="fa-solid fa-shield-halved me-1"></i>Admin
                                </a>
                            @endif
                            @if (request()->routeIs('community'))
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3">
                                <i class="fa-solid fa-note-sticky me-1"></i>Minhas Notas
                                </a>
                            @else
                                <a href="{{ route('community') }}" class="btn btn-outline-secondary me-3">
                                    <i class="fa-solid fa-users me-1"></i>Comunidade
                                </a>
                            @endif

                            <span class="me-3">
                                <a href="{{ route('user.edit', ['user' => Crypt::encrypt(session('user.id'))]) }}" class="btn btn-outline-secondary me-3">
                                    <i class="fa-solid fa-user text-secondary me-1"></i>{{ session('user.email') }}
                                </a>
                            </span>

                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary px-3">
                                Logout<i class="fa-solid fa-arrow-right-from-bracket ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <hr>