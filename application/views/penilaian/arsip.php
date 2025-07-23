<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('penilaian', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">#</th>
                            <th scope="col">ID Sobat</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php $i = 1; ?>
                        <?php foreach ($mitra as $m) : ?>
                            <tr align=center>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $m['sobat_id']; ?></td>
                                <td><?= $m['nama']; ?></td>
                                <td>
                                    <a href="<?= base_url('penilaian/arsip_pilihkegiatan/') . $m['id_mitra']; ?>" class="badge badge-primary">Pilih kegiatan</a>
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