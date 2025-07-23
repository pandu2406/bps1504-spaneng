<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="row" style="color:#00264d;">
                <div class="col-8">
                    <h4>Kegiatan: <?= htmlspecialchars($kegiatan['nama']) ?></h4>
                    <h4><?= ucfirst($peran) ?>: <?= htmlspecialchars($target['nama']) ?></h4>

                    <?php if ($peran == 'pengawas') : ?>
                        <a href="<?= base_url('penilaian/daftar_pengawas/') . $kegiatan['id']; ?>" class="btn btn-info">Kembali</a>
                    <?php else : ?>
                        <a href="<?= base_url('penilaian/daftar_pencacah/') . $kegiatan['id'] . '/' . $id_peg; ?>" class="btn btn-info">Kembali</a>
                    <?php endif; ?>
                </div>
                <div class="col-4 text-right">
                    <h4>Keterangan</h4>
                    <h6>Masukkan nilai antara 0 sampai 100</h6>
                </div>
            </div>

            <br>

            <form action="<?= base_url('penilaian/simpannilai'); ?>" method="post">
    <!-- Hidden input sesuai peran -->
<?php if ($peran === 'pengawas' && isset($all_kegiatan_pengawas['id'])): ?>
    <input type="hidden" name="peran" value="pengawas">
    <input type="hidden" name="all_id" value="<?= $all_kegiatan_pengawas['id']; ?>">
<?php elseif ($peran === 'mitra' && isset($all_kegiatan_pencacah['id'])): ?>
    <input type="hidden" name="peran" value="mitra">
    <input type="hidden" name="all_id" value="<?= $all_kegiatan_pencacah['id']; ?>">
<?php endif; ?>


    <div class="table-responsive">
        <table class="table table-borderless table-hover">
            <thead style="background-color: #00264d; color: #e6e6e6;">
                <tr align="center">
                    <th scope="col">Kriteria</th>
                    <th scope="col">Nilai</th>
                </tr>
            </thead>
            <tbody style="background-color: #ffffff; color: #00264d;">
                <?php foreach ($kriteria as $k): ?>
                    <?php
                        // Inisialisasi nilai default
                        $nilai = '';

                        // Ambil nilai berdasarkan peran dan ID kegiatan
                        if ($peran === 'pengawas' && isset($all_kegiatan_pengawas['id'])) {
                            $nilai = get_nilai_pengawas($all_kegiatan_pengawas['id'], $k['id']);
                        } elseif ($peran === 'mitra' && isset($all_kegiatan_pencacah['id'])) {
                            $nilai = get_nilai($all_kegiatan_pencacah['id'], $k['id']);
                        }
                    ?>
                    <tr align="center">
                        <td align="left"><?= htmlspecialchars($k['nama']) ?></td>
                        <td>
                            <input
                                type="number"
                                min="0"
                                max="100"
                                class="form-control"
                                name="nilai[<?= $k['id']; ?>]"
                                value="<?= htmlspecialchars($nilai) ?>"
                                required
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</form>


        </div>
    </div>
    <br>
</div>
<!-- /.container-fluid -->
                                </div>