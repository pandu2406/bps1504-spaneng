<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-1">
                        <div class="col mr-2">
                            <div
                                class="text-xs font-weight-bold text-primary text-uppercase mb-1 d-flex align-items-center">
                                Mitra Aktif
                                <select id="yearStatsSelect"
                                    class="form-control form-control-sm border-0 bg-transparent py-0 px-1 ml-2 font-weight-bold text-primary"
                                    style="width: auto; height: auto; font-size: 0.75rem; text-decoration: underline; cursor: pointer;">
                                    <option value="2024">2024</option>
                                    <option value="2025" selected>2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="mitraCountDisplay">
                                <?= $mitra_2025 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jumlah Pegawai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pegawai ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kegiatan
                                <br>Berjalan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_berjalan ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kegiatan <br>Yang
                                akan datang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_akan_datang ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kegiatan <br>Yang
                                Sudah Selesai
                            </div>
                            <div class="form-row mb-2 mt-2">
                                <div class="col-6">
                                    <select class="form-control form-control-sm" id="filter_tahun_selesai"
                                        style="font-size: 0.7rem;">
                                        <option value="all">Semua Thn</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-control form-control-sm" id="filter_bulan_selesai"
                                        style="font-size: 0.7rem;">
                                        <option value="all">Semua Bln</option>
                                        <?php
                                        $months = [
                                            1 => 'Jan',
                                            2 => 'Feb',
                                            3 => 'Mar',
                                            4 => 'Apr',
                                            5 => 'Mei',
                                            6 => 'Jun',
                                            7 => 'Jul',
                                            8 => 'Agu',
                                            9 => 'Sep',
                                            10 => 'Okt',
                                            11 => 'Nov',
                                            12 => 'Des'
                                        ];
                                        foreach ($months as $num => $name) {
                                            echo "<option value='$num'>$name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="k_selesai_val"><?= $k_selesai ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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

                    <?php if (!empty($belum_dinilai)): ?>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse"
                                data-target="#collapseKegiatan">
                                Lihat Selengkapnya
                            </button>
                            <div class="collapse mt-2" id="collapseKegiatan">
                                <small class="text-muted">Daftar kegiatan:</small>
                                <div class="row">
                                    <?php
                                    $chunks = array_chunk($belum_dinilai, ceil(count($belum_dinilai) / 2));
                                    foreach ($chunks as $chunk):
                                        ?>
                                        <div class="col-md-6">
                                            <ul class="pl-3 mb-0" style="list-style-type: disc;">
                                                <?php foreach ($chunk as $k): ?>
                                                    <li style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                                        <a href="<?= base_url('ranking/cek_progress/' . $k['id']) ?>"
                                                            class="text-info" style="text-decoration: none;">
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
                    <?php else: ?>
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

                    <?php if (!empty($rekap_tidak_lengkap)): ?>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger" type="button" data-toggle="collapse"
                                data-target="#collapseMitra">
                                Lihat Selengkapnya
                            </button>
                            <div class="collapse mt-2" id="collapseMitra">
                                <small class="text-muted">Daftar mitra:</small>
                                <div class="row">
                                    <?php
                                    $chunks = array_chunk($rekap_tidak_lengkap, ceil(count($rekap_tidak_lengkap) / 2));
                                    foreach ($chunks as $chunk):
                                        ?>
                                        <div class="col-md-6">
                                            <ul class="pl-3 mb-0" style="list-style-type: disc;">
                                                <?php foreach ($chunk as $m): ?>
                                                    <li style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                                        <a href="<?= base_url('rekap/details_mitra/' . $m['id_mitra']) ?>"
                                                            class="text-danger" style="text-decoration: none;">
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
                    <?php else: ?>
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
                        <a href="https://drive.google.com/file/d/1oiSp2Fb1Z7612BMk5sMCW_OA64IfPdFs/view?usp=sharing"
                            target="_blank" class="btn btn-sm btn-warning text-white">
                            Buka Panduan
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <!-- Dashboard Section: Leaderboard -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3">
                    <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-medal mr-2"></i>3 Mitra Terbaik
                            (Kinerja & Pengalaman)</h6>
                    </div>
                    <form action="" method="get" class="form-inline">
                        <select name="bulan" class="form-control form-control-sm mr-2" style="font-size: 0.8rem;">
                            <option value="">- Semua Bulan -</option>
                            <?php
                            $months = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];
                            foreach ($months as $key => $val) {
                                $selected = ($this->input->get('bulan') == $key) ? 'selected' : '';
                                echo "<option value='$key' $selected>$val</option>";
                            }
                            ?>
                        </select>
                        <select name="tahun" class="form-control form-control-sm mr-2" style="font-size: 0.8rem;">
                            <option value="">- Semua Tahun -</option>
                            <?php
                            $current_year = date('Y');
                            for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                $selected = ($this->input->get('tahun') == $i) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="leaderboardTable">
                            <thead class="bg-light">
                                <tr style="cursor: pointer;">
                                    <th class="py-2" style="font-size: 0.8rem;" onclick="sortTable(0)">Nama <i
                                            class="fas fa-sort ml-1 text-gray-400"></i></th>
                                    <th class="py-2 text-center" style="font-size: 0.8rem;"
                                        title="Jumlah Kegiatan yang Diikuti" onclick="sortTable(1)">Kegiatan <i
                                            class="fas fa-sort ml-1 text-gray-400"></i></th>
                                    <th class="py-2 text-right" style="font-size: 0.8rem;"
                                        title="Rata-rata Nilai dari Seluruh Penilaian" onclick="sortTable(2)">Skor Akhir
                                        <i class="fas fa-sort ml-1 text-gray-400"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($top_mitra)): ?>
                                    <?php foreach ($top_mitra as $index => $tm): ?>
                                        <tr>
                                            <td class="py-2" data-sort="<?= $tm['nama'] ?>">
                                                <small class="font-weight-bold text-dark d-block"><?= $tm['nama'] ?></small>
                                                <small class="text-muted"><?= $tm['nik'] ?></small>
                                            </td>
                                            <td class="py-2 text-center align-middle" data-sort="<?= $tm['total_kegiatan'] ?>">
                                                <span class="badge badge-info"><?= $tm['total_kegiatan'] ?></span>
                                            </td>
                                            <td class="py-2 text-right align-middle" data-sort="<?= $tm['rata_rata'] ?>">
                                                <span
                                                    class="font-weight-bold <?= $index < 3 ? 'text-success' : 'text-primary' ?>">
                                                    <?= number_format($tm['rata_rata'], 2) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">Belum ada data penilaian
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2 bg-white text-center">
                    <a href="<?= base_url('penilaian') ?>"
                        class="text-xs font-weight-bold text-primary text-uppercase">Lihat Detail Penilaian</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Section: Info & Instructions -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kegiatan & Monitoring</h6>
                </div>
                <!-- Application Description -->
                <div class="card-body bg-light border-bottom">
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aplikasi ini berfungsi sebagai <strong>Pengecekan kegiatan survei</strong>,
                        <strong>pengalokasian kegiatan</strong>, <strong>evaluasi mitra</strong>, dan <strong>monitoring
                            beban kerja survei mitra/pegawai</strong> serta <strong>honor mitra</strong>.
                    </div>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Aplikasi <strong>SPANENG</strong> dirancang untuk memudahkan monitoring
                        kegiatan dan evaluasi kinerja mitra BPS Kabupaten Batang Hari secara transparan dan akuntabel.
                    </p>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded p-2 mr-3"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold small">Cek Jadwal</h6>
                                    <small class="text-muted">Pantau jadwal survei pada kalender di bawah.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded p-2 mr-3"
                                    style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold small">Evaluasi Mitra</h6>
                                    <small class="text-muted">Nilailah mitra segera setelah kegiatan berakhir.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info border-0 shadow-sm mt-2">
                        <small><i class="fas fa-info-circle mr-2"></i><b>Tips:</b> Gunakan tab di menu Master Mitra
                            untuk melihat data mitra per tahun (2025/2026).</small>
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
                    <label for="filterTahun" class="font-weight-bold">Tahun:</label>
                    <select id="filterTahun" class="form-control form-control-sm d-inline-block mr-3"
                        style="width: 100px;">
                        <option value="2024">2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                    </select>

                    <label for="filterSeksi" class="font-weight-bold">Filter Seksi:</label>
                    <select id="filterSeksi" class="form-control form-control-sm d-inline-block" style="width: 180px;">
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

    <!-- Container for Event Details (Initially Hidden) -->
    <div class="card shadow mt-4" id="eventDetailBox" style="display: none; transition: all 0.3s ease;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Detail Kegiatan
                Terpilih</h6>
            <button class="btn btn-sm btn-circle btn-light"
                onclick="document.getElementById('eventDetailBox').style.display='none'"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="card-body" id="eventDetailContent">
            <!-- Content filled by specific JS -->
        </div>
    </div>

    <!-- SweetAlert2 (REQUIRED for popups) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Style -->
    <style>
        .fc-event {
            border-radius: 4px !important;
            padding: 2px 6px !important;
            font-size: 0.85rem !important;
            margin-bottom: 2px !important;
            color: white !important;
            cursor: pointer;
        }

        .fc-event:hover {
            opacity: 0.9;
        }

        /* Additional enhancements requested */
        #calendar {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
        }

        .fc-header-toolbar {
            margin-bottom: 20px !important;
        }

        .legend-box {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            border: 1px solid #ccc;
            display: inline-block;
        }

        /* DARK MODE SUPPORT */
        @media (prefers-color-scheme: dark) {

            /* Make Calendar Dark */
            #calendar {
                background-color: #2d3748 !important;
                /* Dark Gray */
                color: #e2e8f0 !important;
                border: 1px solid #4a5568;
            }

            .fc-theme-standard .fc-scrollgrid {
                border-color: #4a5568 !important;
            }

            .fc-theme-standard td,
            .fc-theme-standard th {
                border-color: #4a5568 !important;
            }

            .fc-col-header-cell-cushion,
            .fc-daygrid-day-number {
                color: #e2e8f0 !important;
                text-decoration: none !important;
            }

            .fc-daygrid-day:hover {
                background-color: #4a5568 !important;
            }

            #currentMonthText {
                color: #e2e8f0 !important;
            }

            label[for="filterTahun"],
            label[for="filterSeksi"] {
                color: #e2e8f0 !important;
            }

            /* Dropdowns in dark mode */
            #filterTahun,
            #filterSeksi {
                background-color: #4a5568;
                color: #fff;
                border-color: #718096;
            }

            /* Buttons */
            .fc-button-primary {
                background-color: #4c51bf !important;
                border-color: #4c51bf !important;
            }

            .fc-button-primary:disabled {
                background-color: #4a5568 !important;
                border-color: #4a5568 !important;
            }

            /* SweetAlert2 Dark Theme Overrides */
            div:where(.swal2-container) div:where(.swal2-popup) {
                background: #2d3748 !important;
                color: #e2e8f0 !important;
            }

            div:where(.swal2-container) .swal2-title {
                color: #e2e8f0 !important;
            }

            div:where(.swal2-container) .swal2-html-container {
                color: #cbd5e0 !important;
            }
        }
    </style>

    <!-- FullCalendar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Updated script with safety checks for empty values
            const allEvents = [
                <?php foreach ($details as $d): ?>
                                                          <?php if (is_numeric($d['start']) && is_numeric($d['finish'])): ?>
                                                                                                  {
                            title: '<?= addslashes($d['nama']) ?>',
                            start: '<?= date('Y-m-d', $d['start']) ?>',
                            end: '<?= date('Y-m-d', $d['finish'] + 86400) ?>',
                            extendedProps: {
                                pencacah: <?= (int) $d['k_pencacah'] ?>,
                                pengawas: <?= (int) $d['k_pengawas'] ?>,
                                penanggung: <?= (int) $d['seksi_id'] ?>
                            },
                            backgroundColor: getEventColor(<?= (int) $d['seksi_id'] ?>),
                            borderColor: getEventColor(<?= (int) $d['seksi_id'] ?>)
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
            const filterTahun = document.getElementById('filterTahun');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                showNonCurrentDates: false,
                dayMaxEvents: true,
                events: [],
                datesSet: function (info) {
                    const currentViewDate = info.start;
                    // Sync year filter with calendar view
                    filterTahun.value = currentViewDate.getFullYear();
                    updateCalendarEvents(filterSeksi.value, currentViewDate);
                },
                eventClick: function (info) {
                    const data = info.event.extendedProps;
                    let durasi = "-";
                    let dateRange = formatTanggalIndo(info.event.start);

                    if (info.event.end) {
                        const diffMs = new Date(info.event.end) - new Date(info.event.start);
                        const days = Math.round(diffMs / (1000 * 60 * 60 * 24));
                        durasi = days + " Hari";

                        // Fix end date display (subtract 1 day because FC end is exclusive)
                        const endDate = new Date(info.event.end);
                        endDate.setDate(endDate.getDate() - 1);
                        dateRange += ` s.d ${formatTanggalIndo(endDate)}`;
                    } else {
                        durasi = "1 Hari";
                    }

                    // Display details in the detail box instead of popup
                    const detailBox = document.getElementById('eventDetailBox');
                    const detailContent = document.getElementById('eventDetailContent');

                    if (detailBox && detailContent) {
                        detailBox.style.display = 'block';
                        detailContent.innerHTML = `
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="font-weight-bold text-primary mb-3">${info.event.title}</h5>
                                    <table class="table table-bordered table-sm mb-0" style="font-size:0.9rem;">
                                        <tr><th width="30%">Waktu</th><td>${dateRange}</td></tr>
                                        <tr><th>Durasi</th><td>${durasi}</td></tr>
                                        <tr><th>Pencacah</th><td>${data.pencacah} orang</td></tr>
                                        <tr><th>Pengawas</th><td>${data.pengawas} orang</td></tr>
                                        <tr><th>Penanggung Jawab</th><td>${getSeksiName(data.penanggung)}</td></tr>
                                    </table>
                                </div>
                            </div>
                        `;
                        // Scroll to detail box
                        detailBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
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
                    const lastDayOfMonth = new Date(year, month, 0, 23, 59, 59);

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

            // Filter seksi dinamis
            filterSeksi.addEventListener('change', function () {
                updateCalendarEvents(this.value, calendar.getDate());
            });

            // Filter tahun dinamis
            filterTahun.addEventListener('change', function () {
                const year = parseInt(this.value);
                const currentMonth = calendar.getDate().getMonth();
                calendar.gotoDate(new Date(year, currentMonth, 1));
            });

            // Add listener for year stats select to update dashboard number
            document.getElementById('yearStatsSelect').addEventListener('change', function () {
                const counts = {
                    '2024': <?= $mitra_2024 ?>,
                    '2025': <?= $mitra_2025 ?>,
                    '2026': <?= $mitra_2026 ?>
                };
                document.getElementById('mitraCountDisplay').textContent = counts[this.value];
            });
        });
    </script>

    <script>
        /**
         * Simple Table Sorting Logic
         */
        let currentSortCol = -1;
        let isAsc = true;

        function sortTable(n) {
            const table = document.getElementById("leaderboardTable");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            // If row is "No data", don't sort
            if (rows.length === 1 && rows[0].cells.length <= 1) return;

            // Toggle sort direction
            if (currentSortCol === n) {
                isAsc = !isAsc;
            } else {
                isAsc = true;
                currentSortCol = n;
            }

            // Perform sort
            rows.sort((a, b) => {
                let x = a.cells[n].getAttribute("data-sort");
                let y = b.cells[n].getAttribute("data-sort");

                // Check if numeric
                if (!isNaN(parseFloat(x)) && isFinite(x) && !isNaN(parseFloat(y)) && isFinite(y)) {
                    return isAsc ? (parseFloat(x) - parseFloat(y)) : (parseFloat(y) - parseFloat(x));
                }

                // Default string sort
                return isAsc ? x.localeCompare(y) : y.localeCompare(x);
            });

            // Re-append rows
            rows.forEach(row => tbody.appendChild(row));

            // Update icons
            const headers = table.querySelectorAll("th i");
            headers.forEach((icon, idx) => {
                icon.className = "fas fa-sort ml-1 text-gray-400"; // Reset
                if (idx === n) {
                    icon.className = isAsc ? "fas fa-sort-up ml-1 text-primary" : "fas fa-sort-down ml-1 text-primary";
                }
            });
        }
    </script>
</div>
<!-- /.container-fluid -->

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function updateKegiatanSelesai() {
            var tahun = $('#filter_tahun_selesai').val();
            var bulan = $('#filter_bulan_selesai').val();

            $.ajax({
                url: '<?= base_url("admin/get_kegiatan_selesai_count") ?>',
                type: 'POST',
                data: { tahun: tahun, bulan: bulan },
                dataType: 'json',
                success: function (response) {
                    $('#k_selesai_val').text(response.count);
                },
                error: function () {
                    console.log('Error fetching data');
                }
            });
        }

        $('#filter_tahun_selesai, #filter_bulan_selesai').change(function () {
            updateKegiatanSelesai();
        });
    });
</script>
<!-- End of Main Content -->