<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>
    <div class="row">
        <div class="col-lg-6 mb-2">
            <table class="table table-borderless" style="background-color: #ffffff; color:#00264d;">
                <thead>
                    <tr align=center>

                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <th>Picture</th>
                        <td><img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" alt="Responsive image" width="100" height="100"></td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td><?= $mitra['nik'] ?></td>
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
                        <th>Kecamatan</th>
                        <td><?= $mitra['nama_kecamatan']; ?></td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td><?= $mitra['nama_desa']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= $mitra['alamat']; ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?= $mitra['jk']; ?></td>
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
                        <th>Status</th>
                        <?php if ($mitra['is_active'] == '1') : ?>
                            <td><i class="fas fa-check" style="color:yellowgreen" title="OK"></i>
                            </td>
                        <?php else : ?>
                            <td><i class="fas fa-times" style="color:red" title="Suspended"></i>
                            </td>
                        <?php endif; ?>
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
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->