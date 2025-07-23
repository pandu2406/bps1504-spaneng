<div class="container-fluid">
    <div class="card shadow p-4">
        <h4 class="mb-4 font-weight-bold text-primary">Entry LPD Pegawai</h4>

        <div id="loadPreviousContainer" class="mb-3 d-none">
            <button type="button" class="btn btn-warning btn-sm" onclick="loadCachedForm()">Gunakan Isian Sebelumnya</button>
            <button type="button" class="btn btn-outline-danger btn-sm ml-2" onclick="clearCachedForm()">Hapus Cache</button>
        </div>

        <form id="formLPD" action="<?= base_url('persuratan/save_lpd_pegawai'); ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label>Tanggal Buat Surat</label>
                <input type="date" name="tanggal_buat" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama Pegawai</label>
                <select name="nama" id="nama_pegawai" class="form-control select2" required onchange="isiPegawai()">
                    <option value="">-- Pilih Pegawai --</option>
                    <?php foreach ($pegawai_list as $p): ?>
                        <option value="<?= $p['nama'] ?>" data-nip="<?= $p['nip'] ?>" data-jabatan="<?= $p['jabatan'] ?>">
                            <?= $p['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" readonly required>
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" class="form-control" readonly required>
            </div>

            <div class="form-group">
                <label>No. Surat Tugas</label>
                <input type="text" name="no_st" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tanggal Surat Tugas</label>
                <input type="date" name="tgl_st" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tanggal Tugas</label>
                <input type="date" name="tgl_tugas" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tujuan Tugas</label>
                <textarea name="tujuan_tugas" class="form-control" rows="2" required></textarea>
            </div>
            
            <hr>
            <h5 class="mt-4 mb-3 text-secondary">Jadwal Rundown Kegiatan</h5>

            <div id="rundown-wrapper">
                <div class="form-row mb-2 rundown-item">
                    <div class="col-md-3">
                        <input type="time" name="waktu_awal[]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <input type="time" name="waktu_akhir[]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="kegiatanx[]" class="form-control" placeholder="Kegiatan" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="lokasix[]" class="form-control" placeholder="Lokasi" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusRundown(this)">✕</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="tambahRundown()">+ Tambah Kegiatan</button>

            <div class="form-group">
                <label>Resume Kegiatan</label>
                <button type="button" class="btn btn-sm btn-info float-right" onclick="generateResume()">Auto Isi</button>
                <textarea name="resume" id="resume" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Upload Dokumentasi Lapangan</label>
                <input type="file" name="foto[]" class="form-control" accept=".jpg,.jpeg,.png,.heic" multiple required>
            </div>

            <input type="hidden" name="jenis" value="pegawai">
            <button type="submit" class="btn btn-primary mt-3">Simpan & Generate DOCX</button>
        </form>
    </div>
</div>

<!-- === Select2 & Custom Scripts === -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery wajib -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#nama_pegawai').select2({
            placeholder: "-- Pilih Pegawai --",
            allowClear: true,
            width: '100%'
        });
    });

    function isiPegawai() {
        const selected = document.querySelector('#nama_pegawai').selectedOptions[0];
        const nip = selected.getAttribute('data-nip');
        const jabatan = selected.getAttribute('data-jabatan');
        document.querySelector('#nip').value = nip;
        document.querySelector('#jabatan').value = jabatan;
    }

    function tambahRundown() {
    const wrapper = document.getElementById('rundown-wrapper');
    const div = document.createElement('div');
    div.className = "form-row mb-2 rundown-item";
    div.innerHTML = `
        <div class="col-md-3">
            <input type="time" name="waktu_awal[]" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="time" name="waktu_akhir[]" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="kegiatanx[]" class="form-control" placeholder="Kegiatan" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="lokasix[]" class="form-control" placeholder="Lokasi" required>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusRundown(this)">✕</button>
        </div>
    `;
    wrapper.appendChild(div);
}

    function hapusRundown(button) {
    const row = button.closest('.rundown-item');
    if (row) {
        row.remove();
    }
}


    function generateResume() {
        const nama = document.querySelector('#nama_pegawai').value;
        const tanggal = document.querySelector('[name="tgl_tugas"]').value;
        const tujuan = document.querySelector('[name="tujuan_tugas"]').value.trim();

        const kegiatanList = Array.from(document.querySelectorAll('[name="kegiatanx[]"]')).map(el => el.value.trim()).filter(Boolean);
        const lokasiList = Array.from(document.querySelectorAll('[name="lokasix[]"]')).map(el => el.value.trim()).filter(Boolean);

        if (!nama || !tanggal || lokasiList.length === 0 || kegiatanList.length === 0 || !tujuan) {
            alert('Pastikan Nama, Tanggal Tugas, Tujuan, Lokasi, dan Kegiatan telah diisi.');
            return;
        }

        const formatter = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });

        const dateObj = new Date(tanggal);
        const formattedTanggal = formatter.format(dateObj);

        const lokasiUnik = [...new Set(lokasiList)];
        const kegiatanUnik = [...new Set(kegiatanList)];

        const lokasiStr = lokasiUnik.join(', ');
        const kegiatanStr = kegiatanUnik.map(k => k.charAt(0).toUpperCase() + k.slice(1)).join('; ');

        const paragraf1 = `Pada hari ${formattedTanggal} telah dilakukan ${tujuan.toLowerCase()} di lokasi ${lokasiStr} oleh petugas atas nama ${nama}. Kegiatan ini merupakan bagian dari pelaksanaan tugas berdasarkan surat tugas yang berlaku.`;

        const paragraf2 = `Selama pelaksanaan kegiatan, petugas melakukan aktivitas seperti ${kegiatanStr}. Kegiatan diawali dengan koordinasi di lokasi, kemudian dilanjutkan dengan pengawasan dan pencatatan sesuai rundown yang telah direncanakan.`;

        const paragraf3 = `Secara umum kegiatan berjalan dengan baik. Jika terdapat kendala teknis di lapangan, petugas melakukan koordinasi dengan pihak terkait untuk penyelesaian lebih lanjut.`;

        const resumeText = `${paragraf1}\n\n${paragraf2}\n\n${paragraf3}`;
        document.querySelector('#resume').value = resumeText;
    }
    
    // Key untuk LocalStorage
const CACHE_KEY = 'formLPD_pegawai_cache';

// Tampilkan tombol jika ada cache
$(document).ready(function () {
    if (localStorage.getItem(CACHE_KEY)) {
        $('#loadPreviousContainer').removeClass('d-none');
    }
});

// Simpan cache saat submit
$('#formLPD').on('submit', function () {
    const formObject = {};
    const formData = new FormData(this);

    // Simpan input biasa
    formData.forEach((value, key) => {
        if (key.includes('[]')) {
            const arrayKey = key.replace('[]', '');
            if (!formObject[arrayKey]) formObject[arrayKey] = [];
            formObject[arrayKey].push(value);
        } else {
            formObject[key] = value;
        }
    });

    localStorage.setItem(CACHE_KEY, JSON.stringify(formObject));
});

// Fungsi untuk load cache
function loadCachedForm() {
    const cached = localStorage.getItem(CACHE_KEY);
    if (!cached) return;

    const data = JSON.parse(cached);

    // Load input biasa
    for (const key in data) {
        if (Array.isArray(data[key])) continue;
        const el = document.querySelector(`[name="${key}"]`);
        if (el) el.value = data[key];
    }

    // Isi dropdown nama pegawai + trigger onchange
    $('#nama_pegawai').val(data['nama']).trigger('change');
    setTimeout(isiPegawai, 100);

    // Load rundown
    const waktu_awal = data['waktu_awal'] || [];
    const waktu_akhir = data['waktu_akhir'] || [];
    const kegiatanx = data['kegiatanx'] || [];
    const lokasix = data['lokasix'] || [];

    const wrapper = document.getElementById('rundown-wrapper');
    wrapper.innerHTML = ''; // Kosongkan dulu rundown lama

    for (let i = 0; i < waktu_awal.length; i++) {
        const div = document.createElement('div');
        div.className = "form-row mb-2 rundown-item";
        div.innerHTML = `
            <div class="col-md-3">
                <input type="time" name="waktu_awal[]" class="form-control" value="${waktu_awal[i] || ''}" required>
            </div>
            <div class="col-md-3">
                <input type="time" name="waktu_akhir[]" class="form-control" value="${waktu_akhir[i] || ''}" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="kegiatanx[]" class="form-control" placeholder="Kegiatan" value="${kegiatanx[i] || ''}" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="lokasix[]" class="form-control" placeholder="Lokasi" value="${lokasix[i] || ''}" required>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusRundown(this)">✕</button>
            </div>
        `;
        wrapper.appendChild(div);
    }
}

$(document).ready(function () {
    if (localStorage.getItem(CACHE_KEY)) {
        $('#loadPreviousContainer').removeClass('d-none');
        // loadCachedForm(); // ← Aktifkan ini jika ingin auto-load langsung
    }
});

// Fungsi hapus cache
function clearCachedForm() {
    localStorage.removeItem(CACHE_KEY);
    $('#loadPreviousContainer').addClass('d-none');
    alert("Cache berhasil dihapus.");
}

</script>