<section id="contact" class="simalex-panel">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-5">

                <div class="simalex-contact-intro wow fadeInUp">

                    <div class="simalex-badge">
                        <span></span>
                        Kanal Aspirasi
                    </div>

                    <h2 class="simalex-section-title">
                        Sampaikan Aspirasi dengan Data yang Jelas
                    </h2>

                    <p class="simalex-section-desc">
                        Formulir ini menjadi kanal awal bagi masyarakat
                        untuk menyampaikan aspirasi, pengaduan, atau
                        kebutuhan yang ingin ditindaklanjuti.
                    </p>

                    <div class="simalex-contact-info">

                        <div class="simalex-contact-info-item">
                            <div class="icon">
                                <i class="lni lni-phone"></i>
                            </div>

                            <div>
                                <span>Hotline Aspirasi</span>
                                <strong>0812-0000-0000</strong>
                            </div>
                        </div>

                        <div class="simalex-contact-info-item">
                            <div class="icon">
                                <i class="lni lni-envelope"></i>
                            </div>

                            <div>
                                <span>Email Layanan</span>
                                <strong>simalex@dpr.go.id</strong>
                            </div>
                        </div>

                        <div class="simalex-contact-info-item">
                            <div class="icon">
                                <i class="lni lni-map-marker"></i>
                            </div>

                            <div>
                                <span>Unit Pengelola</span>
                                <strong>Kantor Anggota DPR RI Komisi III</strong>
                            </div>
                        </div>

                    </div>

                    <div class="simalex-contact-note">
                        <i class="lni lni-information"></i>
                        Pastikan data yang dikirim sesuai agar proses verifikasi
                        aspirasi dapat dilakukan dengan lebih mudah.
                    </div>

                </div>

            </div>

            <div class="col-lg-7">

                <div class="simalex-contact-card wow fadeInUp">

                    <div class="simalex-contact-card-header">

                        <div>
                            <small>Formulir Publik</small>
                            <h4>Kirim Aspirasi</h4>
                        </div>

                        <div class="simalex-contact-icon">
                            <i class="lni lni-comments"></i>
                        </div>

                    </div>

                    <form
                        action="{{ route('frontend.aspirasi.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="simalex-form-label">Nama Lengkap</label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="simalex-form-control"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >

                                @error('nama')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">NIK</label>

                                <input
                                    type="text"
                                    name="nik"
                                    class="simalex-form-control"
                                    value="{{ old('nik') }}"
                                    placeholder="Masukkan NIK"
                                    required
                                >

                                @error('nik')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">No. HP</label>

                                <input
                                    type="text"
                                    name="kontak"
                                    class="simalex-form-control"
                                    value="{{ old('kontak') }}"
                                    placeholder="Contoh: 0812..."
                                >

                                @error('kontak')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Kategori Aspirasi</label>

                                <select name="kategori_aspirasi_id" class="simalex-form-control">
                                    <option value="">Pilih kategori</option>

                                    @foreach ($kategoriAspirasis as $kategori)
                                        <option
                                            value="{{ $kategori->id }}"
                                            @selected(old('kategori_aspirasi_id') == $kategori->id)
                                        >
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('kategori_aspirasi_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">Judul Aspirasi</label>

                                <input
                                    type="text"
                                    name="judul"
                                    class="simalex-form-control"
                                    value="{{ old('judul') }}"
                                    placeholder="Contoh: Laporan dugaan penipuan investasi"
                                    required
                                >

                                @error('judul')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">Isi Aspirasi</label>

                                <textarea
                                    name="deskripsi"
                                    class="simalex-form-control simalex-textarea"
                                    rows="5"
                                    placeholder="Tuliskan aspirasi atau pengaduan secara jelas"
                                    required
                                >{{ old('deskripsi') }}</textarea>

                                @error('deskripsi')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Tanggal Kejadian</label>

                                <input
                                    type="date"
                                    name="tanggal_kejadian"
                                    class="simalex-form-control"
                                    value="{{ old('tanggal_kejadian') }}"
                                >

                                @error('tanggal_kejadian')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Lokasi Kejadian</label>

                                <input
                                    type="text"
                                    name="lokasi_kejadian"
                                    class="simalex-form-control"
                                    value="{{ old('lokasi_kejadian') }}"
                                    placeholder="Contoh: Medan, Sumatera Utara"
                                >

                                @error('lokasi_kejadian')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">
                                    Lampiran Pendukung
                                </label>

                                <div class="simalex-file-upload-box">
                                    <button type="button" class="simalex-file-upload-btn" id="simalexFileTrigger">
                                        <i class="lni lni-upload"></i>
                                        Pilih File
                                    </button>

                                    <span class="simalex-file-upload-hint">
                                        Maksimal 5 file. Format: PDF, DOC, DOCX, JPG, JPEG, PNG.
                                    </span>

                                    <input
                                        type="file"
                                        id="simalexAttachmentInput"
                                        name="attachments[]"
                                        class="d-none"
                                        multiple
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    >
                                </div>

                                <div id="simalexSelectedFiles" class="simalex-selected-files"></div>

                                @error('attachments')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror

                                @error('attachments.*')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="simalex-consent-box">
                                    <input
                                        type="checkbox"
                                        id="privacy_consent"
                                        name="privacy_consent"
                                        value="1"
                                        required
                                        @checked(old('privacy_consent'))
                                    >

                                    <label for="privacy_consent">
                                        Saya menyetujui penggunaan data pribadi untuk proses verifikasi,
                                        pengelolaan aspirasi, dan tindak lanjut oleh petugas berwenang
                                        sesuai kebutuhan layanan SIMALEX.
                                    </label>
                                </div>
                                
                                <a href="{{ route('frontend.privacy') }}" target="_blank">
                                    Kebijakan Privasi
                                </a>

                                @error('privacy_consent')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="simalex-form-note">
                                    <i class="lni lni-shield"></i>
                                    Setelah aspirasi tercatat, sistem akan menghasilkan nomor tiket
                                    yang dapat digunakan untuk mengecek status aspirasi.
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="simalex-submit-btn">
                                    Kirim Aspirasi
                                    <i class="lni lni-arrow-right"></i>
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>