@php
    $mangihutPhoto = \App\Models\WebsiteImage::where('name', 'Foto Mangihut')->first();
@endphp

<section id="hero" class="simalex-panel">

    <div class="simalex-hero-wrapper">

        <div class="container">

            <div class="row align-items-center gy-5">

                <div class="col-lg-5">

                    <div class="hero-content-wrapper">

                        <div class="simalex-badge wow fadeInUp">
                            <span></span>
                            Portal Aspirasi Konstituen
                        </div>

                        <h1 class="simalex-hero-title wow fadeInUp">
                            Suara Anda,
                            Aspirasi Kita,

                            <span>
                                Perubahan Nyata
                            </span>
                        </h1>

                        <p class="simalex-hero-desc wow fadeInUp">
                            Portal Aspirasi Mangihut Sinaga hadir sebagai media digital
                            untuk menampung, memantau, dan mengelola aspirasi masyarakat
                            secara transparan, cepat, dan terdokumentasi.
                        </p>

                        <div class="simalex-hero-action wow fadeInUp">

                            <a
                                href="{{ route('frontend.home') }}#contact"
                                data-slide="5"
                                class="simalex-primary-btn"
                            >
                                Kirim Aspirasi
                            </a>

                            <a
                                href="{{ route('frontend.home') }}#dashboard"
                                data-slide="2"
                                class="simalex-outline-btn"
                            >
                                Lihat Statistik
                            </a>

                        </div>

                        <div class="simalex-mini-stats wow fadeInUp">

                            <div class="simalex-mini-stat">
                                <strong>24/7</strong>
                                <span>Layanan Digital</span>
                            </div>

                            <div class="simalex-mini-stat">
                                <strong>Realtime</strong>
                                <span>Status Aspirasi</span>
                            </div>

                            <div class="simalex-mini-stat">
                                <strong>Transparan</strong>
                                <span>Pelayanan Publik</span>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-7">

                    <div class="simalex-hero-visual wow fadeInUp">

                        <div class="mangihut-hero-photo-card">

                            @if ($mangihutPhoto?->image)
                                <img
                                    src="{{ Storage::url($mangihutPhoto->image) }}"
                                    alt="Mangihut Sinaga"
                                    class="mangihut-hero-photo"
                                >
                            @else
                                <div class="mangihut-photo-placeholder">
                                    Foto Mangihut Sinaga
                                </div>
                            @endif

                            <div class="mangihut-hero-profile-card">
                                <h5>Mangihut Sinaga, S.H., M.H.</h5>
                                <p>Anggota DPR RI Komisi III</p>
                                <span>Daerah Pemilihan Sumatera Utara III</span>
                            </div>

                        </div>

                        <div class="simalex-floating-card simalex-floating-1">
                            <i class="lni lni-checkmark-circle"></i>
                            Aspirasi Terdokumentasi
                        </div>

                        <div class="simalex-floating-card simalex-floating-2">
                            <i class="lni lni-map-marker"></i>
                            Dapil Sumatera Utara III
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>