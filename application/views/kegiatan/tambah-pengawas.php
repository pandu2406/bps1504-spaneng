<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pengawas', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div>
                <div class="row mb-3" style="color:#00264d;">
                    <div class="col-lg-6" align=left>
                        <h2><?= $kegiatan['nama']; ?></h2>
                        <h4>Jumlah = <?= $kuota['kegiatan_id']; ?> / <?= $kegiatan['k_pengawas']; ?></h4>
                    </div>
                    <div class="col-lg-6" align=right>
                        <a href="<?= base_url('kegiatan/survei') ?>" class="btn btn-danger">Kembali</a>
                        <a href="<?= base_url('kegiatan/tambah_pencacah/') . $kegiatan['id'] ?>" class="btn btn-success">Tambah Pencacah</a>
                        <a href="<?= base_url('kegiatan/tambah_pengawas_mitra/') . $kegiatan['id'] ?>" class="btn btn-warning">Tambah Pengawas Dari Mitra</a>
                        <a href="<?= base_url('kegiatan/pengawasterpilih/') . $kegiatan['id'] ?>" class="btn btn-info">Pengawas Terpilih</a>
                    </div>

                </div>

                <form method="post" action="">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover" id="mydata">
                            <thead style="background-color: #00264d; color:#e6e6e6;">
                                <tr align=center>
                                    <th scope="col">Tambah</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffffff; color: #00264d;">

                                <?php $i = 1; ?>
                                <?php foreach ($pengawas as $p) : ?>
                                    <tr align=center>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-pengawas-input" type="checkbox" <?= check_pengawas($kegiatan['id'], $p['id_peg']); ?> data-kegiatan="<?= $kegiatan['id']; ?>" data-pengawas="<?= $p['id_peg']; ?>">
                                            </div>
                                        </td>
                                        <td><?= $p['email']; ?></td>
                                        <td><?= $p['nama']; ?></td>
                                        <td>
                                            <a href="<?= base_url('kegiatan/details_kegiatan_pengawas/') . $kegiatan['id'] . '/' . $p['id_peg']; ?>" class="badge badge-primary">kegiatan yang diikuti</a>
                                        </td>

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