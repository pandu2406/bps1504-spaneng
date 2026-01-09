<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('hitung', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <div class="row" align="center" style="color:#00264d;">
                <div class="col-sm">
                    <a href="<?= base_url('ranking/data_awal/') . $kegiatan_id ?>" class="btn btn-primary">Tabel Data Awal</a>
                </div>
                <div class="col-sm">
                    <a href="<?= base_url('ranking/normalized/') . $kegiatan_id ?>" class="btn btn-primary">Tabel Normalisasi</a>
                </div>
                <div class="col-sm">
                    <a href="<?= base_url('ranking/utility/') . $kegiatan_id ?>" class="btn btn-primary">Tabel Utility</a>
                </div>
                <div class="col-sm">
                    <a href="<?= base_url('ranking/total/') . $kegiatan_id ?>" class="btn btn-primary">Tabel Nilai Akhir</a>
                </div>
                <div class="col-sm">
                    <a href="<?= base_url('ranking/nilai_akhir/') . $kegiatan_id ?>" class="btn btn-primary">Tabel Ranking</a>
                </div>
            </div>
            <hr>

            <h3 style="color: #00264d;">Tabel Data Awal</h3>
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th>Mitra</th>
                            <?php if (!empty($kriteria) && is_array($kriteria)) : ?>
                                <?php foreach ($kriteria as $header) : ?>
                                    <th><?= htmlspecialchars($header->nama); ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
    <?php foreach ($id_mitra as $col1) : ?>
        <tr align=center>
            <td>
                <?= $col1->nama; ?>
            </td>

            <?php foreach ($kriteria as $row) : ?>
                <td>
                    <?php
                    $nilai_ditemukan = false;
                    foreach ($rekap as $r) :
                        if ($r->id_mitra == $col1->id_mitra && $r->kriteria_id == $row->id) :
                            echo $r->nilai !== null ? htmlspecialchars($r->nilai) : '-';
                            $nilai_ditemukan = true;
                            break;
                        endif;
                    endforeach;

                    if (!$nilai_ditemukan) echo "-";
                    ?>
                </td>
            <?php endforeach; ?>
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
