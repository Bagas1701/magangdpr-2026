@php
    $profilePhoto = \App\Models\WebsiteImage::where('name', 'Foto Mangihut Sinaga')->first();
@endphp

<section id="feature" class="simalex-panel">

    <div class="container">

        <div class="mangihut-profile-section">

            <div class="row align-items-center gy-5">

                <div class="col-lg-5">

                    <div class="mangihut-profile-photo-wrap wow fadeInUp">

                        @if ($profilePhoto?->image)
                            <img
                                src="{{ Storage::url($profilePhoto->image) }}"
                                alt="Mangihut Sinaga"
                                class="mangihut-profile-photo"
                            >
                        @else
                            <div class="mangihut-profile-placeholder">
                                Foto Mangihut Sinaga
                            </div>
                        @endif

                    </div>

                </div>

                <div class="col-lg-7">

                    <div class="mangihut-profile-content wow fadeInUp">

                        <div class="simalex-badge">
                            <span></span>
                            Profil Beliau
                        </div>

                        <h2 class="simalex-section-title">
                            Mangihut Sinaga, S.H., M.H.
                        </h2>

                        <h5 class="mangihut-profile-subtitle">
                            Anggota DPR RI Komisi III
                        </h5>

                        <p class="simalex-section-desc">
                            Berkomitmen untuk menjadi jembatan antara masyarakat
                            dan pemerintah. Aspirasi Anda adalah prioritas kami
                            untuk mendorong perubahan nyata dan pembangunan yang
                            berkeadilan.
                        </p>

                        <div class="mangihut-profile-info-grid">

                            <div class="mangihut-profile-info">
                                <i class="lni lni-briefcase"></i>
                                <div>
                                    <span>Komisi</span>
                                    <strong>III DPR RI</strong>
                                </div>
                            </div>

                            <div class="mangihut-profile-info">
                                <i class="lni lni-map-marker"></i>
                                <div>
                                    <span>Dapil</span>
                                    <strong>Sumatera Utara III</strong>
                                </div>
                            </div>

                            <div class="mangihut-profile-info">
                                <i class="lni lni-shield"></i>
                                <div>
                                    <span>Ruang Lingkup</span>
                                    <strong>Hukum, HAM, Keamanan</strong>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="mangihut-commitment-area wow fadeInUp">

                <div class="mangihut-commitment-title">
                    Komitmen Kami
                </div>

                <div class="mangihut-commitment-grid">

                    <div class="mangihut-commitment-card">
                        <i class="lni lni-comments"></i>
                        <span>Menampung setiap aspirasi masyarakat</span>
                    </div>

                    <div class="mangihut-commitment-card">
                        <i class="lni lni-checkmark-circle"></i>
                        <span>Mengawal hingga ditindaklanjuti</span>
                    </div>

                    <div class="mangihut-commitment-card">
                        <i class="lni lni-dashboard"></i>
                        <span>Transparan dalam setiap proses</span>
                    </div>

                    <div class="mangihut-commitment-card">
                        <i class="lni lni-handshake"></i>
                        <span>Bekerja untuk perubahan nyata</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>