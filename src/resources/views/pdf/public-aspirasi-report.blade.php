<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Aspirasi {{ $aspirasi->ticket_number }}</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #2F80ED; padding-bottom: 14px; margin-bottom: 24px; }
        .header h2 { margin: 0; color: #1a2340; }
        .header div { margin-top: 3px; }
        .ticket { display: inline-block; margin-top: 8px; padding: 6px 12px; background: #eef5ff; color: #2F80ED; font-weight: bold; border-radius: 8px; }
        .section { margin-bottom: 18px; }
        .section-title { font-weight: bold; color: #1a2340; margin-bottom: 8px; border-bottom: 1px solid #dbeafe; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 7px 8px; vertical-align: top; border-bottom: 1px solid #edf2f7; }
        td.label { width: 32%; font-weight: bold; color: #4b5563; }
        .note { margin-top: 24px; padding: 12px; background: #f8fbff; border: 1px solid #dbeafe; font-size: 11px; }
        .muted { color: #6b7280; font-size: 11px; }
    </style>
</head>
<body>

    @php
        $dokumenAwalCount = $aspirasi->attachments
            ->where('stage', '!=', 'tindak_lanjut')
            ->count();

        $dokumenTindakLanjutCount = $aspirasi->attachments
            ->where('stage', 'tindak_lanjut')
            ->count();
    @endphp

    <div class="header">
        <h2>Bukti Pengajuan Aspirasi</h2>
        <div>Portal Aspirasi Mangihut Sinaga</div>
        <div>Anggota DPR RI Komisi III - Daerah Pemilihan Sumatera Utara III</div>
        <div class="ticket">{{ $aspirasi->ticket_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">A. Informasi Aspirasi</div>

        <table>
            <tr>
                <td class="label">Judul</td>
                <td>{{ $aspirasi->judul }}</td>
            </tr>
            <tr>
                <td class="label">Kategori</td>
                <td>{{ $aspirasi->kategoriAspirasi?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status Saat Ini</td>
                <td>{{ $aspirasi->status?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Prioritas</td>
                <td>{{ ucfirst($aspirasi->prioritas ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengajuan</td>
                <td>{{ $aspirasi->created_at?->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Kejadian</td>
                <td>
                    {{ $aspirasi->tanggal_kejadian ? \Carbon\Carbon::parse($aspirasi->tanggal_kejadian)->format('d M Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Lokasi Kejadian</td>
                <td>{{ $aspirasi->lokasi_kejadian ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">B. Isi Aspirasi</div>
        <p>{{ $aspirasi->deskripsi ?? '-' }}</p>
    </div>

    <div class="section">
        <div class="section-title">C. Riwayat Status</div>

        <table>
            @forelse ($aspirasi->statusHistories as $history)
                <tr>
                    <td class="label">{{ $history->created_at?->format('d M Y H:i') }}</td>
                    <td>
                        <strong>{{ $history->new_status ?? '-' }}</strong><br>

                        @switch($history->new_status)
                            @case('Masuk')
                                Aspirasi telah diterima oleh sistem.
                                @break

                            @case('Verifikasi')
                                Aspirasi sedang dalam proses verifikasi data dan kelengkapan dokumen.
                                @break

                            @case('Tindak Lanjut')
                                Aspirasi sedang ditindaklanjuti oleh tim pendukung Anggota DPR RI.
                                @break

                            @case('Menunggu Persetujuan')
                                Aspirasi sedang menunggu persetujuan atau arahan dari Anggota Dewan.
                                @break

                            @case('Selesai')
                                Aspirasi telah selesai ditindaklanjuti.
                                @break

                            @case('Ditolak')
                                Aspirasi tidak dapat diproses lebih lanjut berdasarkan hasil verifikasi.
                                @break

                            @default
                                Perkembangan aspirasi telah diperbarui.
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">Belum ada riwayat status.</td>
                </tr>
            @endforelse
        </table>
    </div>

    <div class="section">
        <div class="section-title">D. Dokumen Pendukung</div>

        <table>
            <tr>
                <td class="label">Dokumen Pengajuan</td>
                <td>{{ $dokumenAwalCount }} file telah diterima oleh sistem.</td>
            </tr>
            <tr>
                <td class="label">Dokumen Tindak Lanjut Internal</td>
                <td>{{ $dokumenTindakLanjutCount }} file digunakan untuk proses internal penanganan aspirasi.</td>
            </tr>
        </table>

        <p class="muted">
            Nama file dan dokumen internal tidak ditampilkan pada bukti publik untuk menjaga keamanan serta kerahasiaan data.
        </p>
    </div>

    <div class="note">
        Dokumen ini merupakan bukti pengajuan dan pelacakan aspirasi melalui Portal Aspirasi Mangihut Sinaga.
        Simpan nomor tiket untuk melakukan pengecekan perkembangan aspirasi. Dokumen internal hanya dapat diakses oleh petugas yang berwenang.
    </div>

</body>
</html>