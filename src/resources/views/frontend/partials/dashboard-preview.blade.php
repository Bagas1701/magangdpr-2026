<section id="dashboard" class="simalex-panel">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-5">

                <div class="simalex-dashboard-intro wow fadeInUp">

                    <div class="simalex-badge">
                        <span></span>
                        Dashboard Statistik
                    </div>

                    <h2 class="simalex-section-title">
                        Monitoring Aspirasi Berbasis Data
                    </h2>

                    <p class="simalex-section-desc">
                        SIMALEX menampilkan statistik aspirasi secara ringkas
                        berdasarkan jumlah laporan, status penanganan, dan
                        perkembangan tindak lanjut tanpa menampilkan data pribadi.
                    </p>

                    <div class="simalex-dashboard-note">
                        <i class="lni lni-dashboard"></i>
                        Dashboard ini menampilkan ringkasan data aspirasi
                        secara publik sebagai bentuk transparansi layanan.
                    </div>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="simalex-dashboard-showcase wow fadeInUp">

                    <div class="simalex-dashboard-showcase-header">
                        <div>
                            <small>Public Monitoring</small>
                            <h4>Ringkasan Aspirasi</h4>
                        </div>

                        <span>Live Data</span>
                    </div>

                    <div class="simalex-dashboard-stats">

                        <div class="simalex-dashboard-stat">
                            <small>Total Aspirasi</small>
                            <strong>{{ number_format($totalAspirasi) }}</strong>
                            <span>Seluruh aspirasi tercatat</span>
                        </div>

                        <div class="simalex-dashboard-stat">
                            <small>Diproses</small>
                            <strong>{{ number_format($processedAspirasi) }}</strong>
                            <span>Verifikasi & tindak lanjut</span>
                        </div>

                        <div class="simalex-dashboard-stat">
                            <small>Selesai</small>
                            <strong>{{ number_format($finishedAspirasi) }}</strong>
                            <span>Telah ditutup</span>
                        </div>

                    </div>

                    <div class="simalex-dashboard-kpi">

                        <div>
                            <small>Tingkat Penyelesaian</small>
                            <strong>{{ $completionRate }}%</strong>
                        </div>

                        <div>
                            <small>Aspirasi Bulan Ini</small>
                            <strong>{{ number_format($currentMonthAspirasi) }}</strong>
                        </div>

                    </div>

                    <div class="simalex-dashboard-body-preview">

                        <div class="simalex-chart-card">
                            <div class="simalex-chart-header">
                                <strong>Distribusi Status</strong>
                                <span>{{ now()->year }}</span>
                            </div>

                            <div class="simalex-chart-bars">
                                @foreach ($dashboardStatusBars as $label => $count)
                                    @php
                                        $height = $totalAspirasi > 0
                                            ? max(12, min(100, ($count / $totalAspirasi) * 100))
                                            : 12;
                                    @endphp

                                    <div
                                        title="{{ $label }}: {{ $count }}"
                                        style="height: {{ $height }}%"
                                    ></div>
                                @endforeach
                            </div>
                        </div>

                        <div class="simalex-recent-card">
                            <strong>Top Kategori</strong>

                            @forelse ($topCategories as $category)
                                <div class="simalex-recent-item">
                                    <span>{{ $category->kategori ?? 'Tanpa Kategori' }}</span>
                                    <small>{{ $category->total }} aspirasi</small>
                                </div>
                            @empty
                                <div class="simalex-recent-item">
                                    <span>Belum ada data</span>
                                    <small>-</small>
                                </div>
                            @endforelse
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>