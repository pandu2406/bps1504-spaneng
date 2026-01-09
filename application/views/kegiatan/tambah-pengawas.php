<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pengawas', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><?= $kegiatan['nama']; ?></h6>
                        <span class="badge badge-primary" style="font-size: 1rem;">
                            Kuota: <span id="quota-count"><?= $kuota['kegiatan_id']; ?> /
                                <?= $kegiatan['k_pengawas']; ?></span>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-12 text-right">
                                <a href="<?= base_url('kegiatan/survei') ?>" class="btn btn-sm btn-danger shadow-sm"><i
                                        class="fas fa-arrow-left"></i> Kembali</a>
                                <a href="<?= base_url('kegiatan/tambah_pencacah/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-success shadow-sm"><i class="fas fa-users"></i> Tambah
                                    Pencacah</a>
                                <a href="<?= base_url('kegiatan/tambah_pengawas_mitra/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-warning shadow-sm"><i class="fas fa-user-plus"></i> Tambah
                                    Pengawas Dari Mitra</a>
                                <a href="<?= base_url('kegiatan/pengawasterpilih/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-info shadow-sm"><i class="fas fa-list"></i> Pengawas
                                    Terpilih</a>
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
                                        <?php foreach ($pengawas as $p): ?>
                                            <tr align=center>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-pengawas-input" type="checkbox"
                                                            <?= check_pengawas($kegiatan['id'], $p['id_peg']); ?>
                                                            data-kegiatan="<?= $kegiatan['id']; ?>"
                                                            data-pengawas="<?= $p['id_peg']; ?>">
                                                    </div>
                                                </td>
                                                <td><?= $p['email']; ?></td>
                                                <td><?= $p['nama']; ?></td>
                                                <td>
                                                    <a href="<?= base_url('kegiatan/details_kegiatan_pengawas/') . $kegiatan['id'] . '/' . $p['id_peg']; ?>"
                                                        class="badge badge-primary">kegiatan yang diikuti</a>
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