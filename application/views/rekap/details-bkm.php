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
            <th>Start</th>
            <th>Finish</th>
            <th>Pembayaran</th>
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
                <td><?= date('d F Y', (int)$d['start']) ?></td>
                <td><?= date('d F Y', (int)$d['finish']) ?></td>
                <td><?= $d['sistem_pembayaran'] ?? '-'; ?></td>
                <td><?= $d['beban'] ?? '-'; ?></td>
                <td>Rp<?= number_format($d['honor'] ?? 0, 0, ',', '.'); ?></td>
                <td>Rp<?= number_format($d['total_honor'] ?? 0, 0, ',', '.'); ?></td>
                <td>
                    <?php $now = time(); ?>
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
        <td colspan="6" align="right">
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