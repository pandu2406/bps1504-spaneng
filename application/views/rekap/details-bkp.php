<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>
            <div class="row" style="color:#00264d;">
                <div class="col-lg-6">
                    <h2>Pegawai: <?= $pegawai['nama']; ?></h2>
                </div>
                <div class="col-lg-6 text-lg-right mt-2 mt-lg-0">
                    <a href="<?= base_url('rekap/bk_pegawai'); ?>" class="btn btn-danger">
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
                        <tr align=center>

                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($details as $d) : ?>
                            <tr align=center>
                                <td><?= $d['namakeg']; ?></td>
                                <td><?= date('d F Y', $d['start']); ?></td>
                                <td><?= date('d F Y', $d['finish']); ?></td>
                                <?php $now = (time()); ?>
                                <?php if ($now < $d['start']) : ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $d['finish']) : ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else : ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
                                <?php endif; ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->