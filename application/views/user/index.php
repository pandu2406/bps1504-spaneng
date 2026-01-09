<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <style>
        .profile-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        .profile-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            height: 150px;
            position: relative;
        }

        .profile-img-container {
            position: absolute;
            bottom: -50px;
            left: 30px;
            border: 5px solid white;
            border-radius: 50%;
            overflow: hidden;
            background: white;
            width: 120px;
            height: 120px;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            transition: border-color 0.3s, background-color 0.3s;
        }

        .profile-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-body {
            padding-top: 60px;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 30px;
        }

        .info-label {
            font-size: 0.85rem;
            color: #858796;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 1rem;
            color: #2e343a;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .role-badge {
            position: absolute;
            bottom: 15px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            backdrop-filter: blur(5px);
        }

        /* DARK MODE SUPPORT */
        @media (prefers-color-scheme: dark) {
            .profile-card {
                background-color: #2d3748 !important; /* Dark Gray */
                color: #e2e8f0 !important;
            }

            .profile-header {
                /* Slightly darker gradient for dark mode if desired, or keep same */
                 background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
            }

            .profile-img-container {
                border-color: #2d3748 !important; /* Match card bg */
                background-color: #2d3748 !important;
            }

            .text-dark {
                color: #e2e8f0 !important;
            }
            
            .text-muted {
                color: #a0aec0 !important;
            }

            .info-label {
                 color: #a0aec0 !important;
            }

            .info-value {
                color: #e2e8f0 !important;
            }
            
            /* Border line between columns */
            .border-right {
                border-right: 1px solid #4a5568 !important;
            }
        }
    </style>

    <?php if ($user['role_id'] == 5): ?>
        <!-- LAYOUT MITRA (Role 5) -->
        <div class="card shadow profile-card mb-4">
            <div class="profile-header">
                <div class="role-badge"><i class="fas fa-user-tie mr-2"></i>Mitra Statistik</div>
                <div class="profile-img-container">
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" alt="Profile Image">
                </div>
            </div>
            <div class="profile-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="font-weight-bold text-dark mb-0"><?= $mitra['nama']; ?></h3>
                    <small class="text-muted"><i class="fas fa-clock mr-1"></i> Member since
                        <?= date('d F Y', $user['date_created']); ?></small>
                </div>

                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="info-label">NIK</div>
                        <div class="info-value"><?= $mitra['nik']; ?></div>

                        <div class="info-label">Posisi</div>
                        <div class="info-value"><?= $mitra['posisi']; ?></div>

                        <div class="info-label">Email</div>
                        <div class="info-value"><?= $mitra['email']; ?></div>

                        <div class="info-label">Info Sensus / Sobat ID</div>
                        <div class="info-value"><span
                                class="badge badge-primary px-3 py-2"><?= $mitra['sobat_id']; ?></span></div>
                    </div>
                    <div class="col-md-6 pl-md-4">
                        <div class="info-label">Wilayah Tugas</div>
                        <div class="info-value">
                            <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                            Kec. <?= $mitra['nama_kecamatan']; ?>, Desa <?= $mitra['nama_desa']; ?>
                        </div>

                        <div class="info-label">Alamat Lengkap</div>
                        <div class="info-value"><?= $mitra['alamat']; ?></div>

                        <div class="info-label">Kontak</div>
                        <div class="info-value"><i class="fab fa-whatsapp text-success mr-2"></i><?= $mitra['no_hp']; ?>
                        </div>

                        <div class="mt-4">
                            <a href="<?= base_url('kegiatan/details_mitra_kegiatan/') . $mitra['id_mitra']; ?>"
                                class="btn btn-primary btn-block shadow-sm">
                                <i class="fas fa-clipboard-list mr-2"></i> Lihat & Pilih Kegiatan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($user['role_id'] == 1): ?>
        <!-- LAYOUT ADMIN (Role 1) -->
        <div class="card shadow profile-card mb-4 col-lg-8 mx-auto p-0">
            <div class="profile-header" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                <div class="role-badge"><i class="fas fa-user-shield mr-2"></i>Administrator</div>
                <div class="profile-img-container">
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" alt="Profile Image">
                </div>
            </div>
            <div class="profile-body text-center pt-5">
                <h3 class="font-weight-bold text-dark mt-4"><?= isset($pegawai['nama']) ? $pegawai['nama'] : 'User'; ?></h3>
                <!-- Assumes 'pegawai' data is passed from controller -->
                <p class="text-muted mb-4"><?= $user['email']; ?></p>

                <div class="row justify-content-center text-left mt-4">
                    <div class="col-md-8">
                        <div class="info-label text-center">Bergabung Sejak</div>
                        <div class="info-value text-center text-primary font-weight-bold" style="font-size: 1.2rem;">
                            <?= date('d F Y', $user['date_created']); ?>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-2">
                    <a href="<?= base_url('user/edit'); ?>" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- LAYOUT PEGAWAI / DEFAULT -->
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Welcome Card -->
            <div class="card shadow profile-card mb-5">
                <div class="profile-header"
                    style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); height: 120px;">
                    <div class="role-badge"><i class="fas fa-user mr-2"></i>Pegawai</div>
                </div>
                <div class="card-body pl-5 pr-5 pb-4" style="margin-top: -60px;">
                    <div class="d-flex align-items-end mb-4">
                        <div class="profile-img-container position-relative"
                            style="bottom: 0; left: 0; margin-right: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                            <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" alt="Profile">
                        </div>
                        <div class="pb-2">
                            <h4 class="font-weight-bold text-gray-900 mb-0">Halo, User!</h4>
                            <span class="text-muted"><?= $user['email']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards for Employee/Other -->
            <div class="row">
                <!-- Jumlah Mitra -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 d-flex align-items-center">
                                        Jumlah Mitra
                                        <select id="userYearStatsSelect"
                                            class="form-control form-control-sm border-0 bg-transparent py-0 px-1 ml-2 font-weight-bold text-primary"
                                            style="width: auto; height: auto; font-size: 0.75rem; text-decoration: underline; cursor: pointer;">
                                            <option value="2024">2024</option>
                                            <option value="2025" selected>2025</option>
                                            <option value="2026">2026</option>
                                        </select>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" id="userMitraCountDisplay"><?= $mitra_2025 ?></div>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.getElementById('userYearStatsSelect').addEventListener('change', function() {
                                                const counts = {
                                                    '2024': <?= $mitra_2024 ?>,
                                                    '2025': <?= $mitra_2025 ?>,
                                                    '2026': <?= $mitra_2026 ?>
                                                };
                                                document.getElementById('userMitraCountDisplay').textContent = counts[this.value];
                                            });
                                        });
                                    </script>
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
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Pegawai
                                    </div>
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
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kegiatan Berjalan
                                    </div>
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
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kegiatan Akan
                                        Datang</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_akan_datang ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-plus fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Stats / Legend Section (Rest of existing content kept similar but cleaned) -->
            <div class="row">
                <!-- Kegiatan Selesai -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 bg-secondary text-white">
                            <h6 class="m-0 font-weight-bold">Kegiatan Selesai</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="form-row justify-content-center mb-2">
                                <div class="col-4">
                                    <select class="form-control form-control-sm" id="filter_tahun_selesai" style="font-size: 0.7rem;">
                                        <option value="all">Thn</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control form-control-sm" id="filter_bulan_selesai" style="font-size: 0.7rem;">
                                        <option value="all">Bln</option>
                                        <?php
                                        $months = [
                                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                                            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                                        ];
                                        foreach ($months as $num => $name) {
                                            echo "<option value='$num'>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="display-4 font-weight-bold text-gray-800" id="k_selesai_val"><?= $k_selesai ?></div>
                            <small>Total Kegiatan</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 bg-info text-white d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold">Belum Dinilai</h6>
                            <span class="badge badge-light text-info"><?= count($belum_dinilai) ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($belum_dinilai)): ?>
                                <div class="mb-2">
                                    <button class="btn btn-sm btn-block btn-info" type="button" data-toggle="collapse"
                                        data-target="#collapseKegiatan">
                                        Lihat Daftar
                                    </button>
                                </div>
                                <div class="collapse" id="collapseKegiatan">
                                    <ul class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                                        <?php foreach ($belum_dinilai as $k): ?>
                                            <li class="list-group-item p-2 small">
                                                <a href="<?= base_url('ranking/cek_progress/' . $k['id']) ?>"
                                                    class="text-info font-weight-bold">
                                                    <?= $k['nama'] ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-success mt-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>Semua aman!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Rekap Incomplete -->
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 bg-danger text-white d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold">Rekap Belum Lengkap</h6>
                            <span class="badge badge-light text-danger"><?= $jumlah_rekap_tidak_lengkap ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($rekap_tidak_lengkap)): ?>
                                <div class="mb-2">
                                    <button class="btn btn-sm btn-block btn-danger" type="button" data-toggle="collapse"
                                        data-target="#collapseMitra">
                                        Lihat Daftar
                                    </button>
                                </div>
                                <div class="collapse" id="collapseMitra">
                                    <ul class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                                        <?php foreach ($rekap_tidak_lengkap as $m): ?>
                                            <li class="list-group-item p-2 small">
                                                <a href="<?= base_url('rekap/details_mitra/' . $m['id_mitra']) ?>"
                                                    class="text-danger font-weight-bold">
                                                    <?= $m['nama'] ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-success mt-3">
                                    <i class="fas fa-check-double fa-2x mb-2"></i><br>Semua rekap lengkap!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kalender Kegiatan (Existing) -->
            <div class="card shadow mt-4 mb-5">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kalender Kegiatan</h6>
                    <a href="#" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-calendar-alt mr-1"></i> Full
                        View</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="mb-2">
                            <label for="filterSeksi" class="font-weight-bold mr-2">Filter Seksi:</label>
                            <select id="filterSeksi" class="custom-select custom-select-sm d-inline-block"
                                style="width: 200px;">
                                <option value="all">Semua Seksi</option>
                                <option value="1">Produksi</option>
                                <option value="2">Sosial</option>
                                <option value="3">Distribusi</option>
                                <option value="4">Nerwilis</option>
                                <option value="5">IPDS</option>
                            </select>
                        </div>
                        <div class="text-right font-weight-bold mb-2">
                            Total: <span id="totalKegiatan" class="badge badge-primary p-2" style="font-size:1rem">0</span>
                        </div>
                    </div>

                    <div id='calendar'></div>

                    <!-- Legend Warna Seksi -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="font-weight-bold text-dark mb-3">Legenda Seksi:</h6>
                        <div class="d-flex flex-wrap">
                            <div class="mr-4 mb-2"><span class="badge mr-1" style="background-color: #0d6efd;">&nbsp;</span>
                                Produksi</div>
                            <div class="mr-4 mb-2"><span class="badge mr-1" style="background-color: #198754;">&nbsp;</span>
                                Sosial</div>
                            <div class="mr-4 mb-2"><span class="badge mr-1" style="background-color: #ffc107;">&nbsp;</span>
                                Distribusi</div>
                            <div class="mr-4 mb-2"><span class="badge mr-1" style="background-color: #ff9874;">&nbsp;</span>
                                Nerwilis</div>
                            <div class="mr-4 mb-2"><span class="badge mr-1" style="background-color: #6f42c1;">&nbsp;</span>
                                IPDS</div>
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
                    border: none;
                    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
                }

                .fc-event:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 5px rgba(0, 0, 0, 0.2);
                    filter: brightness(1.05);
                    cursor: pointer;
                }

                .fc-toolbar-title {
                    font-size: 1.25rem !important;
                    font-weight: 700 !important;
                }

                .fc-button-primary {
                    background-color: #4e73df !important;
                    border-color: #4e73df !important;
                }
            </style>

            <!-- FullCalendar Script (Kept same logic, just format fixes) -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const allEvents = [
                        <?php foreach ($details as $d): ?>
                                      <?php if (is_numeric($d['start']) && is_numeric($d['finish'])): ?>
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

                    const calendarEl = document.getElementById('calendar');
                    const totalKegiatanEl = document.getElementById('totalKegiatan');
                    const filterSeksi = document.getElementById('filterSeksi');

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek'
                        },
                        themeSystem: 'bootstrap',
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

                        // Strict month filtering
                        filtered = filtered.filter(e => {
                            // Logic to detect if event overlaps with this month
                            const eventStart = new Date(e.start);
                            const eventEnd = new Date(e.end);
                            const firstDayOfMonth = new Date(year, month - 1, 1);
                            const lastDayOfMonth = new Date(year, month, 0, 23, 59, 59);

                            return (eventStart <= lastDayOfMonth && eventEnd >= firstDayOfMonth);
                        });

                        calendar.removeAllEvents();
                        filtered.forEach(e => calendar.addEvent(e));
                        totalKegiatanEl.textContent = filtered.length;
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Ensure this script runs after jQuery is loaded
    $(document).ready(function() {
        function updateKegiatanSelesaiUser() {
             var tahun = $('#filter_tahun_selesai').val();
             var bulan = $('#filter_bulan_selesai').val();
             
             $.ajax({
                 url: '<?= base_url("user/get_kegiatan_selesai_count") ?>',
                 type: 'POST',
                 data: { tahun: tahun, bulan: bulan },
                 dataType: 'json',
                 success: function(response) {
                     $('#k_selesai_val').text(response.count);
                 },
                 error: function() {
                     console.log('Error fetching data');
                 }
             });
        }

        $('#filter_tahun_selesai, #filter_bulan_selesai').change(function() {
            updateKegiatanSelesaiUser();
        });
    });
</script>