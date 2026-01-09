<div class="container-fluid mb-5">

    <!-- Page Header -->
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Entry Laporan Perjalanan Dinas (Pegawai)</h1>

    <!-- Cache Alert -->
    <div id="loadPreviousContainer" class="alert alert-warning border-left-warning shadow-sm d-none fade show"
        role="alert">
        <i class="fas fa-history mr-2"></i>Anda memiliki data isian sebelumnya yang belum tersimpan.
        <div class="mt-2">
            <button type="button" class="btn btn-warning btn-sm font-weight-bold shadow-sm" onclick="loadCachedForm()">
                <i class="fas fa-upload mr-1"></i> Pulihkan Data
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm ml-2" onclick="clearCachedForm()">
                <i class="fas fa-trash-alt mr-1"></i> Hapus Cache
            </button>
        </div>
    </div>

    <form id="formLPD" action="<?= base_url('persuratan/save_lpd_pegawai'); ?>" method="post"
        enctype="multipart/form-data">

        <div class="row">
            <!-- Left Column: Informasi Petugas & Tugas -->
            <div class="col-lg-6 mb-4">

                <!-- Card Informasi Petugas -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gradient-primary text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-user-tie mr-2"></i>Informasi Petugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Tanggal Pembuatan Surat Laporan</label>
                            <input type="date" name="tanggal_buat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Nama Pegawai</label>
                            <select name="nama" id="nama_pegawai" class="form-control select2" required
                                onchange="isiPegawai()">
                                <option value="">-- Cari Nama Pegawai --</option>
                                <?php foreach ($pegawai_list as $p): ?>
                                    <option value="<?= $p['nama'] ?>" data-nip="<?= $p['nip'] ?>"
                                        data-jabatan="<?= $p['jabatan'] ?>">
                                        <?= $p['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">NIP</label>
                                    <input type="text" name="nip" id="nip" class="form-control bg-light" readonly
                                        placeholder="NIP otomatis..." required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-secondary">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan" class="form-control bg-light"
                                        readonly placeholder="Jabatan otomatis..." required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Detail Penugasan -->
                <div class="card shadow">
                    <div class="card-header py-3 bg-white border-left-success">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-briefcase mr-2"></i>Detail
                            Penugasan Surat (ST)</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Nomor Surat Tugas (ST)</label>
                            <input type="text" name="no_st" class="form-control" placeholder="Contoh: B-123/BPS/..."
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary">Tanggal Surat Tugas</label>
                                <input type="date" name="tgl_st" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary">Tanggal Pelaksanaan Tugas</label>
                                <input type="date" name="tgl_tugas" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Tujuan/Maksud Penugasan</label>
                            <textarea name="tujuan_tugas" class="form-control" rows="3"
                                placeholder="Jelaskan tujuan tugas secara singkat..." required></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Rundown & Resume -->
            <div class="col-lg-6">

                <!-- Card Rundown -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white border-left-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-list-ol mr-2"></i>Jadwal
                                Kegiatan (Rundown)</h6>
                            <button type="button" class="btn btn-sm btn-info shadow-sm" onclick="tambahRundown()">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                        <small class="text-muted d-block text-center mb-2 font-italic">* Tekan tombol (+) untuk menambah
                            baris kegiatan</small>
                        <div id="rundown-wrapper">
                            <div class="card mb-2 border-left-info shadow-sm rundown-item">
                                <div class="card-body p-3">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-10">
                                            <div class="form-row mb-2">
                                                <div class="col-6">
                                                    <small
                                                        class="text-xs font-weight-bold text-uppercase text-muted">Mulai</small>
                                                    <input type="time" name="waktu_awal[]"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-6">
                                                    <small
                                                        class="text-xs font-weight-bold text-uppercase text-muted">Selesai</small>
                                                    <input type="time" name="waktu_akhir[]"
                                                        class="form-control form-control-sm" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-6">
                                                    <input type="text" name="kegiatanx[]"
                                                        class="form-control form-control-sm border-0 bg-light"
                                                        placeholder="Nama Kegiatan..." required>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="lokasix[]"
                                                        class="form-control form-control-sm border-0 bg-light"
                                                        placeholder="Lokasi..." required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-center pl-2 border-left">
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle"
                                                onclick="hapusRundown(this)" title="Hapus Baris">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Resume & Foto -->
                <div class="card shadow">
                    <div class="card-header py-3 bg-white border-left-warning">
                        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-file-alt mr-2"></i>Resume &
                            Dokumentasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small font-weight-bold text-secondary mb-0">Uraian/Resume Hasil</label>
                                <button type="button" class="btn btn-xs btn-outline-info" onclick="generateResume()">
                                    <i class="fas fa-magic mr-1"></i> Auto-Generate
                                </button>
                            </div>
                            <textarea name="resume" id="resume" class="form-control" rows="5"
                                placeholder="Tuliskan hasil kegiatan..." required></textarea>
                            <small class="form-text text-muted">Gunakan tombol <b>Auto-Generate</b> untuk membuat narasi
                                otomatis dari data yang diinput.</small>
                        </div>
                        <div class="form-group mt-3">
                            <label class="small font-weight-bold text-secondary">Upload Foto Dokumentasi (Max 3)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="foto[]" id="fotoFile"
                                    accept=".jpg,.jpeg,.png,.heic" multiple required>
                                <label class="custom-file-label" for="fotoFile">Pilih file foto...</label>
                            </div>
                            <small class="text-xs text-muted mt-1">* Format: JPG/PNG/HEIC. Maks 2MB per file.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <input type="hidden" name="jenis" value="pegawai">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow">
                            <i class="fas fa-save mr-2"></i>Simpan Laporan & Download .DOCX
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

<!-- Styles and Plugins -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css"
    rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Focus effects for cleaner look */
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #d1d3e2 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>

<script>$(document).ready(function () {

            // Init Select2 with Bootstrap theme if possible, or default
            $('#nama_pegawai').select2({
                placeholder: "-- Pilih Pegawai --",
                allowClear: true,
                theme: 'bootstrap4',
                width: '100%'
            });

        // File Input Label Change
        $('.custom-file-input').on('change', function () {
                var files=Array.from(this.files);
                var label=files.length > 1 ? files.length + ' file dipilih' : files[0].name;
                $(this).next('.custom-file-label').addClass("selected").html(label);
            });

        // Cek Cache
        if (localStorage.getItem(CACHE_KEY)) {
            $('#loadPreviousContainer').removeClass('d-none');
        }
    });

    const CACHE_KEY='formLPD_pegawai_cache';

    function isiPegawai() {
        const selected=$('#nama_pegawai').select2('data')[0].element;

        if (selected) {
            $('#nip').val($(selected).data('nip'));
            $('#jabatan').val($(selected).data('jabatan'));
        }

        else {
            $('#nip').val('');
            $('#jabatan').val('');
        }
    }

    function tambahRundown() {
        const template=` <div class="card mb-2 border-left-info shadow-sm rundown-item fade-in"><div class="card-body p-3"><div class="row no-gutters align-items-center"><div class="col-10"><div class="form-row mb-2"><div class="col-6"><small class="text-xs font-weight-bold text-uppercase text-muted">Mulai</small><input type="time" name="waktu_awal[]" class="form-control form-control-sm" required></div><div class="col-6"><small class="text-xs font-weight-bold text-uppercase text-muted">Selesai</small><input type="time" name="waktu_akhir[]" class="form-control form-control-sm" required></div></div><div class="form-row"><div class="col-6"><input type="text" name="kegiatanx[]" class="form-control form-control-sm border-0 bg-light" placeholder="Nama Kegiatan..." required></div><div class="col-6"><input type="text" name="lokasix[]" class="form-control form-control-sm border-0 bg-light" placeholder="Lokasi..." required></div></div></div><div class="col-2 text-center pl-2 border-left"><button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="hapusRundown(this)" title="Hapus Baris"><i class="fas fa-times"></i></button></div></div></div></div>`;
        $('#rundown-wrapper').append(template);
        // Scroll to bottom of wrapper
        var wrapper=document.getElementById('rundown-wrapper');
        wrapper.scrollTop=wrapper.scrollHeight;
    }

    function hapusRundown(btn) {
        $(btn).closest('.rundown-item').remove();
    }

    function generateResume() {
        // ... (Logic same as before, essentially)
        const nama=$('#nama_pegawai').val(); // Select2 value
        const tanggal=$('[name="tgl_tugas"]').val();
        const tujuan=$('[name="tujuan_tugas"]').val().trim();

        const kegiatanList=Array.from(document.querySelectorAll('[name="kegiatanx[]"]')).map(el=> el.value.trim()).filter(Boolean);
        const lokasiList=Array.from(document.querySelectorAll('[name="lokasix[]"]')).map(el=> el.value.trim()).filter(Boolean);

        if ( !nama || !tanggal || lokasiList.length===0 || kegiatanList.length===0 || !tujuan) {
            alert('Lengkapi data Nama, Tanggal Tugas, Tujuan, Lokasi, dan Kegiatan terlebih dahulu.');
            return;
        }

        const formatter=new Intl.DateTimeFormat('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
    const dateObj=new Date(tanggal);
    const formattedTanggal=formatter.format(dateObj);

    const lokasiUnik=[...new Set(lokasiList)].join(', ');
    const kegiatanUnik=[...new Set(kegiatanList)].map(k=> k.charAt(0).toUpperCase() + k.slice(1)).join('; ');

    const text=`Pada hari $ {
        formattedTanggal
    }

    telah dilakukan $ {
        tujuan.toLowerCase()
    }

    di lokasi $ {
        lokasiUnik
    }

    oleh petugas atas nama $ {
        nama
    }

    . Kegiatan ini merupakan bagian dari pelaksanaan tugas berdasarkan surat tugas yang berlaku.\n\nSelama pelaksanaan kegiatan,
    petugas melakukan aktivitas seperti $ {
        kegiatanUnik
    }

    . Kegiatan diawali dengan koordinasi di lokasi,
    kemudian dilanjutkan dengan pengawasan dan pencatatan sesuai rundown yang telah direncanakan.\n\nSecara umum kegiatan berjalan dengan baik. Jika terdapat kendala teknis di lapangan,
    petugas melakukan koordinasi dengan pihak terkait untuk penyelesaian lebih lanjut.`;

    $('#resume').val(text);
    }

    // Form Cache Logic
    $('#formLPD').on('submit', function () {
            const formObject= {}

            ;
            const formData=new FormData(this);

            formData.forEach((value, key)=> {
                    if (key.includes('[]')) {
                        const arrayKey=key.replace('[]', '');
                        if ( !formObject[arrayKey]) formObject[arrayKey]=[];
                        formObject[arrayKey].push(value);
                    }

                    else {
                        formObject[key]=value;
                    }
                });
            localStorage.setItem(CACHE_KEY, JSON.stringify(formObject));
        });

    function loadCachedForm() {
        const cached=localStorage.getItem(CACHE_KEY);
        if ( !cached) return;
        const data=JSON.parse(cached);

        // Inputs
        for (const key in data) {
            if (Array.isArray(data[key])) continue;
            let el=document.querySelector(`[name="${key}"]`);
            if (el) el.value=data[key];
        }

        // Select2
        if (data['nama']) $('#nama_pegawai').val(data['nama']).trigger('change');
        setTimeout(isiPegawai, 200);

        // Rundown
        const totalItems=data['waktu_awal'] ? data['waktu_awal'].length : 0;
        $('#rundown-wrapper').empty(); // Clear existing default

        for (let i=0; i < totalItems; i++) {
            tambahRundown(); // Add row structure
        }

        // Populate rundown rows (need delay/direct insert modification ideally, but simple iteration works if we assume order matches)
        // A better way is to rebuild specific HTML with values
        $('#rundown-wrapper').empty();

        for (let i=0; i < totalItems; i++) {
            const html=` <div class="card mb-2 border-left-info shadow-sm rundown-item"><div class="card-body p-3"><div class="row no-gutters align-items-center"><div class="col-10"><div class="form-row mb-2"><div class="col-6"><small class="text-muted">Mulai</small><input type="time" name="waktu_awal[]" class="form-control form-control-sm" value="${data['waktu_awal'][i]}" required></div><div class="col-6"><small class="text-muted">Selesai</small><input type="time" name="waktu_akhir[]" class="form-control form-control-sm" value="${data['waktu_akhir'][i]}" required></div></div><div class="form-row"><div class="col-6"><input type="text" name="kegiatanx[]" class="form-control form-control-sm bg-light" value="${data['kegiatanx'][i]}" required></div><div class="col-6"><input type="text" name="lokasix[]" class="form-control form-control-sm bg-light" value="${data['lokasix'][i]}" required></div></div></div><div class="col-2 text-center pl-2 border-left"><button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="hapusRundown(this)"><i class="fas fa-times"></i></button></div></div></div></div>`;
            $('#rundown-wrapper').append(html);
        }
    }

    function clearCachedForm() {
        localStorage.removeItem(CACHE_KEY);
        $('#loadPreviousContainer').addClass('d-none');
    }

    </script>