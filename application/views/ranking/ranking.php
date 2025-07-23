<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <?= form_error('hitung', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <div class="d-flex justify-content-between flex-wrap align-items-center mb-3" style="color:#00264d;">
                <div>
                    <h3 style="color: #00264d;">Tabel Ranking</h3>
                    <h5>Kegiatan: <?= $kegiatan->nama; ?></h5>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= base_url('ranking/pilih_kegiatan_nilai_akhir') ?>" class="btn btn-danger">Kembali</a>&nbsp;&nbsp;
                    <a href="<?= base_url('penilaian/cetak_ranking/' . $kegiatan->id) ?>" target="_blank" class="btn btn-success">Cetak Ranking PDF</a>&nbsp;&nbsp;
                    <a href="<?= base_url('ranking/cetak_excel/' . $kegiatan->id) ?>" class="btn btn-success">Cetak Rekap Excel</a>
                </div>
            </div>

            <hr>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th>Ranking</th>
                            <th>Mitra</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php $i = 1; ?>
                        <?php foreach ($hq as $col) : ?>
                            <tr align="center">
                                <td><?= $i; ?></td>
                                <td><?= $col->nama; ?></td>
                                <td><?= number_format($col->total, 2); ?></td>
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