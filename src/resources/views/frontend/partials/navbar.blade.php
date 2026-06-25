@php
    $isHome = request()->routeIs('frontend.home');
    $siteLogo = \App\Models\WebsiteImage::where('name', 'Logo Mangihut Sinaga')->first();
@endphp

<header class="header header-6">
    <div class="navbar-area">
        <div class="container">
            <nav class="navbar navbar-expand-lg">

                <a class="navbar-brand simalex-brand mangihut-brand">

                     @if ($siteLogo?->image)
                        <img
                            src="{{ Storage::url($siteLogo->image) }}"
                            alt="Logo Mangihut Sinaga"
                            class="mangihut-navbar-logo"
                        >
                    @else
                        <div class="mangihut-brand-logo">
                            MS
                        </div>
                    @endif

                    <span class="mangihut-brand-text">
                        <strong>Portal Aspirasi</strong>
                        <small>Mangihut Sinaga</small>
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

                <div
                    class="collapse navbar-collapse sub-menu-bar"
                    id="navbarSupportedContent6"
                >

                    <ul
                        id="nav6"
                        class="navbar-nav ms-auto align-items-lg-center"
                    >

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
                                href="{{ route('frontend.home') }}"
                                data-slide="1"
                            >
                                Profil Beliau
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}"
                                data-slide="2"
                            >
                                Statistik Aspirasi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}"
                                data-slide="3"
                            >
                                Workflow
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav {{ request()->routeIs('frontend.tracking.show') ? 'active' : '' }}"
                                href="{{ route('frontend.home') }}"
                                data-slide="4"
                            >
                                Cek Aspirasi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="simalex-nav"
                                href="{{ route('frontend.home') }}"
                                data-slide="5"
                            >
                                Aspirasi & Kontak
                            </a>
                        </li>

                    </ul>

                </div>

                <div
                    class="header-action d-none d-lg-flex align-items-center gap-2"
                >

                    <a
                        href="{{ url('/admin') }}"
                        class="simalex-admin-btn"
                    >
                        Masuk Admin
                    </a>

                </div>

            </nav>
        </div>
    </div>
</header>