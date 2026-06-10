@extends('frontend.layouts.app')

@section('title', 'Tracking Aspirasi - SIMALEX')
@section('body_class', 'tracking-page')

@section('content')
    <main class="simalex-tracking-page">
        <div class="container">

            <div class="simalex-tracking-hero">
                <div>
                    <div class="simalex-badge">
                        <span></span>
                        Tracking Aspirasi
                    </div>

                    <h1>Cek Status Aspirasi</h1>

                    <p>
                        Masukkan nomor tiket untuk melihat perkembangan
                        aspirasi, status terakhir, dan riwayat tindak lanjut.
                    </p>
                </div>

                <form action="{{ route('frontend.tracking.show') }}" method="GET" class="simalex-tracking-search">
                    <input
                        type="text"
                        name="ticket_number"
                        value="{{ $ticketNumber }}"
                        placeholder="Contoh: ASP-2026-0001"
                    >

                    <button type="submit">
                        Cek Status
                    </button>
                </form>
            </div>

            @if ($ticketNumber && ! $aspirasi)
                <div class="simalex-result-card simalex-empty-state">
                    <i class="lni lni-warning"></i>

                    <h3>Nomor tiket tidak ditemukan</h3>

                    <p>
                        Pastikan nomor tiket yang Anda masukkan sudah benar.
                    </p>

                    <a href="{{ route('frontend.home') }}#tracking">
                        Kembali ke halaman tracking
                    </a>
                </div>
            @endif

            @if ($aspirasi)
                @php
                    $status = strtolower($aspirasi->status?->nama);

                    $badgeClass = match ($status) {
                        'masuk' => 'status-masuk',
                        'verifikasi' => 'status-verifikasi',
                        'tindak lanjut' => 'status-tindak',
                        'selesai' => 'status-selesai',
                        'ditolak' => 'status-ditolak',
                        default => 'status-masuk',
                    };
                @endphp

                <div class="simalex-result-card">

                    <div class="simalex-result-header">
                        <div>
                            <span class="simalex-ticket-number">
                                {{ $aspirasi->ticket_number }}
                            </span>

                            <h2>
                                {{ $aspirasi->judul }}
                            </h2>
                        </div>

                        <span class="status-badge {{ $badgeClass }}">
                            {{ $aspirasi->status?->nama }}
                        </span>
                    </div>

                    <div class="simalex-result-action-row">
                        <a
                            href="{{ route('frontend.tracking.pdf', $aspirasi) }}"
                            target="_blank"
                            class="simalex-download-pdf-btn btn btn-sm"
                        >
                            <i class="lni lni-download"></i>
                            Unduh PDF Aspirasi
                        </a>
                    </div>

                    <div class="simalex-result-grid">
                        <div class="simalex-result-info">
                            <span>Kategori</span>
                            <strong>{{ $aspirasi->kategoriAspirasi?->nama ?? '-' }}</strong>
                        </div>

                        <div class="simalex-result-info">
                            <span>Prioritas</span>
                            <strong>{{ ucfirst($aspirasi->prioritas ?? '-') }}</strong>
                        </div>

                        <div class="simalex-result-info">
                            <span>Status Saat Ini</span>
                            <strong>{{ $aspirasi->status?->nama ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="simalex-result-progress">
                        <div class="simalex-progress-header">
                            <strong>Progress Aspirasi</strong>
                            <strong>{{ $progress }}%</strong>
                        </div>

                        <div class="simalex-progress-track">
                            <div
                                class="simalex-progress-fill"
                                style="width: {{ $progress }}%;"
                            ></div>
                        </div>
                    </div>

                    <div class="simalex-result-section-title">
                        Timeline Aspirasi
                    </div>

                    <div class="simalex-result-timeline">
                        @forelse ($aspirasi->statusHistories as $history)
                            <div class="simalex-result-timeline-item">
                                <div class="timeline-marker"></div>

                                <div class="timeline-content">
                                    <h5>
                                        {{ $history->old_status ?? '-' }}
                                        →
                                        {{ $history->new_status ?? '-' }}
                                    </h5>

                                    <span>
                                        {{ $history->created_at?->format('d M Y H:i') }}
                                        •
                                        {{ $history->changer?->name ?? 'System' }}
                                    </span>

                                    <p>
                                        {{ $history->catatan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">
                                Belum ada riwayat status.
                            </p>
                        @endforelse
                    </div>

                    <div class="simalex-result-section-title">
                        Dokumen Pendukung
                    </div>

                    <div class="simalex-attachment-list">
                        @forelse ($aspirasi->attachments as $attachment)
                            <div class="simalex-attachment-item">
                                <div class="simalex-attachment-icon">
                                    <i class="lni lni-files"></i>
                                </div>

                                <div class="simalex-attachment-content">
                                    <strong>
                                        {{ $attachment->original_name }}
                                    </strong>

                                    <span>
                                        {{ strtoupper(pathinfo($attachment->original_name, PATHINFO_EXTENSION)) }}
                                        •
                                        {{ number_format(($attachment->file_size ?? 0) / 1024, 1) }} KB
                                    </span>
                                </div>

                                <a
                                    href="{{ asset('storage/' . $attachment->file_path) }}"
                                    target="_blank"
                                    class="simalex-attachment-link"
                                >
                                    Lihat
                                </a>
                            </div>
                        @empty
                            <p class="text-muted">
                                Belum ada dokumen pendukung.
                            </p>
                        @endforelse
                    </div>

                </div>
            @endif

        </div>
    </main>
@endsection