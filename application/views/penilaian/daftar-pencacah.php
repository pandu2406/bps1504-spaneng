<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>
            <div class="row">
                <div class="col-8" style="color:#00264d;">
                    <h2>Kegiatan: <?= $nama_kegiatan['nama']; ?></h2>
                </div>
                <div class="col-4" align=right>
                    <a href="<?= base_url('penilaian'); ?>" class="btn btn-info">kembali</a>
                </div>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>

                            <th scope="col">NIK</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php foreach ($kegiatan as $k) : ?>
                            <tr align="center">
                                <td><?= $k['nik']; ?></td>
                                <td><?= $k['nama']; ?></td>
                                <td>
                                    <?php
                                        $now = time();
                                        $start = strtotime($nama_kegiatan['start']);
                                        $id_mitra = $k['id_mitra'];
                                        $link = base_url("penilaian/isi_nilai/{$kegiatan_id}/{$id_peg}/{$id_mitra}/mitra");
                                    ?>
                                    <?php if ($now < $start) : ?>
                                        <span class="badge badge-secondary">Belum Dimulai</span>
                                    <?php else : ?>
                                        <a href="<?= $link ?>" class="btn btn-sm btn-primary">Isi Nilai</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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