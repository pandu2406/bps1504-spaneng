<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>
            <div class="row">
                <div class="col-lg-6" style="color:#00264d;">
                    <h2>Penilai: <?= $nama; ?></h2>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>

                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($kegiatan as $k) : ?>
                            <tr align=center>
                                <td><?= $k['nama']; ?></td>
                                <td><?= date('d F Y', $k['start']); ?></td>
                                <td><?= date('d F Y', $k['finish']); ?></td>
                                <?php $now = (time()); ?>
                                <?php if ($now < $k['start']) : ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $k['finish']) : ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else : ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
                                <?php endif; ?>
                                <td>
                                    <a href="<?= base_url('penilaian/daftar_pencacah/') . $k['kegiatan_id'] . '/' . $id_peg; ?>" class="badge badge-info">Lihat daftar pencacah</a>
                                </td>
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