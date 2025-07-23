<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="row align-items-center mb-3" style="color:#00264d;">
                <div class="col-lg-6">
                    <h2 class="mb-0">Mitra: <?= $mitra['nama']; ?></h2>
                </div>
                <div class="col-lg-6 text-lg-right mt-2 mt-lg-0">
                    <a href="<?= base_url('rekap/bk_mitra'); ?>" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <br>

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
        $bulan_finish = (int)date('n', $d['finish']);
        $tahun_finish = (int)date('Y', $d['finish']);

        if ($bulan_finish == $selected_bulan && $tahun_finish == $selected_tahun) {
            if ($d['posisi_id'] == 1) {
                $total_posisi_1 += $honor_sekarang;
            } elseif ($d['posisi_id'] == 2) {
                $total_posisi_2 += $honor_sekarang;
            } elseif ($d['posisi_id'] == 3) {
                // Periksa dari kolom 'peran' apakah pencacah atau pengawas
                if (isset($d['peran'])) {
                    if (strtolower($d['peran']) == 'pencacah') {
                        $total_posisi_3_ppl += $honor_sekarang;
                    } elseif (strtolower($d['peran']) == 'pengawas') {
                        $total_posisi_3_pml += $honor_sekarang;
                    }
                } else {
                    // Fallback: Deteksi dari nama kegiatan jika 'peran' tidak ada
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

            <?php if ($selected_bulan != 0 && $selected_tahun != 0): ?>
                <!-- Validasi posisi 1 -->
                <?php
// Atur batas maksimal honor per posisi
$batas_honor = [
    1 => 3303000,
    2 => 3056000,
    3 => [
        'PPL' => 4624000,
        'PML' => 5120000,
    ],
    4 => 3386000,
];
?>

<?php foreach ($posisi_list as $posisi): ?>
    <?php
        $id = $posisi['id'];
        $nama = $posisi['posisi'];

        if ($id == 3):
    ?>
        <!-- Posisi 3: PPL -->
        <?php if ($total_posisi_3_ppl >= $batas_honor[3]['PPL']): ?>
            <div class="status-alert alert-danger">
                <strong>Status (<?= $nama ?> - PPL):</strong> Honor mitra sudah melebihi <strong>Rp<?= number_format($batas_honor[3]['PPL'], 0, ',', '.') ?></strong>. Mitra tidak bisa dimasukkan ke kegiatan ini.
            </div>
        <?php else: ?>
            <div class="status-alert alert-success">
                <strong>Status (<?= $nama ?> - PPL):</strong> Honor mitra masih di bawah batas <strong>Rp<?= number_format($batas_honor[3]['PPL'], 0, ',', '.') ?></strong>.
            </div>
        <?php endif; ?>

        <!-- Posisi 3: PML -->
        <?php if ($total_posisi_3_pml >= $batas_honor[3]['PML']): ?>
            <div class="status-alert alert-danger">
                <strong>Status (<?= $nama ?> - PML):</strong> Honor mitra sudah melebihi <strong>Rp<?= number_format($batas_honor[3]['PML'], 0, ',', '.') ?></strong>. Mitra tidak bisa dimasukkan ke kegiatan ini.
            </div>
        <?php else: ?>
            <div class="status-alert alert-success">
                <strong>Status (<?= $nama ?> - PML):</strong> Honor mitra masih di bawah batas <strong>Rp<?= number_format($batas_honor[3]['PML'], 0, ',', '.') ?></strong>.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <?php
            $total = ${"total_posisi_" . $id} ?? 0;
            $batas = $batas_honor[$id] ?? 0;
        ?>
        <?php if ($total >= $batas): ?>
            <div class="status-alert alert-danger">
                <strong>Status (<?= $nama ?>):</strong>Honor mitra sudah melebihi <strong>Rp<?= number_format($batas, 0, ',', '.') ?></strong>. Mitra tidak bisa dimasukkan ke kegiatan ini.
            </div>
        <?php else: ?>
            <div class="status-alert alert-success">
                <strong>Status (<?= $nama ?>):</strong>Honor mitra masih di bawah batas <strong>Rp<?= number_format($batas, 0, ',', '.') ?></strong>.
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>


            <?php else: ?>
                <div class="status-alert alert-info">
                    <strong>Catatan:</strong> Validasi batas honor hanya berlaku jika <strong>bulan dan tahun dipilih secara spesifik</strong>.
                </div>
            <?php endif; ?>

            <br>
            <h5 class="mb-3">
                Periode: 
                <?php 
                    if ($selected_bulan == 0 && $selected_tahun == 0) {
                        echo "Semua Tahun Semua Bulan";
                    } elseif ($selected_bulan == 0) {
                        echo "Semua Bulan Tahun $selected_tahun";
                    } elseif ($selected_tahun == 0) {
                        echo date('F', mktime(0, 0, 0, $selected_bulan, 10)) . " Semua Tahun";
                    } else {
                        echo date('F', mktime(0, 0, 0, $selected_bulan, 10)) . " " . $selected_tahun;
                    }
                ?>
            </h5>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th>Nama Kegiatan</th>
                            <th>Jenis Kegiatan</th>
                            <th>Penanggung Jawab</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Paket Data</th>
                            <th>Satuan</th> <!-- Tambahan -->
                            <th>Beban</th>
                            <th>Honor</th>
                            <th>Total Honor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php 
                        $total = 0;
                        foreach ($details as $d) :
                            $total += $d['total_honor'] ?? 0;
                        ?>
                            <tr align="center">
                                <td><?= $d['namakeg']; ?></td>
                                <td><?= $d['posisi_nama'] ?? '-'; ?></td>
                                <td><?= $d['nama_seksi'] ?? '-'; ?></td>
                                <td><?= date('d F Y', (int)$d['start']) ?></td>
                                <td><?= date('d F Y', (int)$d['finish']) ?></td>
                                <td><?= $d['sistem_pembayaran'] ?? '-'; ?></td>
                                <td><?= $d['satuan_nama'] ?? '-'; ?></td> <!-- Tambahan -->
                                <td><?= $d['beban'] ?? '-'; ?></td>
                                <td>Rp<?= number_format($d['honor'] ?? 0, 0, ',', '.'); ?></td>
                                <td>Rp<?= number_format($d['total_honor'] ?? 0, 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    $now = time();
                                    $start = (int)$d['start'];
                                    $finish = (int)$d['finish'];
                                    if ($now < $start) {
                                        echo '<span class="badge badge-warning">belum mulai</span>';
                                    } elseif ($now > $finish) {
                                        echo '<span class="badge badge-danger">selesai</span>';
                                    } else {
                                        echo '<span class="badge badge-primary">sedang berjalan</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('rekap/editdetailbkm/' . $d['id_mitra'] . '/' . $d['kegiatan_id']); ?>" class="btn btn-sm btn-info">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold;">
                            <td colspan="7" align="right">
                                <?php 
                                    $nama_bulan = [
                                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];

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
                                Total Keseluruhan Honor <?= $periode_text; ?>:
                            </td>
                            <td colspan="3">Rp<?= number_format($total, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <br>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
