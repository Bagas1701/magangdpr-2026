@extends('frontend.layouts.app')

@section('title', 'Kebijakan Privasi - SIMALEX')
@section('body_class', 'tracking-page')

@section('content')

<main class="simalex-tracking-page">

    <div class="container">

        <div class="simalex-result-card">

            <div class="text-center mb-5">

                <div class="simalex-badge">
                    <span></span>
                    Kebijakan Privasi
                </div>

                <h2 class="mt-4">
                    Perlindungan Data Pengguna SIMALEX
                </h2>

                <p class="text-muted">
                    SIMALEX berkomitmen untuk menjaga kerahasiaan dan keamanan
                    data pribadi masyarakat yang disampaikan melalui sistem aspirasi.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    1. Pengumpulan Data
                </div>

                <p>
                    SIMALEX mengumpulkan informasi yang diberikan secara sukarela
                    oleh pengguna, seperti nama, NIK, nomor kontak, isi aspirasi,
                    lokasi kejadian, dan lampiran pendukung yang diperlukan untuk
                    proses verifikasi dan tindak lanjut aspirasi.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    2. Penggunaan Data
                </div>

                <p>
                    Data yang dikirimkan hanya digunakan untuk keperluan
                    pengelolaan aspirasi, verifikasi laporan, koordinasi tindak lanjut,
                    dan penyusunan statistik layanan secara agregat.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    3. Kerahasiaan Informasi
                </div>

                <p>
                    Data pribadi pengguna tidak dipublikasikan kepada masyarakat.
                    Informasi hanya dapat diakses oleh petugas yang memiliki
                    kewenangan sesuai tugas dan tanggung jawabnya.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    4. Keamanan Data
                </div>

                <p>
                    SIMALEX menerapkan mekanisme keamanan sistem seperti validasi
                    data, pembatasan akses berbasis peran (RBAC), HTTPS,
                    serta pengelolaan dokumen secara terstruktur untuk menjaga
                    keamanan informasi yang tersimpan.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    5. Hak Pengguna
                </div>

                <p>
                    Pengguna berhak mengetahui status aspirasi yang diajukan
                    melalui nomor tiket yang diberikan oleh sistem setelah
                    proses pengiriman berhasil dilakukan.
                </p>

            </div>

            <div class="simalex-result-section">

                <div class="simalex-result-section-title">
                    6. Persetujuan Pengguna
                </div>

                <p>
                    Dengan mengirimkan aspirasi melalui SIMALEX, pengguna dianggap
                    telah membaca, memahami, dan menyetujui kebijakan privasi ini.
                </p>

            </div>

        </div>

    </div>

</main>

@endsection