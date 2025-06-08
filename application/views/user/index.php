<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <?php if ($user['role_id'] == 5) : ?>

        <div class="card shadow" style="background-color: #ffffff; ">
            <div class=" row">
                <div class="col-lg-2 mb-2 mt-2" align=center>
                    <br>
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    <hr>
                    <p class="card-text"><small class="text-muted">User since <?= date('d F Y', $user['date_created']); ?></small></p>
                </div>
                <div class="col-lg-5 mb-2 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>


                            <tr>
                                <th>NIK</th>
                                <td><?= $mitra['nik']; ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?= $mitra['nama']; ?></td>
                            </tr>
                            <tr>
                                <th>Posisi</th>
                                <td><?= $mitra['posisi']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $mitra['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= $mitra['alamat']; ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div>

                <div class="col-lg-5 mb-2 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Kecamatan</th>
                                <td><?= $mitra['kecamatan']; ?></td>
                            </tr>
                            <tr>
                                <th>Desa</th>
                                <td><?= $mitra['desa']; ?></td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td><?= $mitra['no_hp']; ?></td>
                            </tr>
                            <tr>
                                <th>Sobat ID</th>
                                <td><?= $mitra['sobat_id']; ?></td>
                            </tr>
                            <tr>
                                <th>Nilai</th>
                                <td><a href="<?= base_url('kegiatan/details_mitra_kegiatan/') . $mitra['id_mitra']; ?>" class="badge badge-primary">Pilih kegiatan</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($user['role_id'] == 1) : ?>
        <div class="card shadow col-lg-6" style="background-color: #ffffff; color:#00264d;">
            <div class="row">
                <div class="col-lg-2 mt-2" align=center>
                    <hr>
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    <hr>

                </div>
                <div class="col-lg-10 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <th>Nama</th>
                                <td><?= $pegawai['nama']; ?></td>
                            </tr> -->
                            <tr>
                                <th>Email</th>
                                <td><?= $user['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Since</th>
                                <td><?= date('d F Y', $user['date_created']); ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div>

            </div>
        </div>


    <?php else : ?>
        <div class="card shadow col-lg-6" style="background-color: #ffffff; color:#00264d;">
            <div class="row">
                <div class="col-lg-2 mt-2" align=center>
                    <hr>
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
                    <hr>

                </div>
                <div class="col-lg-10 mt-2">
                    <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                        <thead>
                            <tr align=center>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <th>Nama</th>
                                <td><?= $pegawai['nama']; ?></td>
                            </tr> -->
                            <tr>
                                <th>Email</th>
                                <td><?= $user['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Since</th>
                                <td><?= date('d F Y', $user['date_created']); ?></td>
                            </tr>


                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    <?php endif; ?>

    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->