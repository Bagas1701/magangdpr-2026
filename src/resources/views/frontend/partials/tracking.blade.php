<section id="tracking" class="simalex-panel">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-5">

                <div class="simalex-tracking-intro wow fadeInUp">

                    <div class="simalex-badge">
                        <span></span>
                        Layanan Aspirasi
                    </div>

                    <h2 class="simalex-section-title">
                        Cek Status Aspirasi dengan Nomor Tiket
                    </h2>

                    <p class="simalex-section-desc">
                        Masyarakat dapat mengecek perkembangan aspirasi
                        secara mandiri melalui nomor tiket yang diterima
                        setelah aspirasi tercatat di sistem.
                    </p>

                    <div class="simalex-tracking-points">

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Riwayat status aspirasi</span>
                        </div>

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Progress bar penanganan</span>
                        </div>

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Informasi tindak lanjut</span>
                        </div>

                    </div>

                    <div class="simalex-tracking-mini-card">
                        <i class="lni lni-shield"></i>

                        <div>
                            <strong>Data status bersifat informatif</strong>
                            <small>Gunakan nomor tiket resmi dari sistem SIMALEX.</small>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="simalex-tracking-card wow fadeInUp">

                    <div class="simalex-tracking-card-header">

                        <div>
                            <small>Layanan Publik</small>
                            <h4>Cek Status Aspirasi</h4>
                        </div>

                        <div class="simalex-tracking-icon">
                            <i class="lni lni-ticket"></i>
                        </div>

                    </div>

                    <div class="simalex-tracking-preview">

                        <div class="simalex-preview-step active">
                            <span></span>
                            Masuk
                        </div>

                        <div class="simalex-preview-line"></div>

                        <div class="simalex-preview-step active">
                            <span></span>
                            Verifikasi
                        </div>

                        <div class="simalex-preview-line"></div>

                        <div class="simalex-preview-step">
                            <span></span>
                            Tindak Lanjut
                        </div>

                        <div class="simalex-preview-line"></div>

                        <div class="simalex-preview-step">
                            <span></span>
                            Selesai
                        </div>

                    </div>

                    <form action="{{ route('frontend.tracking.show') }}" method="GET">

                        <label class="simalex-form-label">
                            Nomor Tiket Aspirasi
                        </label>

                        <div class="simalex-ticket-input">

                            <i class="lni lni-search-alt"></i>

                            <input
                                type="text"
                                name="ticket_number"
                                value="{{ request('ticket_number') }}"
                                placeholder="Contoh: ASP-2026-0001"
                                required
                            >

                        </div>

                        <button type="submit" class="simalex-track-btn">
                            Cek Status Aspirasi
                            <i class="lni lni-arrow-right"></i>
                        </button>

                    </form>

                    <div class="simalex-tracking-example">

                        <span>Contoh format:</span>
                        <strong>ASP-2026-0001</strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>