<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('penilaian', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>
            <div class="row" style="color:#00264d;">
                <div class="col-lg-6">
                    <h2>Kegiatan: <?= $kegiatan['nama']; ?></h2>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">#</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Action</th>

                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php $i = 1; ?>
                        <?php foreach ($mitra as $m) : ?>
                            <tr align=center>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $m['nik']; ?></td>
                                <td><?= $m['nama']; ?></td>
                                <td>
                                    <a href="<?= base_url('penilaian/download_seksi/') . $kegiatan['id'] . '/' . $m['id_mitra'] ?>" class="fa fa-fw fa-download text-success" target="_blank"></a><span> </span><a href="<?= base_url('penilaian/download_seksi/') . $kegiatan['id'] . '/' . $m['id_mitra'] ?>" class="badge badge-success" target="_blank"> Download hasil penilaian</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
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