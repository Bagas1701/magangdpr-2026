@extends('frontend.layouts.app')

@section('title', 'Aspirasi Berhasil Dikirim - SIMALEX')
@section('body_class', 'tracking-page')

@section('content')

<main class="simalex-tracking-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="simalex-result-card text-center">

                    <div class="simalex-success-icon">
                        <i class="lni lni-checkmark-circle"></i>
                    </div>

                    <span class="simalex-badge">
                        Aspirasi Berhasil Dikirim
                    </span>

                    <h2 class="mt-4">
                        Terima Kasih
                    </h2>

                    <p class="mb-4">
                        Aspirasi Anda telah berhasil dicatat ke dalam
                        sistem SIMALEX dan sedang menunggu proses
                        verifikasi oleh petugas.
                    </p>

                    <div class="simalex-ticket-box">

                        <small>Nomor Tiket Aspirasi</small>

                        <h3>
                            {{ $aspirasi->ticket_number }}
                        </h3>

                    </div>

                    <div class="mt-4">

                        <a
                            href="{{ route('frontend.tracking.show', [
                                'ticket_number' => $aspirasi->ticket_number
                            ]) }}"
                            class="simalex-primary-btn"
                        >
                            <i class="lni lni-search-alt"></i>
                            Cek Status Aspirasi
                        </a>

                    </div>

                    <div class="mt-4">

                        <small class="text-muted">
                            Simpan nomor tiket ini untuk melakukan
                            pelacakan perkembangan aspirasi di kemudian hari.
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

@endsection