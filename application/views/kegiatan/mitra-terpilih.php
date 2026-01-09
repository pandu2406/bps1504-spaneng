<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg">
            <div class="row mb-3" style="color:#00264d;">
                <div class="col-lg-6" align=left>
                    <h2><?= $kegiatan['nama']; ?></h2>
                    <h4>Jumlah Pencacah= <?= $kuota['kegiatan_id']; ?> / <?= $kegiatan['k_pencacah']; ?></h4>
                </div>
                <div class="col-lg-6" align=right>
                    <a href="<?= base_url('kegiatan/survei') ?>" class="btn btn-danger">Kembali</a>
                    <a href="<?= base_url('kegiatan/tambah_pengawas/') . $kegiatan['id'] ?>"
                        class="btn btn-success">Tambah Pengawas</a>
                    <a href="<?= base_url('kegiatan/tambah_pencacah/') . $kegiatan['id'] ?>"
                        class="btn btn-primary">Tambah Pencacah</a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pencacah', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div>


                <form method="post" action="">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover" id="mydata">
                            <thead style="background-color: #00264d; color:#e6e6e6;">
                                <tr align=center>
                                    <th scope="col">#</th>
                                    <th scope="col">NIK / NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Kecamatan</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffffff; color: #00264d;">
                                <?php $i = 1; ?>
                                <?php foreach ($pencacah as $p): ?>
                                    <tr align=center>
                                        <td>
                                            <div class="form-check">
                                                <?php if ($p['type'] == 'pegawai'): ?>
                                                    <input class="form-pencacah-organik-input" type="checkbox" checked
                                                        data-kegiatan="<?= $kegiatan['id']; ?>"
                                                        data-pegawai="<?= $p['id_pencacah']; ?>">
                                                <?php else: ?>
                                                    <input class="form-pencacah-input" type="checkbox" checked
                                                        data-kegiatan="<?= $kegiatan['id']; ?>"
                                                        data-pencacah="<?= $p['id_pencacah']; ?>">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?= $p['nik']; ?></td>
                                        <td class="text-capitalize"><?= $p['nama']; ?></td>
                                        <td><?= $p['alamat']; ?></td>
                                        <td><?= $p['kecamatan']; ?></td>

                                        <td>
                                            <?php if ($p['type'] == 'pegawai'): ?>
                                                <a href="<?= base_url('kegiatan/details_kegiatan_pegawai/') . $kegiatan['id'] . '/' . $p['id_pencacah']; ?>"
                                                    class="badge badge-warning">kegiatan yang diikuti</a>
                                            <?php else: ?>
                                                <a href="<?= base_url('kegiatan/details_kegiatan_mitra/') . $kegiatan['id'] . '/' . $p['id_pencacah']; ?>"
                                                    class="badge badge-primary">kegiatan yang diikuti</a>
                                            <?php endif; ?>
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