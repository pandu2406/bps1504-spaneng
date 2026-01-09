<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pencacah', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><?= $kegiatan['nama']; ?></h6>
                        <span class="badge badge-primary" style="font-size: 1rem;">
                            Kuota: <span id="quota-count"><?= $kuota['kegiatan_id']; ?> /
                                <?= $kegiatan['k_pencacah']; ?></span>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-12 text-right">
                                <a href="<?= base_url('kegiatan/survei') ?>" class="btn btn-sm btn-danger shadow-sm"><i
                                        class="fas fa-arrow-left"></i> Kembali</a>
                                <a href="<?= base_url('kegiatan/tambah_pencacah_organik/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-user-plus"></i> Tambah
                                    Pencacah Organik</a>
                                <a href="<?= base_url('kegiatan/tambah_pengawas/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-success shadow-sm"><i class="fas fa-user-tie"></i> Tambah
                                    Pengawas</a>
                                <a href="<?= base_url('kegiatan/pengawasterpilih/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-info shadow-sm"><i class="fas fa-list"></i> Pengawas
                                    Terpilih</a>
                                <a href="<?= base_url('kegiatan/mitraterpilih/') . $kegiatan['id'] ?>"
                                    class="btn btn-sm btn-info shadow-sm"><i class="fas fa-users"></i> Pencacah
                                    Terpilih</a>
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
                                                        <input class="form-pencacah-input" type="checkbox"
                                                            <?= check_pencacah($kegiatan['id'], $p['id_mitra']); ?>
                                                            data-kegiatan="<?= $kegiatan['id']; ?>"
                                                            data-pencacah="<?= $p['id_mitra']; ?>">
                                                    </div>
                                                </td>
                                                <td><?= $p['nik']; ?></td>
                                                <td class="text-capitalize"><?= $p['nama']; ?></td>
                                                <td><?= $p['alamat']; ?></td>
                                                <td><?= $p['kecamatan']; ?></td>

                                                <td>
                                                    <a href="<?= base_url('kegiatan/details_kegiatan_mitra/') . $kegiatan['id'] . '/' . $p['id_mitra']; ?>"
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