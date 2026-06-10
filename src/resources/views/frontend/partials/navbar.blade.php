@php
    $isHome = request()->routeIs('frontend.home');
@endphp

<header class="header header-6">
    <div class="navbar-area">
        <div class="container">
            <nav class="navbar navbar-expand-lg">

                <a class="navbar-brand simalex-brand" href="{{ route('frontend.home') }}">
                    <span class="simalex-brand-icon">S</span>
                    <span>
                        <strong>SIMALEX</strong>
                        <small>Aspirasi Legislatif Digital</small>
                    </span>
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent6"
                    aria-controls="navbarSupportedContent6"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="toggler-icon"></span>
                    <span class="toggler-icon"></span>
                    <span class="toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent6">
                    <ul id="nav6" class="navbar-nav ms-auto align-items-lg-center">

                        <li class="nav-item">
                            <a
                                class="simalex-nav {{ $isHome ? 'active' : '' }}"
                                href="{{ route('frontend.home') }}"
                                data-slide="0"
                            >
                                Beranda
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}#feature"
                                data-slide="1"
                            >
                                Fitur
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}#about"
                                data-slide="2"
                            >
                                Workflow
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="simalex-nav"
                            href="{{ route('frontend.home') }}#dashboard"
                            data-slide="3">
                                Dashboard
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a
                                class="simalex-nav {{ request()->routeIs('frontend.statistics.index') ? 'active' : '' }}"
                                href="{{ route('frontend.statistics.index') }}"
                            >
                                Statistik
                            </a>
                        </li> --}}


                        <li class="nav-item">
                            <a
                                class="simalex-nav {{ request()->routeIs('frontend.tracking.show') ? 'active' : '' }}"
                                href="{{ route('frontend.home') }}#tracking"
                                data-slide="4"
                            >
                                Cek Aspirasi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}#contact"
                                data-slide="5"
                            >
                                Kontak
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="header-action d-none d-lg-flex align-items-center gap-2">

                    <a href="{{ url('/admin') }}" class="simalex-admin-btn">
                        Masuk Admin
                    </a>
                </div>

            </nav>
        </div>
    </div>
</header>