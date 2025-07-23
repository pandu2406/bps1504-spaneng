<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="row mb-3" style="color:#00264d;">
                <div class="col-lg-6" align=left>
                    <h2><?= $nama_kegiatan['nama']; ?></h2>
                </div>
                <div class="col-lg-6" align=right>
                    <a href="<?= base_url('ranking/pilih_kegiatan_nilai_akhir') ?>" class="btn btn-danger">Kembali</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">#</th>
                            <th scope="col">Pengawas/Pemeriksa</th>
                            <th scope="col">Mitra</th>
                            <th scope="col">Progress</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($progress as $p) : ?>
                            <tr align=center>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $p['nmpegawai']; ?></td>
                                <td><?= $p['nmmitra']; ?></td>
                                <td><?= $p['progress'] . '/' . $jumlah_kriteria; ?></td>
                            </tr>
                            <?php $i++; ?>
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