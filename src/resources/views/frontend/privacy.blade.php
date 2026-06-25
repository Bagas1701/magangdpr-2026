@extends('frontend.layouts.blank')

@section('content')
<section class="privacy-page">
    <div class="privacy-card">

        <div class="privacy-header">

            <div class="privacy-badge">
                Kebijakan Privasi
            </div>

            <a href="{{ route('frontend.home') }}" class="privacy-back">
                ← Kembali ke Beranda
            </a>

        </div>

        <h1>Perlindungan Data Pengguna Portal Aspirasi Mangihut Sinaga</h1>

        <p class="privacy-subtitle">
            Portal Aspirasi Mangihut Sinaga berkomitmen menjaga kerahasiaan dan keamanan data masyarakat
            yang disampaikan melalui formulir aspirasi.
        </p>

        <h3>1. Pengumpulan Data</h3>
        <p>
            Sistem mengumpulkan data yang diberikan secara sukarela oleh pengguna, seperti nama,
            NIK, nomor kontak, alamat, isi aspirasi, lokasi kejadian, serta lampiran pendukung
            yang diperlukan untuk proses verifikasi dan tindak lanjut.
        </p>

        <h3>2. Penggunaan Data</h3>
        <p>
            Data hanya digunakan untuk pencatatan, verifikasi, pengelolaan aspirasi,
            koordinasi tindak lanjut, dan penyusunan statistik layanan secara agregat.
        </p>

        <h3>3. Kerahasiaan Informasi</h3>
        <p>
            Data pribadi pengguna tidak dipublikasikan kepada masyarakat umum. Informasi hanya
            dapat diakses oleh petugas yang memiliki kewenangan sesuai tugas dan tanggung jawabnya.
        </p>

        <h3>4. Keamanan Data</h3>
        <p>
            Sistem menerapkan pembatasan akses berbasis peran, validasi data, pengelolaan dokumen,
            serta mekanisme keamanan sistem untuk menjaga informasi yang tersimpan.
        </p>

        <h3>5. Hak Pengguna</h3>
        <p>
            Pengguna dapat mengetahui status aspirasi melalui nomor tiket yang diberikan setelah
            pengiriman aspirasi berhasil dilakukan.
        </p>

        <h3>6. Persetujuan Pengguna</h3>
        <p>
            Dengan mengirimkan aspirasi melalui portal ini, pengguna dianggap telah membaca,
            memahami, dan menyetujui kebijakan privasi ini.
        </p>

    </div>
</section>
@endsection