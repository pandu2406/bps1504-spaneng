<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>
            <div class="row" style="color:#00264d;">
                <div class="col-lg-6">
                    <h2>Mitra: <?= $id_mitra['nama']; ?></h2>
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
                            <th scope="col">Nilai</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($details as $d) : ?>
                            <tr align=center>
                                <td><?= $d['nama']; ?></td>
                                <td><?= date('d F Y', $d['start']); ?></td>
                                <td><?= date('d F Y', $d['finish']); ?></td>
                                <?php $now = (time()); ?>
                                <?php if ($now < $d['start']) : ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $d['finish']) : ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else : ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
                                <?php endif; ?>
                                <td><a href="<?= base_url('kegiatan/details_nilai_perkegiatan/') . $id_mitra['id_mitra'] . '/' . $d['id'] ?>" class="badge badge-primary">Lihat nilai</a></td>
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