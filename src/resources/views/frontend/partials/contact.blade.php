<section id="contact" class="simalex-panel">

    <div class="container">

        <div class="row align-items-center gy-5">

            <div class="col-lg-5">

                <div class="simalex-contact-intro wow fadeInUp">

                    <div class="simalex-badge">
                        <span></span>
                        Aspirasi & Kontak
                    </div>

                    <h2 class="simalex-section-title">
                        Sampaikan Aspirasi untuk Ditindaklanjuti
                    </h2>

                    <p class="simalex-section-desc">
                        Portal ini menjadi kanal digital bagi masyarakat
                        untuk menyampaikan aspirasi, pengaduan, atau kebutuhan
                        kepada Mangihut Sinaga secara lebih mudah, rapi,
                        dan terdokumentasi.
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
                                <strong>aspirasi@mangihutsinaga.id</strong>
                            </div>
                        </div>

                        <div class="simalex-contact-info-item">
                            <div class="icon">
                                <i class="lni lni-map-marker"></i>
                            </div>

                            <div>
                                <span>Wilayah Aspirasi</span>
                                <strong>Dapil Sumatera Utara III</strong>
                            </div>
                        </div>

                    </div>

                    <div class="simalex-contact-note">
                        <i class="lni lni-information"></i>
                        Pastikan data yang dikirim lengkap dan jelas agar proses
                        verifikasi aspirasi dapat dilakukan dengan lebih mudah.
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

                            <div class="col-md-12">
                                <div class="simalex-form-note">
                                    <i class="lni lni-user"></i>
                                    <strong>Data Konstituen</strong><br>
                                    Data ini digunakan untuk memastikan aspirasi berasal dari wilayah Dapil Sumatera Utara III.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">NIK</label>
                                <input
                                    id="nikInput"
                                    type="text"
                                    name="nik"
                                    class="simalex-form-control"
                                    value="{{ old('nik') }}"
                                    placeholder="16 digit NIK"
                                    maxlength="16"
                                    inputmode="numeric"
                                    required
                                >
                                <small id="nikCheckMessage" class="d-block mt-1 text-muted"></small>
                                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Kabupaten/Kota</label>
                                <select
                                    name="kabupaten_kota"
                                    id="kabupatenKotaSelect"
                                    class="simalex-form-control"
                                    required
                                >
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    @foreach (config('wilayah.sumut_iii') as $kabupaten => $kecamatans)
                                        <option value="{{ $kabupaten }}" @selected(old('kabupaten_kota') === $kabupaten)>
                                            {{ $kabupaten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kabupaten_kota') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Nama Lengkap</label>
                                <input
                                    id="namaInput"
                                    type="text"
                                    name="nama"
                                    class="simalex-form-control"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Kecamatan</label>
                                <select
                                    name="kecamatan"
                                    id="kecamatanSelect"
                                    class="simalex-form-control"
                                    required
                                >
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                @error('kecamatan') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">No. HP</label>
                                <input
                                    id="kontakInput"
                                    type="text"
                                    name="kontak"
                                    class="simalex-form-control"
                                    value="{{ old('kontak') }}"
                                    placeholder="Contoh: 0812..."
                                    required
                                >
                                @error('kontak') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Desa/Kelurahan</label>
                                <input
                                    id="kelurahanInput"
                                    type="text"
                                    name="kelurahan"
                                    class="simalex-form-control"
                                    value="{{ old('kelurahan') }}"
                                    placeholder="Contoh: Kelurahan/desa domisili"
                                >
                                @error('kelurahan') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">Alamat Lengkap</label>
                                <textarea
                                    id="alamatInput"
                                    name="alamat"
                                    class="simalex-form-control simalex-textarea"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap domisili"
                                >{{ old('alamat') }}</textarea>
                                @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="simalex-form-note">
                                    <i class="lni lni-comments"></i>
                                    <strong>Data Aspirasi</strong><br>
                                    Isi bagian berikut dengan detail aspirasi atau pengaduan yang ingin disampaikan.
                                </div>
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
                                @error('kategori_aspirasi_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="simalex-form-label">Tanggal Kejadian</label>
                                <input
                                    type="date"
                                    name="tanggal_kejadian"
                                    class="simalex-form-control"
                                    value="{{ old('tanggal_kejadian') }}"
                                >
                                @error('tanggal_kejadian') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">Judul Aspirasi</label>
                                <input
                                    type="text"
                                    name="judul"
                                    class="simalex-form-control"
                                    value="{{ old('judul') }}"
                                    placeholder="Contoh: Aspirasi terkait pelayanan masyarakat"
                                    required
                                >
                                @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
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
                                @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="simalex-form-label">Lokasi Kejadian</label>
                                <input
                                    type="text"
                                    name="lokasi_kejadian"
                                    class="simalex-form-control"
                                    value="{{ old('lokasi_kejadian') }}"
                                    placeholder="Contoh: Dairi, Medan, Jakarta, Online, atau lokasi kejadian lainnya"
                                >
                                @error('lokasi_kejadian') <small class="text-danger">{{ $message }}</small> @enderror
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
                                        Maksimal 5 file. Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, JPEG, PNG.
                                    </span>

                                    <input
                                        type="file"
                                        id="simalexAttachmentInput"
                                        name="attachments[]"
                                        class="d-none"
                                        multiple
                                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png"
                                    >
                                </div>

                                <div id="simalexSelectedFiles" class="simalex-selected-files"></div>

                                @error('attachments') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                @error('attachments.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
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
                                        sesuai kebutuhan layanan Portal Aspirasi Mangihut Sinaga.
                                    </label>
                                </div>

                                <a href="{{ route('frontend.privacy') }}" target="_blank">
                                    Kebijakan Privasi
                                </a>

                                @error('privacy_consent') <small class="text-danger d-block">{{ $message }}</small> @enderror
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wilayah = @json(config('wilayah.sumut_iii'));

            const nikInput = document.getElementById('nikInput');
            const namaInput = document.getElementById('namaInput');
            const kontakInput = document.getElementById('kontakInput');
            const kabupatenSelect = document.getElementById('kabupatenKotaSelect');
            const kecamatanSelect = document.getElementById('kecamatanSelect');
            const kelurahanInput = document.getElementById('kelurahanInput');
            const alamatInput = document.getElementById('alamatInput');
            const nikCheckMessage = document.getElementById('nikCheckMessage');

            const oldKecamatan = @json(old('kecamatan'));

            function renderKecamatan(selectedKecamatan = null) {
                const kabupaten = kabupatenSelect.value;
                const kecamatans = wilayah[kabupaten] || [];

                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

                kecamatans.forEach(function (kecamatan) {
                    const option = document.createElement('option');
                    option.value = kecamatan;
                    option.textContent = kecamatan;

                    if (
                        (selectedKecamatan && selectedKecamatan === kecamatan) ||
                        (!selectedKecamatan && oldKecamatan === kecamatan)
                    ) {
                        option.selected = true;
                    }

                    kecamatanSelect.appendChild(option);
                });
            }

            function setNikMessage(message, type = 'muted') {
                nikCheckMessage.textContent = message;
                nikCheckMessage.className = 'd-block mt-1 text-' + type;
            }

            async function checkNik() {
                const nik = nikInput.value.trim();

                if (nik.length === 0) {
                    setNikMessage('');
                    return;
                }

                if (!/^[0-9]{16}$/.test(nik)) {
                    setNikMessage('NIK harus 16 digit angka.', 'danger');
                    return;
                }

                setNikMessage('Memeriksa data konstituen...', 'muted');

                try {
                    const url = `{{ route('frontend.konstituen.check-nik') }}?nik=${encodeURIComponent(nik)}`;

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    const result = await response.json();

                    if (!result.found) {
                        setNikMessage(result.message, 'muted');
                        return;
                    }

                    namaInput.value = result.data.nama ?? '';
                    kontakInput.value = result.data.kontak ?? '';
                    kabupatenSelect.value = result.data.kabupaten_kota ?? '';

                    renderKecamatan(result.data.kecamatan ?? null);

                    kelurahanInput.value = result.data.kelurahan ?? '';
                    alamatInput.value = result.data.alamat ?? '';

                    setNikMessage('✓ Data konstituen ditemukan dan otomatis terisi.', 'success');
                } catch (error) {
                    setNikMessage('Gagal memeriksa NIK. Silakan isi manual.', 'danger');
                }
            }

            kabupatenSelect.addEventListener('change', function () {
                renderKecamatan();
            });

            nikInput.addEventListener('blur', checkNik);

            nikInput.addEventListener('input', function () {
                if (nikInput.value.trim().length === 16) {
                    checkNik();
                }
            });

            renderKecamatan();
        });
    </script>
</section>