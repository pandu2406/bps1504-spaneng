<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('tambah-pencacah', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <!-- Card Layout -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Penugasan Pencacah ke Pengawas:
                        <?= $pengawas['nama'] ?></h6>
                    <div>
                        <a href="<?= base_url('kegiatan/pengawasterpilih/') . $kegiatan['id'] ?>"
                            class="btn btn-secondary btn-sm shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                        <a href="<?= base_url('kegiatan/pencacahterpilih/') . $kegiatan['id'] . '/' . $pengawas['id_peg'] ?>"
                            class="btn btn-info btn-sm shadow-sm">
                            <i class="fas fa-list fa-sm text-white-50"></i> Lihat Terpilih
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row align-items-center mb-4">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Kegiatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $kegiatan['nama']; ?></div>
                        </div>
                    </div>

                    <form method="post" action="">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="mydata" width="100%"
                                cellspacing="0">
                                <thead class="thead-dark">
                                    <tr align=center>
                                        <th scope="col" width="10%">Pilih</th>
                                        <th scope="col">NIK/NIP</th>
                                        <th scope="col">Nama Pencacah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($pencacah as $p): ?>
                                        <tr>
                                            <td align="center">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox"
                                                        class="custom-control-input form-pencacahpengawas-input"
                                                        id="customCheck<?= $p['id_pencacah'] . $p['type'] ?>"
                                                        <?= check_pencacahpengawas($kegiatan['id'], $pengawas['id_peg'], $p['id_pencacah'], $p['type']); ?>
                                                        data-kegiatan="<?= $kegiatan['id']; ?>"
                                                        data-id_pengawas="<?= $pengawas['id_peg']; ?>"
                                                        data-id_pencacah="<?= $p['id_pencacah']; ?>"
                                                        data-type="<?= $p['type']; ?>">
                                                    <label class="custom-control-label"
                                                        for="customCheck<?= $p['id_pencacah'] . $p['type'] ?>"></label>
                                                </div>
                                            </td>
                                            <td align="center"><?= $p['nik']; ?></td>
                                            <td class="text-capitalize"><?= $p['nama']; ?></td>
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

    </div>

    <br>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->