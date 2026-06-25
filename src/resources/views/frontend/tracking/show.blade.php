@extends('frontend.layouts.app')

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

                    <a href="{{ route('frontend.home') }}" class="simalex-back-track-btn">
                        ← Kembali ke Beranda
                    </a>

                    <h1>Cek Status Aspirasi</h1>

                    <p>
                        Masukkan nomor tiket untuk melihat status,
                        progress, dan ringkasan perkembangan aspirasi.
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
                        'menunggu persetujuan' => 'status-verifikasi',
                        'selesai' => 'status-selesai',
                        'ditolak' => 'status-ditolak',
                        default => 'status-masuk',
                    };

                    $dokumenAwalCount = $aspirasi->attachments
                        ->where('stage', '!=', 'tindak_lanjut')
                        ->count();

                    $dokumenTindakLanjutCount = $aspirasi->attachments
                        ->where('stage', 'tindak_lanjut')
                        ->count();

                    $publicStatusMessage = match ($aspirasi->status?->nama) {
                        'Masuk' => 'Aspirasi telah diterima oleh sistem dan menunggu proses verifikasi awal.',
                        'Verifikasi' => 'Aspirasi sedang dalam proses verifikasi data dan kelengkapan dokumen.',
                        'Tindak Lanjut' => 'Aspirasi sedang ditindaklanjuti oleh tim pendukung Anggota DPR RI dan dilakukan koordinasi dengan pihak terkait sesuai kebutuhan penanganan.',
                        'Menunggu Persetujuan' => 'Aspirasi telah ditindaklanjuti dan sedang menunggu persetujuan atau arahan dari Anggota Dewan.',
                        'Selesai' => 'Aspirasi telah selesai ditindaklanjuti. Informasi hasil penanganan akan disampaikan kepada pelapor melalui kontak yang tercatat.',
                        'Ditolak' => 'Aspirasi tidak dapat diproses lebih lanjut berdasarkan hasil verifikasi atau pertimbangan penanganan.',
                        default => 'Perkembangan aspirasi telah diperbarui oleh sistem.',
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
                            Unduh Bukti Aspirasi
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
                        Perkembangan Aspirasi
                    </div>

                    <div class="simalex-public-update-card">
                        <i class="lni lni-information"></i>

                        <div>
                            <strong>{{ $aspirasi->status?->nama ?? 'Status diperbarui' }}</strong>
                            <p>{{ $publicStatusMessage }}</p>
                        </div>
                    </div>

                    <div class="simalex-result-section-title">
                        Timeline Aspirasi
                    </div>

                    <div class="simalex-result-timeline">
                        @forelse ($aspirasi->statusHistories as $history)
                            @php
                                $historyMessage = match ($history->new_status) {
                                    'Masuk' => 'Aspirasi telah diterima oleh sistem.',
                                    'Verifikasi' => 'Aspirasi sedang dalam proses verifikasi data dan kelengkapan dokumen.',
                                    'Tindak Lanjut' => 'Aspirasi sedang memasuki tahap tindak lanjut oleh tim pendukung Anggota DPR RI.',
                                    'Menunggu Persetujuan' => 'Aspirasi sedang menunggu persetujuan atau arahan dari Anggota Dewan.',
                                    'Selesai' => 'Aspirasi telah selesai ditindaklanjuti.',
                                    'Ditolak' => 'Aspirasi tidak dapat diproses lebih lanjut berdasarkan hasil verifikasi.',
                                    default => 'Perkembangan aspirasi telah diperbarui.',
                                };
                            @endphp

                            <div class="simalex-result-timeline-item">
                                <div class="timeline-marker"></div>

                                <div class="timeline-content">
                                    <h5>
                                        {{ $history->new_status ?? '-' }}
                                    </h5>

                                    <span>
                                        {{ $history->created_at?->format('d M Y H:i') }}
                                    </span>

                                    <p>
                                        {{ $historyMessage }}
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

                    <div class="simalex-public-doc-summary">

                        <div class="simalex-public-doc-item">
                            <div class="simalex-public-doc-icon">
                                <i class="lni lni-files"></i>
                            </div>

                            <div>
                                <strong>Dokumen pengajuan diterima</strong>
                                <span>{{ $dokumenAwalCount }} file telah tersimpan dalam sistem.</span>
                            </div>
                        </div>

                        <div class="simalex-public-doc-item locked">
                            <div class="simalex-public-doc-icon">
                                <i class="lni lni-lock"></i>
                            </div>

                            <div>
                                <strong>Dokumen tindak lanjut internal</strong>
                                <span>{{ $dokumenTindakLanjutCount }} file digunakan untuk proses internal penanganan aspirasi.</span>
                            </div>
                        </div>

                        <div class="simalex-public-doc-note">
                            Dokumen tindak lanjut, kajian, dan lampiran internal hanya dapat diakses oleh petugas yang berwenang untuk menjaga keamanan serta kerahasiaan data.
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </main>

    @push('styles')
<style>
    .header {
        display: none !important;
    }

    .simalex-shell,
    main {
        padding-top: 50px !important;
        margin-top: 0px !important;
    }

    .simalex-public-update-card {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 18px;
        border-radius: 20px;
        background: #f8fbff;
        border: 1px solid #dbeafe;
        margin-bottom: 24px;
    }

    .simalex-public-update-card i {
        font-size: 22px;
        color: #2F80ED;
        margin-top: 2px;
    }

    .simalex-public-update-card strong {
        display: block;
        margin-bottom: 6px;
        color: #1a2340;
    }

    .simalex-public-update-card p {
        margin: 0;
        color: #4b5563;
        line-height: 1.7;
    }

    .simalex-public-doc-summary {
        display: grid;
        gap: 14px;
    }

    .simalex-public-doc-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        padding: 16px;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
    }

    .simalex-public-doc-item.locked {
        background: #f9fafb;
    }

    .simalex-public-doc-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: #eef5ff;
        color: #2F80ED;
        flex-shrink: 0;
    }

    .simalex-public-doc-item.locked .simalex-public-doc-icon {
        background: #f3f4f6;
        color: #6b7280;
    }

    .simalex-public-doc-item strong {
        display: block;
        color: #1a2340;
        margin-bottom: 4px;
    }

    .simalex-public-doc-item span {
        color: #6b7280;
        font-size: 14px;
    }

    .simalex-public-doc-note {
        padding: 14px 16px;
        border-radius: 16px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        font-size: 14px;
        line-height: 1.6;
    }
</style>
@endpush

@endsection