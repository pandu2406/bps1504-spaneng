<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg">

            <!-- Heading Periode -->
            <h5 class="mb-3 font-weight-bold text-dark">Periode: <?= $label_periode; ?></h5>

            <!-- Nav Tabs for Years -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?= ($selected_tahun == 0) ? 'active font-weight-bold text-primary border-bottom-0' : 'text-secondary' ?>"
                        href="<?= base_url('rekap/bk_mitra?bulan=' . $selected_bulan . '&tahun=0') ?>">
                        Semua Tahun
                    </a>
                </li>
                <?php
                $available_years = [2024, 2025, 2026];
                foreach ($available_years as $y):
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($selected_tahun == $y) ? 'active font-weight-bold text-primary border-bottom-0' : 'text-secondary' ?>"
                            href="<?= base_url('rekap/bk_mitra?bulan=' . $selected_bulan . '&tahun=' . $y) ?>">
                            <i class="fas fa-calendar-alt mr-1"></i> Tahun <?= $y ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($selected_bulan == 0 || $selected_tahun == 0): ?>
                <div class="status-alert alert-warning">
                    <strong>Catatan:</strong> Silakan pilih <b>bulan dan tahun tertentu</b> untuk melihat rekap honor dan
                    status kelebihan honor mitra.
                </div>
            <?php else: ?>
                <?php if (!empty($mitra_overlimit)): ?>
                    <div class="status-alert alert-danger">
                        <strong>⚠️ Peringatan!</strong> Ditemukan mitra yang melebihi honor maksimum pada periode
                        <strong><?= $label_periode; ?></strong>:
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="table-danger">
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Total Honor</th>
                                    <th>Ambang Batas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mitra_overlimit as $o): ?>
                                    <tr>
                                        <td>
                                            <strong>
                                                <a class="text-capitalize"
                                                    href="<?= base_url('rekap/details_mitra/' . $o['id_mitra'] . '/' . $selected_bulan . '/' . $selected_tahun); ?>">
                                                    <?= $o['nama']; ?>
                                                </a>
                                            </strong>
                                        </td>
                                        <td><?= $o['posisi']; ?></td>
                                        <td style="color:darkred">Rp<?= number_format($o['total'], 0, ',', '.'); ?></td>
                                        <td>Rp<?= number_format($o['batas'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="status-alert alert-success">
                        ✅ Tidak ada mitra yang melebihi batas honor pada periode <strong><?= $label_periode; ?></strong>.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($chart_status)): ?>
                <div class="row mb-4">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Status Beban Kerja (<?= $label_periode; ?>)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-danger"></i> Overload
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-success"></i> Aman
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <br>
            <?php
            $bulan = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];
            ?>

            <!-- Dropdown Bulan -->
            <div class="dropdown d-inline-block mb-3 mr-2">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <?= ($selected_bulan == 0) ? 'Semua Bulan' : $bulan[$selected_bulan - 1]; ?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="<?= base_url('rekap/bk_mitra?bulan=0&tahun=' . $selected_tahun); ?>">Semua Bulan</a>
                    <?php
                    foreach ($bulan as $i => $b): ?>
                        <a class="dropdown-item"
                            href="<?= base_url('rekap/bk_mitra?bulan=' . ($i + 1) . '&tahun=' . $selected_tahun); ?>">
                            <?= $b; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>



            <a href="<?= site_url('rekap/export_excel/' . $selected_bulan . '/' . $selected_tahun); ?>"
                class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>

            <a href="<?= site_url('rekap/export_nilai_excel/' . $selected_bulan . '/' . $selected_tahun); ?>"
                class="btn btn-danger">
                <i class="fas fa-file-excel"></i> Export Penilaian
            </a>

            <?php
            $jumlah_terlibat = 0;
            foreach ($rekap as $r) {
                if ((int) $r['jk'] > 0 || (int) $r['jk_p'] > 0) {
                    $jumlah_terlibat++;
                }
            }
            ?>

            <?php if ($selected_bulan != 0 && $selected_tahun != 0): ?>
                <div class="alert-info font-weight-bold mt-3 p-2 rounded">
                    📊 Jumlah Mitra Terlibat: <span style="color:darkblue"><?= $jumlah_terlibat; ?> mitra</span>
                </div>
            <?php endif; ?>


            <!-- Tabel Data -->
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered" id="mydata">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Status Honor</th>
                            <th scope="col">Pengawasan</th>
                            <th scope="col">Pencacahan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php $i = 1; ?>
                        <?php foreach ($rekap as $r):
                            $is_overload = false;
                            if (isset($mitra_overlimit)) {
                                foreach ($mitra_overlimit as $mo) {
                                    if ($mo['id_mitra'] == $r['id_mitra']) {
                                        $is_overload = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            <tr align="center">
                                <th scope="row"><?= $i++; ?></th>
                                <td class="text-left font-weight-bold text-capitalize"><?= $r['nama']; ?></td>
                                <td>
                                    <?php if ($selected_bulan > 0 && $selected_tahun > 0): ?>
                                        <?php if ($is_overload): ?>
                                            <span class="badge badge-danger">OVERLOAD</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">AMAN</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $r['jk']; ?></td>
                                <td><?= $r['jk_p']; ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('rekap/details_mitra/' . $r['id_mitra'] . '/' . $selected_bulan . '/' . $selected_tahun); ?>"
                                            class="btn btn-sm btn-info" title="Detail Periode Ini">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                        <a href="<?= base_url('rekap/riwayat_mitra/' . $r['id_mitra']); ?>"
                                            class="btn btn-sm btn-dark" title="Riwayat Tahunan">
                                            <i class="fas fa-history"></i> Riwayat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Chart JS -->
            <?php if (isset($chart_status)): ?>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Set new default font family and font color to mimic Bootstrap's default styling
                    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                    Chart.defaults.global.defaultFontColor = '#858796';

                    // Pie Chart Example
                    var ctx = document.getElementById("myPieChart");
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ["Overload", "Aman"],
                            datasets: [{
                                data: [<?= $chart_status['overload']; ?>, <?= $chart_status['safe']; ?>],
                                backgroundColor: ['#e74a3b', '#1cc88a'],
                                hoverBackgroundColor: ['#c92416', '#17a673'],
                                hoverBorderColor: "rgba(234, 236, 244, 1)",
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                caretPadding: 10,
                            },
                            legend: {
                                display: false
                            },
                            cutoutPercentage: 80,
                        },
                    });
                </script>
            <?php endif; ?>

        </div>
    </div>
    <br>
</div>
<!-- End of Main Content -->
</div>
<!-- Optional Script -->
<script>
    document.querySelectorAll('.periode-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            const label = this.getAttribute('data-label');
            document.getElementById('dropdownPeriodeBtn').innerText = label;
        });
    });
</script>