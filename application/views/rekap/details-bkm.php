<!-- Begin Page Content -->
<div class="container-fluid">

    <?= $this->session->flashdata('message'); ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Mitra: <?= $mitra['nama']; ?></h1>
        <a href="<?= base_url('rekap/bk_mitra'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Define Nama Bulan -->
    <?php
    $nama_bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    ?>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="dropdown d-inline-block">
                <button class="btn btn-primary dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter fa-sm text-white-50 mr-2"></i>
                    <?= ($selected_bulan == 0) ? 'Pilih Bulan (Semua)' : $nama_bulan[$selected_bulan]; ?>
                </button>
                <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuButton">
                    <h6 class="dropdown-header">Filter Bulan:</h6>
                    <a class="dropdown-item <?= ($selected_bulan == 0) ? 'active' : '' ?>"
                        href="<?= base_url('rekap/details_mitra/' . $mitra['id_mitra'] . '/0/' . $selected_tahun); ?>">Semua
                        Bulan</a>
                    <div class="dropdown-divider"></div>
                    <?php foreach ($nama_bulan as $key => $val): ?>
                        <a class="dropdown-item <?= ($selected_bulan == $key) ? 'active' : '' ?>"
                            href="<?= base_url('rekap/details_mitra/' . $mitra['id_mitra'] . '/' . $key . '/' . $selected_tahun); ?>">
                            <?= $val; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Tahunan -->
    <div class="row">
        <!-- Annual Summary Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Honor Tahun <?= $selected_tahun; ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp<?= number_format($annual_summary['total_honor'] ?? 0, 0, ',', '.'); ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= $annual_summary['jumlah_kegiatan'] ?? 0; ?> Kegiatan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Period Info -->
        <div class="col-xl-9 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <?php
                            if ($selected_bulan == 0 && $selected_tahun == 0) {
                                $periode_text = "Semua Tahun Semua Bulan";
                            } elseif ($selected_bulan == 0) {
                                $periode_text = "Semua Bulan Tahun " . $selected_tahun;
                            } elseif ($selected_tahun == 0) {
                                $periode_text = $nama_bulan[$selected_bulan] . " Semua Tahun";
                            } else {
                                $periode_text = $nama_bulan[$selected_bulan] . " " . $selected_tahun;
                            }
                            ?>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Periode Terpilih</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $periode_text; ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Menampilkan detail kegiatan berdasarkan filter yang dipilih.
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logic Perhitungan Total Honor View -->
    <?php
    $total = 0;
    $total_posisi_1 = 0;
    $total_posisi_2 = 0;
    $total_posisi_3_ppl = 0;
    $total_posisi_3_pml = 0;
    $total_posisi_4 = 0;

    foreach ($details as $d) {
        $honor_sekarang = $d['total_honor'] ?? 0;
        $total += $honor_sekarang;

        if ($selected_bulan != 0 && $selected_tahun != 0) {
            $bulan_finish = (int) date('n', $d['finish']);
            $tahun_finish = (int) date('Y', $d['finish']);

            if ($bulan_finish == $selected_bulan && $tahun_finish == $selected_tahun) {
                if ($d['posisi_id'] == 1) {
                    $total_posisi_1 += $honor_sekarang;
                } elseif ($d['posisi_id'] == 2) {
                    $total_posisi_2 += $honor_sekarang;
                } elseif ($d['posisi_id'] == 3) {
                    if (isset($d['peran'])) {
                        if (strtolower($d['peran']) == 'pencacah') {
                            $total_posisi_3_ppl += $honor_sekarang;
                        } elseif (strtolower($d['peran']) == 'pengawas') {
                            $total_posisi_3_pml += $honor_sekarang;
                        }
                    } else {
                        if (stripos($d['namakeg'], 'pml') !== false) {
                            $total_posisi_3_pml += $honor_sekarang;
                        } elseif (stripos($d['namakeg'], 'ppl') !== false) {
                            $total_posisi_3_ppl += $honor_sekarang;
                        }
                    }
                }
            }
        }
    }
    ?>

    <!-- Status Validasi Card -->
    <div class="card shadow mb-4">
        <a href="#collapseStatus" class="d-block card-header py-3" data-toggle="collapse" role="button"
            aria-expanded="true" aria-controls="collapseStatus">
            <h6 class="m-0 font-weight-bold text-secondary">Status Validasi Honor (Bulan Ini)</h6>
        </a>
        <div class="collapse show" id="collapseStatus">
            <div class="card-body">
                <?php if ($selected_bulan != 0 && $selected_tahun != 0): ?>
                    <?php
                    $batas_honor = [
                        1 => 3303000,
                        2 => 3056000,
                        3 => ['PPL' => 4624000, 'PML' => 5120000],
                        4 => 3386000,
                    ];
                    ?>

                    <div class="row">
                        <?php foreach ($posisi_list as $posisi): ?>
                            <?php
                            $id = $posisi['id'];
                            $nama = $posisi['posisi'];
                            ?>

                            <?php if ($id == 3): ?>
                                <!-- PPL -->
                                <div class="col-md-6 mb-2">
                                    <?php if ($total_posisi_3_ppl >= $batas_honor[3]['PPL']): ?>
                                        <div class="card border-left-danger shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                            <?= $nama ?> (PPL) - OVERLOAD
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_posisi_3_ppl, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas_honor[3]['PPL'], 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="card border-left-success shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            <?= $nama ?> (PPL) - AMAN
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_posisi_3_ppl, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas_honor[3]['PPL'], 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- PML -->
                                <div class="col-md-6 mb-2">
                                    <?php if ($total_posisi_3_pml >= $batas_honor[3]['PML']): ?>
                                        <div class="card border-left-danger shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                            <?= $nama ?> (PML) - OVERLOAD
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_posisi_3_pml, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas_honor[3]['PML'], 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="card border-left-success shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            <?= $nama ?> (PML) - AMAN
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_posisi_3_pml, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas_honor[3]['PML'], 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            <?php else: ?>
                                <?php
                                $total_pos = ${"total_posisi_" . $id} ?? 0;
                                $batas = $batas_honor[$id] ?? 0;
                                ?>
                                <div class="col-md-6 mb-2">
                                    <?php if ($total_pos >= $batas): ?>
                                        <div class="card border-left-danger shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                            <?= $nama ?> - OVERLOAD
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_pos, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas, 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="card border-left-success shadow-sm h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            <?= $nama ?> - AMAN
                                                        </div>
                                                        <div class="mb-0 font-weight-bold text-gray-800">
                                                            Rp<?= number_format($total_pos, 0, ',', '.') ?> /
                                                            Rp<?= number_format($batas, 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> Info: Validasi batas honor hanya berlaku jika <strong>bulan dan
                            tahun dipilih secara spesifik</strong>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold text-white">Daftar Kegiatan: <?= $periode_text; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="mydata" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr align="center">
                            <th>Nama Kegiatan</th>
                            <th>Jenis</th>
                            <th>Penanggung Jawab</th>
                            <th>Waktu Pelaksanaan</th> <!-- Digabung -->
                            <th>Satuan</th>
                            <th>Beban</th>
                            <th>Honor</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_all = 0;
                        foreach ($details as $d):
                            $total_all += $d['total_honor'] ?? 0;
                            ?>
                            <tr align="center">
                                <td class="text-left font-weight-bold"><?= $d['namakeg']; ?></td>
                                <td><?= $d['posisi_nama'] ?? '-'; ?></td>
                                <td><?= $d['nama_seksi'] ?? '-'; ?></td>
                                <td>
                                    <small>Start: <?= date('d/m/Y', (int) $d['start']) ?></small><br>
                                    <small>Finish: <?= date('d/m/Y', (int) $d['finish']) ?></small>
                                </td>
                                <td><?= $d['satuan_nama'] ?? '-'; ?></td>
                                <td><?= $d['beban'] ?? '-'; ?></td>
                                <td>Rp<?= number_format($d['honor'] ?? 0, 0, ',', '.'); ?></td>
                                <td class="font-weight-bold">Rp<?= number_format($d['total_honor'] ?? 0, 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php
                                    $now = time();
                                    $start = (int) $d['start'];
                                    $finish = (int) $d['finish'];
                                    if ($now < $start) {
                                        echo '<span class="badge badge-warning">Belum Mulai</span>';
                                    } elseif ($now > $finish) {
                                        echo '<span class="badge badge-danger">Selesai</span>';
                                    } else {
                                        echo '<span class="badge badge-success">Berjalan</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('rekap/editdetailbkm/' . $d['id_mitra'] . '/' . $d['kegiatan_id']); ?>"
                                        class="btn btn-sm btn-info btn-circle" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-200 font-weight-bold">
                        <tr>
                            <td colspan="7" class="text-right text-uppercase">Total Keseluruhan:</td>
                            <td colspan="3" class="text-left text-primary">
                                Rp<?= number_format($total_all, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->