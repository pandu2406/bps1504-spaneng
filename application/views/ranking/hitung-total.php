<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('hitung', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <div class="row" align=center style="color:#00264d;">
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

            <h3 style="color: #00264d;">Tabel Nilai Akhir</h3>
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th>Mitra</th>
                            <?php foreach ($kriteria as $header) : ?>
                                <th>
                                    <?= $header->nama; ?>
                                </th>
                            <?php endforeach; ?>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php foreach ($rekap as $col) : ?>
                            <tr align="center">
                                <td><?= $col->nama ?></td>
                                <?php
                                $total = 0;
                                if (isset($col->bobot) && is_array($col->bobot)) :
                                    foreach ($kriteria as $krit) :
                                        $found = false;
                                        foreach ($col->bobot as $score) :
                                            if ($score->kriteria_id == $krit->id) :
                                                $total += $score->ut;
                                                echo "<td>" . number_format($score->ut, 2) . "</td>";
                                                $found = true;
                                                break;
                                            endif;
                                        endforeach;
                                        if (!$found) echo "<td>-</td>";
                                    endforeach;
                                else :
                                    for ($i = 0; $i < $jumlah_kriteria; $i++) echo "<td>-</td>";
                                endif;
                                ?>

                                <td><strong><?= number_format($total, 2); ?></strong></td>
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