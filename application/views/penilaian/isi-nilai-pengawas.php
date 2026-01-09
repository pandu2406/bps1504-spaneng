<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="row" style="color:#00264d;">
                <div class="col-8">
                    <h4>Kegiatan: <?= $kegiatan['nama'] ?></h4>
                    <h4><?= ucfirst($peran) ?>: <?= $target['nama'] ?></h4>
                    <a href="<?= base_url('penilaian/daftar_pencacah/') . $kegiatan['id'] . "/" . $id_peg; ?>" class="btn btn-info">Kembali</a>
                </div>
                <div class="col-4 text-right">
                    <h4>Keterangan</h4>
                    <h6>Masukkan nilai antara 0 sampai 100</h6>
                </div>
            </div>

            <br>

            <form action="<?= base_url('penilaian/simpannilai'); ?>" method="post">
                <!-- Hidden ID untuk identifikasi -->
                <input type="hidden" name="all_kegiatan_pencacah_id" value="<?= $all_kegiatan_pencacah['id']; ?>">

                <div class="table-responsive">
                    <table class="table table-borderless table-hover">
                        <thead style="background-color: #00264d; color:#e6e6e6;">
                            <tr align="center">
                                <th scope="col">Kriteria</th>
                                <th scope="col">Nilai</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: #ffffff; color: #00264d;">
                            <?php foreach ($kriteria as $k) : ?>
                                <tr align="center">
                                    <td align="left"><?= $k['nama']; ?></td>
                                    <td>
                                        <input type="number" min="0" max="100" class="form-control"
                                            name="nilai[<?= $k['id']; ?>]"
                                            value="<?= get_nilai($all_kegiatan_pencacah['id'], $k['id']); ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Submit -->
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>

        </div>
    </div>

    <br>
</div>
<!-- /.container-fluid -->
