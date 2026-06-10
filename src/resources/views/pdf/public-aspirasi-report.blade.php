<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Aspirasi {{ $aspirasi->ticket_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2F80ED;
            padding-bottom: 14px;
            margin-bottom: 24px;
        }

        .header h2 {
            margin: 0;
            color: #1a2340;
        }

        .ticket {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            background: #eef5ff;
            color: #2F80ED;
            font-weight: bold;
            border-radius: 8px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: bold;
            color: #1a2340;
            margin-bottom: 8px;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 7px 8px;
            vertical-align: top;
            border-bottom: 1px solid #edf2f7;
        }

        td.label {
            width: 32%;
            font-weight: bold;
            color: #4b5563;
        }

        .note {
            margin-top: 24px;
            padding: 12px;
            background: #f8fbff;
            border: 1px solid #dbeafe;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Bukti Pengajuan Aspirasi</h2>
        <div>SIMALEX - Sistem Aspirasi Legislatif Digital</div>
        <div class="ticket">{{ $aspirasi->ticket_number }}</div>
    </div>

    <div class="section">
        <div class="section-title">Informasi Aspirasi</div>

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
                <td>{{ $aspirasi->tanggal_kejadian ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi Kejadian</td>
                <td>{{ $aspirasi->lokasi_kejadian ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Isi Aspirasi</div>
        <p>{{ $aspirasi->deskripsi }}</p>
    </div>

    <div class="section">
        <div class="section-title">Riwayat Status</div>

        <table>
            @forelse ($aspirasi->statusHistories as $history)
                <tr>
                    <td class="label">{{ $history->created_at?->format('d M Y H:i') }}</td>
                    <td>
                        {{ $history->old_status ?? '-' }} → {{ $history->new_status ?? '-' }}<br>
                        {{ $history->catatan ?? '-' }}
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
        <div class="section-title">Lampiran</div>
        <p>Jumlah lampiran pendukung: {{ $aspirasi->attachments->count() }}</p>
    </div>

    <div class="note">
        Dokumen ini merupakan bukti pengajuan aspirasi publik melalui SIMALEX.
        Simpan nomor tiket untuk melakukan pengecekan perkembangan aspirasi.
    </div>

</body>
</html>