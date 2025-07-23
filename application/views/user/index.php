<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <?php if ($user['role_id'] == 5) : ?>

        <div class="card shadow" style="background-color: #ffffff; ">
            <div class=" row">
                <div class="col-lg-2 mb-2 mt-2" align=center>
                    <br>
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    <hr>
                    <p class="card-text"><small class="text-muted">User since <?= date('d F Y', $user['date_created']); ?></small></p>
                </div>
                <div class="col-lg-5 mb-2 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>


                            <tr>
                                <th>NIK</th>
                                <td><?= $mitra['nik']; ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?= $mitra['nama']; ?></td>
                            </tr>
                            <tr>
                                <th>Posisi</th>
                                <td><?= $mitra['posisi']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $mitra['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= $mitra['alamat']; ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div>

                <div class="col-lg-5 mb-2 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Kecamatan</th>
                                <td><?= $mitra['nama_kecamatan']; ?></td>
                            </tr>
                            <tr>
                                <th>Desa</th>
                                <td><?= $mitra['nama_desa']; ?></td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td><?= $mitra['no_hp']; ?></td>
                            </tr>
                            <tr>
                                <th>Sobat ID</th>
                                <td><?= $mitra['sobat_id']; ?></td>
                            </tr>
                            <tr>
                                <th>Nilai</th>
                                <td><a href="<?= base_url('kegiatan/details_mitra_kegiatan/') . $mitra['id_mitra']; ?>" class="badge badge-primary">Pilih kegiatan</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($user['role_id'] == 1) : ?>
        <div class="card shadow col-lg-6" style="background-color: #ffffff; color:#00264d;">
            <div class="row">
                <div class="col-lg-2 mt-2" align=center>
                    <hr>
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    <hr>

                </div>
                <div class="col-lg-10 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <th>Nama</th>
                                <td><?= $pegawai['nama']; ?></td>
                            </tr> -->
                            <tr>
                                <th>Email</th>
                                <td><?= $user['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Since</th>
                                <td><?= date('d F Y', $user['date_created']); ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div>

            </div>
        </div>

<?php else : ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Biodata Pengguna -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow" style="background-color: #ffffff; color:#00264d;">
                <div class="row">
                    <div class="col-lg-3 text-center mt-3">
                        <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    </div>
                    <div class="col-lg-9 mt-3">
                        <table class="table table-borderless" style="color:#00264d;">
                            <tbody>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td><?= $user['email']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Since</th>
                                    <td><?= date('d F Y', $user['date_created']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Jumlah Mitra -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Mitra</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mitra_count ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Pegawai -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Pegawai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pegawai_count ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kegiatan Berjalan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kegiatan Berjalan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_berjalan ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kegiatan Akan Datang -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kegiatan Akan Datang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_akan_datang ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kegiatan Selesai -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Kegiatan Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_selesai ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-2">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Kegiatan Yang Selesai dan Belum Dilakukan Penilaian
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($belum_dinilai) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
        
                    <?php if (!empty($belum_dinilai)) : ?>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseKegiatan">
                                Lihat Selengkapnya
                            </button>
                            <div class="collapse mt-2" id="collapseKegiatan">
                                <small class="text-muted">Daftar kegiatan:</small>
                                <div class="row">
                                    <?php
                                    $chunks = array_chunk($belum_dinilai, ceil(count($belum_dinilai) / 2));
                                    foreach ($chunks as $chunk) :
                                    ?>
                                        <div class="col-md-6">
                                            <ul class="pl-3 mb-0" style="list-style-type: disc;">
                                                <?php foreach ($chunk as $k) : ?>
                                                    <li style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                                        <a href="<?= base_url('ranking/cek_progress/' . $k['id']) ?>" class="text-info" style="text-decoration: none;">
                                                            <?= $k['nama'] ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="mt-2 text-muted" style="font-size: 0.85rem;">
                            Semua kegiatan sudah dinilai.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-2">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Jumlah Rekap Mitra Belum Lengkap
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_rekap_tidak_lengkap ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
        
                    <?php if (!empty($rekap_tidak_lengkap)) : ?>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" type="button" data-toggle="collapse" data-target="#collapseMitra">
                                Lihat Selengkapnya
                            </button>
                            <div class="collapse mt-2" id="collapseMitra">
                                <small class="text-muted">Daftar mitra:</small>
                                <div class="row">
                                    <?php
                                    $chunks = array_chunk($rekap_tidak_lengkap, ceil(count($rekap_tidak_lengkap) / 2));
                                    foreach ($chunks as $chunk) :
                                    ?>
                                        <div class="col-md-6">
                                            <ul class="pl-3 mb-0" style="list-style-type: disc;">
                                                <?php foreach ($chunk as $m) : ?>
                                                    <li style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                                        <a href="<?= base_url('rekap/details_mitra/' . $m['id_mitra']) ?>" class="text-danger" style="text-decoration: none;">
                                                            <?= $m['nama'] ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="mt-2 text-muted" style="font-size: 0.85rem;">
                            Semua rekap mitra sudah lengkap.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-2">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Panduan Aplikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Lihat Panduan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="https://drive.google.com/file/d/1oiSp2Fb1Z7612BMk5sMCW_OA64IfPdFs/view?usp=sharing" target="_blank" class="btn btn-sm btn-warning text-white">
                            Buka Panduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Kalender Kegiatan -->
<div class="card shadow mt-4">
  <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <label for="filterSeksi" class="font-weight-bold">Filter Seksi:</label>
    <select id="filterSeksi" class="form-control form-control-sm d-inline-block" style="width: 200px;">
      <option value="all">Semua Seksi</option>
      <option value="1">Produksi</option>
      <option value="2">Sosial</option>
      <option value="3">Distribusi</option>
      <option value="4">Nerwilis</option>
      <option value="5">IPDS</option>
    </select>
  </div>
  <div class="text-right font-weight-bold">
  Total kegiatan di bulan <span id="currentMonthText"></span>: 
  <span id="totalKegiatan" class="badge badge-primary">0</span>
</div>
</div>

    <div id='calendar'></div>

    <!-- Legend Warna Seksi -->
    <div class="mt-4">
      <h6 class="font-weight-bold">Keterangan Warna Seksi:</h6>
      <div class="d-flex flex-wrap">
        <div class="legend-box me-3" style="background-color: #0d6efd;"></div> Produksi
        <div class="legend-box mx-3" style="background-color: #198754;"></div> Sosial
        <div class="legend-box mx-3" style="background-color: #ffc107;"></div> Distribusi
        <div class="legend-box mx-3" style="background-color: #ff9874;"></div> Nerwilis
        <div class="legend-box mx-3" style="background-color: #6f42c1;"></div> IPDS
        <div class="legend-box mx-3" style="background-color: #6c757d;"></div> Tidak Diketahui
      </div>
    </div>
  </div>
</div>

<!-- Custom Style -->
<style>
  .fc-event {
    border-radius: 4px !important;
    padding: 2px 6px !important;
    font-size: 0.85rem !important;
    margin-bottom: 2px !important;
    color: white !important;
  }

  .fc-event:hover {
    opacity: 0.9;
    cursor: pointer;
  }

  .legend-box {
    width: 16px;
    height: 16px;
    margin-right: 5px;
    border: 1px solid #ccc;
    display: inline-block;
  }
</style>

<!-- FullCalendar Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const allEvents = [
      <?php foreach ($details as $d) : ?>
      <?php if (is_numeric($d['start']) && is_numeric($d['finish'])) : ?>
      {
        title: '<?= addslashes($d['nama']) ?>',
        start: '<?= date('Y-m-d', $d['start']) ?>',
        end: '<?= date('Y-m-d', $d['finish']) ?>',
        extendedProps: {
          pencacah: <?= $d['k_pencacah'] ?>,
          pengawas: <?= $d['k_pengawas'] ?>,
          penanggung: <?= $d['seksi_id'] ?>
        },
        backgroundColor: getEventColor(<?= $d['seksi_id'] ?>),
        borderColor: getEventColor(<?= $d['seksi_id'] ?>)
      },
      <?php endif; ?>
      <?php endforeach; ?>
    ];

    function getEventColor(seksi_id) {
      switch (seksi_id) {
        case 1: return '#0d6efd';   // Produksi
        case 2: return '#198754';   // Sosial
        case 3: return '#ffc107';   // Distribusi
        case 4: return '#ff9874';   // Nerwilis
        case 5: return '#6f42c1';   // IPDS
        default: return '#6c757d';  // Default
      }
    }

    function getSeksiName(seksi_id) {
      switch (seksi_id) {
        case 1: return 'Produksi';
        case 2: return 'Sosial';
        case 3: return 'Distribusi';
        case 4: return 'Nerwilis';
        case 5: return 'IPDS';
        default: return 'Tidak Diketahui';
      }
    }

    function formatTanggalIndo(dateObj) {
      const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      const tgl = dateObj.getDate();
      const bln = bulan[dateObj.getMonth()];
      const thn = dateObj.getFullYear();
      return `${tgl} ${bln} ${thn}`;
    }

    function formatBulanIndo(dateObj) {
      const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      return `${bulan[dateObj.getMonth()]} ${dateObj.getFullYear()}`;
    }

    const calendarEl = document.getElementById('calendar');
    const totalKegiatanEl = document.getElementById('totalKegiatan');
    const bulanTextEl = document.getElementById('currentMonthText');
    const filterSeksi = document.getElementById('filterSeksi');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listWeek'
      },
      showNonCurrentDates: false,
      events: [],
      datesSet: function (info) {
        const currentViewDate = info.start;
        const selectedFilter = filterSeksi.value;
        updateCalendarEvents(selectedFilter, currentViewDate);
      },
      eventClick: function (info) {
        const data = info.event.extendedProps;
        let durasi = "-";
        if (info.event.start && info.event.end) {
          const diffMs = new Date(info.event.end) - new Date(info.event.start);
          durasi = Math.round(diffMs / (1000 * 60 * 60 * 24));
        }

        Swal.fire({
          title: `<strong>${info.event.title}</strong>`,
          html: `
            <div style="text-align: left">
              <p><b>Start:</b> ${formatTanggalIndo(info.event.start)}</p>
              <p><b>Finish:</b> ${info.event.end ? formatTanggalIndo(info.event.end) : '-'}</p>
              <p><b>Durasi:</b> ${durasi} hari</p>
              <p><b>Jumlah Pencacah:</b> ${data.pencacah ?? '0'}</p>
              <p><b>Jumlah Pengawas:</b> ${data.pengawas ?? '0'}</p>
              <p><b>Penanggung Jawab:</b> ${getSeksiName(data.penanggung)}</p>
            </div>
          `,
          icon: 'info',
          showCloseButton: true,
          confirmButtonText: 'Tutup'
        });
      }
    });

    function updateCalendarEvents(filterValue, viewDate) {
      const month = viewDate.getMonth() + 1;
      const year = viewDate.getFullYear();

      let filtered = allEvents;

      if (filterValue !== 'all') {
        filtered = filtered.filter(e => e.extendedProps.penanggung == parseInt(filterValue));
      }

      // Filter berdasarkan bulan yang tampil
      filtered = filtered.filter(e => {
      const eventStart = new Date(e.start);
      const eventEnd = new Date(e.end);
      const firstDayOfMonth = new Date(year, month - 1, 1);
      const lastDayOfMonth = new Date(year, month, 0, 23, 59, 59); // hari terakhir bulan ini
    
      return (
        eventStart <= lastDayOfMonth && eventEnd >= firstDayOfMonth
      );
    });

      calendar.removeAllEvents();
      filtered.forEach(e => calendar.addEvent(e));
      totalKegiatanEl.textContent = filtered.length;
      bulanTextEl.textContent = formatBulanIndo(viewDate);
    }

    // Jalankan saat halaman load
    calendar.render();
    const today = calendar.getDate();
    updateCalendarEvents('all', today);

    // Filter seksi dinamis
    filterSeksi.addEventListener('change', function () {
      const selectedDate = calendar.getDate();
      updateCalendarEvents(this.value, selectedDate);
    });
  });
</script>


<?php endif; ?>

    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->