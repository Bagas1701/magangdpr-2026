<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aspirasi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 6px;
            padding: 6px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            background: #e5e7eb;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ASPIRASI KONSTITUEN</h1>
        <p>SIMALEX - Sistem Informasi Manajemen Aspirasi Legislatif</p>
        <p>Kantor Anggota DPR RI Komisi III - Dapil Sumatera Utara III</p>
    </div>

    <div class="section">
        <div class="section-title">A. Data Konstituen</div>
        <table>
            <tr>
                <th>Nama</th>
                <td>{{ $aspirasi->konstituen?->nama ?? '-' }}</td>
                <th>NIK</th>
                <td>{{ $aspirasi->konstituen?->nik ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kontak</th>
                <td>{{ $aspirasi->konstituen?->kontak ?? '-' }}</td>
                <th>Wilayah</th>
                <td>
                    {{ $aspirasi->konstituen?->kabupaten_kota ?? '-' }},
                    {{ $aspirasi->konstituen?->kecamatan ?? '-' }},
                    {{ $aspirasi->konstituen?->kelurahan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td colspan="3">{{ $aspirasi->konstituen?->alamat ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">B. Data Aspirasi</div>
        <table>
            <tr>
                <th>Nomor Tiket</th>
                <td colspan="3">{{ $aspirasi->ticket_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td colspan="3">{{ $aspirasi->judul }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $aspirasi->kategoriAspirasi?->nama ?? '-' }}</td>
                <th>Status</th>
                <td><span class="badge">{{ $aspirasi->status?->nama ?? '-' }}</span></td>
            </tr>
            <tr>
                <th>Prioritas</th>
                <td>{{ ucfirst($aspirasi->prioritas ?? '-') }}</td>
                <th>Tanggal Kejadian</th>
                <td>{{ $aspirasi->tanggal_kejadian ? \Carbon\Carbon::parse($aspirasi->tanggal_kejadian)->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Lokasi Kejadian</th>
                <td colspan="3">{{ $aspirasi->lokasi_kejadian ?? '-' }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td colspan="3">{{ $aspirasi->deskripsi ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">C. Keputusan Anggota Dewan</div>
        <table>
            <tr>
                <th>Status Approval</th>
                <td>{{ ucfirst($aspirasi->approval_status ?? 'pending') }}</td>
                <th>Nomor Disposisi</th>
                <td>{{ $aspirasi->nomor_disposisi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Keputusan</th>
                <td>{{ $jenisKeputusan }}</td>
                <th>Diproses Oleh</th>
                <td>{{ $aspirasi->approver?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Keputusan</th>
                <td colspan="3">{{ $aspirasi->approved_at?->format('d M Y H:i') ?? '-' }}</td>
            </tr>
            @if (in_array($aspirasi->approval_status, ['revision', 'rejected'], true))
                <tr>
                    <th>Catatan</th>
                    <td colspan="3">{{ $aspirasi->approval_note ?? '-' }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">D. Kajian / Rekomendasi</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Catatan</th>
                    <th>Ditulis Oleh</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aspirasi->notes as $note)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $note->catatan }}</td>
                        <td>{{ $note->user?->name ?? '-' }}</td>
                        <td>{{ $note->created_at?->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada kajian/rekomendasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">E. Daftar Lampiran</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahap</th>
                    <th>Jenis</th>
                    <th>Nama File</th>
                    <th>Diunggah Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aspirasi->attachments as $attachment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attachment->stage === 'tindak_lanjut' ? 'File Tindak Lanjut' : 'File Awal' }}</td>
                        <td>{{ \App\Models\Attachment::categoryOptions()[$attachment->attachment_category] ?? '-' }}</td>
                        <td>{{ $attachment->original_name }}</td>
                        <td>{{ $attachment->uploader?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada lampiran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">F. Riwayat Status</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Catatan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aspirasi->statusHistories as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $history->old_status }}</td>
                        <td>{{ $history->new_status }}</td>
                        <td>{{ $history->catatan ?? '-' }}</td>
                        <td>{{ $history->created_at?->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada riwayat status.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>