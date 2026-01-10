<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <!-- Card for Progress -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                    <h6 class="m-0 font-weight-bold text-white"><?= $nama_kegiatan['nama']; ?></h6>
                    <a href="<?= base_url('ranking/pilih_kegiatan_nilai_akhir') ?>"
                        class="btn btn-sm btn-light text-primary font-weight-bold shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="mydata" width="100%"
                            cellspacing="0">
                            <thead class="thead-dark">
                                <tr align="center">
                                    <th width="5%">#</th>
                                    <th>Pengawas/Pemeriksa</th>
                                    <th>Mitra</th>
                                    <th width="35%">Progress Penilaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($progress as $p): ?>
                                    <?php
                                    $cur = (int) $p['progress'];
                                    $max = (int) $jumlah_kriteria;
                                    $percent = ($max > 0) ? ($cur / $max) * 100 : 0;
                                    $color = ($percent < 50) ? 'bg-danger' : (($percent < 100) ? 'bg-warning' : 'bg-success');
                                    ?>
                                    <tr>
                                        <td align="center"><?= $i++; ?></td>
                                        <td><span class="font-weight-bold"><?= $p['nmpegawai']; ?></span></td>
                                        <td><?= $p['nmmitra']; ?></td>
                                        <td align="center">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2 font-weight-bold text-gray-800" style="min-width: 60px;">
                                                    <?= $cur ?> / <?= $max ?>
                                                </div>
                                                <div class="progress flex-grow-1" style="height: 20px;">
                                                    <div class="progress-bar <?= $color ?> progress-bar-striped progress-bar-animated"
                                                        role="progressbar" style="width: <?= $percent ?>%;"
                                                        aria-valuenow="<?= $percent ?>" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <?= round($percent) ?>%
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>

    <br>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->