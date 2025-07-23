<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pencacah', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div>
                <div class="row" style="color:#00264d;">
                    <div class="col-lg-4" align=left>
                        <h2><?= $kegiatan['nama']; ?></h2>
                    </div>
                    <div class="col-lg-4" align=center>
                        <h3>Pengawas : <?= $pengawas['nama']; ?></h3>
                    </div>
                    <div class="col-lg-4" align=right>
                        <a href="<?= base_url('kegiatan/tambah_pencacah_pengawas/') . $kegiatan['id'] . '/' . $pengawas['id_peg']  ?>" class="btn btn-primary">Tambah Pencacah</a>
                    </div>


                </div>

                <form method="post" action="">

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover" id="mydata">
                            <thead style="background-color: #00264d; color:#e6e6e6;">
                                <tr align=center>
                                    <th scope="col">#</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Nama</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffffff; color: #00264d;">
                                <?php $i = 1; ?>
                                <?php foreach ($pencacah as $p) : ?>
                                    <tr align=center>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-pencacahpengawas-input" type="checkbox" <?= check_pencacahpengawas($kegiatan['id'], $pengawas['id_peg'], $p['id_mitra']); ?> data-kegiatan="<?= $kegiatan['id']; ?>" data-pengawas="<?= $pengawas['id_peg']; ?>" data-pencacah="<?= $p['id_mitra']; ?>">
                                            </div>
                                        </td>
                                        <td><?= $p['nik']; ?></td>
                                        <td><?= $p['nama']; ?></td>

                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <br>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->