<section id="dashboard" class="simalex-panel">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-5">

                <div class="simalex-dashboard-intro wow fadeInUp">

                    <div class="simalex-badge">
                        <span></span>
                        Dashboard Publik
                    </div>

                    <h2 class="simalex-section-title">
                        Statistik Aspirasi
                        <span>Berbasis Data</span>
                    </h2>

                    <p class="simalex-section-desc">
                        Pantau perkembangan aspirasi masyarakat secara
                        transparan melalui ringkasan data jumlah aspirasi,
                        status penanganan, tingkat penyelesaian, dan kategori
                        aspirasi yang paling banyak diterima.
                    </p>

                    <div class="simalex-dashboard-note">
                        <i class="lni lni-dashboard"></i>
                        Data yang ditampilkan bersifat ringkasan publik
                        tanpa menampilkan informasi pribadi masyarakat.
                    </div>

                    <div class="simalex-tracking-points mt-4">

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Data aspirasi tercatat</span>
                        </div>

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Monitoring status penanganan</span>
                        </div>

                        <div>
                            <i class="lni lni-checkmark-circle"></i>
                            <span>Transparansi layanan publik</span>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="simalex-dashboard-showcase wow fadeInUp">

                    <div class="simalex-dashboard-showcase-header">
                        <div>
                            <small>Ringkasan Aspirasi</small>
                            <h4>Portal Aspirasi Mangihut Sinaga</h4>
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
                            <small>Sedang Diproses</small>
                            <strong>{{ number_format($processedAspirasi) }}</strong>
                            <span>Verifikasi & tindak lanjut</span>
                        </div>

                        <div class="simalex-dashboard-stat">
                            <small>Selesai</small>
                            <strong>{{ number_format($finishedAspirasi) }}</strong>
                            <span>Telah ditindaklanjuti</span>
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

                                        $shortLabel = match ($label) {
                                            'Menunggu Persetujuan' => 'Approval',
                                            'Tindak Lanjut' => 'Tindak',
                                            default => $label,
                                        };
                                    @endphp

                                    <div class="simalex-chart-item" title="{{ $label }}: {{ $count }} aspirasi">
                                        <div class="simalex-chart-bar-wrap">
                                            <div class="simalex-chart-bar" style="height: {{ $height }}%"></div>
                                        </div>

                                        <small class="simalex-chart-label">
                                            {{ $shortLabel }}
                                        </small>
                                    </div>
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