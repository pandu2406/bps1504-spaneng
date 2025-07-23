<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>

                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
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
                                <td>
                                    <a href="<?= base_url('penilaian/download/') . $k['id'] . '/' . $k['id_pengawas'] . '/' . $id_mitra ?>" class="fa fa-fw fa-download text-success" target="_blank"></a><span> </span><a href="<?= base_url('penilaian/download/') . $k['id'] . '/' . $k['id_pengawas'] . '/' . $id_mitra ?>" class="badge badge-success" target="_blank"> Download hasil penilaian</a>
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