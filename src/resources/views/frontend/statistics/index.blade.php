@extends('frontend.layouts.app')

@section('title', 'Statistik Aspirasi Publik - SIMALEX')
@section('body_class', 'tracking-page')

@section('content')
    <main class="simalex-tracking-page">

        <div class="container">

            <div class="simalex-statistic-hero">

                <div>
                    <div class="simalex-badge">
                        <span></span>
                        Statistik Publik
                    </div>

                    <h1>Transparansi Data Aspirasi</h1>

                    <p>
                        Statistik ini menampilkan ringkasan pengelolaan aspirasi
                        tanpa menampilkan data pribadi konstituen.
                    </p>
                </div>

                <a href="{{ route('frontend.home') }}" class="simalex-primary-btn">
                    Kembali ke Beranda
                </a>

            </div>

            <div class="simalex-stat-grid">

                <div class="simalex-stat-card">
                    <span>Total Aspirasi</span>
                    <strong>{{ $total }}</strong>
                </div>

                <div class="simalex-stat-card">
                    <span>Masuk</span>
                    <strong>{{ $status['masuk'] }}</strong>
                </div>

                <div class="simalex-stat-card">
                    <span>Verifikasi</span>
                    <strong>{{ $status['verifikasi'] }}</strong>
                </div>

                <div class="simalex-stat-card">
                    <span>Tindak Lanjut</span>
                    <strong>{{ $status['tindak_lanjut'] }}</strong>
                </div>

                <div class="simalex-stat-card">
                    <span>Selesai</span>
                    <strong>{{ $status['selesai'] }}</strong>
                </div>

                <div class="simalex-stat-card">
                    <span>Ditolak</span>
                    <strong>{{ $status['ditolak'] }}</strong>
                </div>

            </div>

            <div class="simalex-result-card mt-4">

                <div class="simalex-result-section-title mt-0">
                    Aspirasi Berdasarkan Kategori
                </div>

                <div class="simalex-category-stat-list">

                    @forelse ($categories as $category => $count)

                        <div class="simalex-category-stat-item">

                            <div>
                                <strong>{{ $category ?? 'Tanpa Kategori' }}</strong>
                                <span>{{ $count }} aspirasi</span>
                            </div>

                            <div class="simalex-category-bar">
                                <div
                                    style="width: {{ $total > 0 ? min(100, ($count / $total) * 100) : 0 }}%;"
                                ></div>
                            </div>

                        </div>

                    @empty

                        <p class="text-muted">
                            Belum ada data aspirasi.
                        </p>

                    @endforelse

                </div>

            </div>

        </div>

    </main>
@endsection