<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6">
            <?= form_error('subkriteria', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <div class="row" style="color:#00264d;">
                <div class="col-lg-6">
                    <a href="<?= base_url('ranking/kriteria'); ?>" class="btn btn-success mb-3">Kriteria</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">Prioritas</th>
                            <th scope="col">Nilai</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Bobot</th>
                            <!-- <th scope="col">Action</th> -->

                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php foreach ($subkriteria as $sk) : ?>
                            <tr align=center>
                                <th><?= $sk['prioritas']; ?></th>
                                <td><?= $sk['nilai']; ?></td>
                                <td><?= $sk['deskripsi']; ?></td>
                                <td><?= number_format($sk['bobot'], 4); ?></td>
                                <!-- <td><a href="<?= base_url('ranking/hitung_bobot_subkriteria/') . $sk['prioritas']; ?>" class="badge badge-primary">Perbarui bobot</a></td> -->
                            </tr>
                        <?php endforeach ?>
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