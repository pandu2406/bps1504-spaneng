<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pengawas', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <!-- Card Layout -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Pengawas Terpilih: <?= $kegiatan['nama']; ?></h6>
                    <div>
                         <?php 
                            // Calculate Quota Percentage for Color
                            $filled = isset($kuota['kegiatan_id']) ? $kuota['kegiatan_id'] : 0;
                            $total = isset($kegiatan['k_pengawas']) ? $kegiatan['k_pengawas'] : 0;
                            $badgeColor = 'success';
                            if ($total > 0 && $filled >= $total) $badgeColor = 'danger';
                         ?>
                        <span class="badge badge-<?= $badgeColor ?> p-2 mr-2">
                            <i class="fas fa-users"></i> <?= $filled ?> / <?= $total ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Toolbar -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                             <a href="<?= base_url('kegiatan/survei') ?>" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                             <div class="btn-group" role="group">
                                <a href="<?= base_url('kegiatan/tambah_pencacah/') . $kegiatan['id'] ?>" class="btn btn-success btn-sm shadow-sm">
                                    <i class="fas fa-plus"></i> Pencacah
                                </a>
                                <a href="<?= base_url('kegiatan/tambah_pengawas_mitra/') . $kegiatan['id'] ?>" class="btn btn-warning btn-sm shadow-sm text-white">
                                    <i class="fas fa-plus"></i> Pengawas (Mitra)
                                </a>
                                <a href="<?= base_url('kegiatan/tambah_pengawas/') . $kegiatan['id'] ?>" class="btn btn-primary btn-sm shadow-sm">
                                    <i class="fas fa-plus"></i> Pengawas (Organik)
                                </a>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="mydata" width="100%" cellspacing="0">
                                <thead class="thead-dark">
                                    <tr align=center>
                                        <th scope="col" width="10%">Status</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col" width="10%">Tipe</th>
                                        <th scope="col" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pengawas as $p) : ?>
                                        <tr>
                                            <td align="center">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input form-pengawas-input" 
                                                        id="customCheck<?= $p['id'] ?>"
                                                        <?= check_pengawas($kegiatan['id'], $p['id']); ?>
                                                        data-kegiatan="<?= $kegiatan['id']; ?>" 
                                                        data-pengawas="<?= $p['id']; ?>">
                                                    <label class="custom-control-label" for="customCheck<?= $p['id'] ?>"></label>
                                                </div>
                                            </td>
                                            <td><?= $p['email']; ?></td>
                                            <td class="text-capitalize"><?= $p['nama']; ?></td>
                                            <td align="center">
                                                <span class="badge badge-<?= $p['asal'] == 'pegawai' ? 'primary' : 'warning' ?> px-2 py-1">
                                                    <?= ucfirst($p['asal']); ?>
                                                </span>
                                            </td>
                                            <td align="center">
                                                <a href="<?= base_url('kegiatan/tambah_pencacah_pengawas/') . $kegiatan['id'] . '/' . $p['id']; ?>"
                                                    class="btn btn-info btn-sm shadow-sm">
                                                    <i class="fas fa-user-plus fa-sm"></i> Assign Pencacah
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <br>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->