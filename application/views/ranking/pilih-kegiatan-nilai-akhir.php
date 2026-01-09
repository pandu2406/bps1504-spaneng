<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Kegiatan</h6>
                </div>
                <div class="card-body">
                    <form action="" method="get" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="bulan" class="sr-only">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="">- Pilih Bulan -</option>
                                <?php
                                $bulan_list = [
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember'
                                ];
                                foreach ($bulan_list as $key => $val) {
                                    $selected = ($this->input->get('bulan') == $key) ? 'selected' : '';
                                    echo "<option value='$key' $selected>$val</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="tahun" class="sr-only">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">- Pilih Tahun -</option>
                                <?php
                                $current_year = date('Y');
                                for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                    $selected = ($this->input->get('tahun') == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Filter</button>
                        <a href="<?= base_url('ranking/pilih_kegiatan_nilai_akhir'); ?>"
                            class="btn btn-secondary mb-2 ml-2">Reset</a>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">#</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
                            <th scope="col">Progress</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($kegiatan as $k): ?>
                            <?php
                            $target = $k['target_penilaian'];
                            $realisasi = $k['realisasi_penilaian'];
                            $persen = ($target > 0) ? round(($realisasi / $target) * 100, 1) : 0;

                            $badge_color = ($realisasi >= $target && $target > 0) ? 'badge-success' : 'badge-warning';
                            ?>
                            <tr align=center>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $k['nama']; ?></td>
                                <td><?= date('d F Y', $k['start']); ?></td>
                                <td><?= date('d F Y', $k['finish']); ?></td>
                                <td>
                                    <span class="badge <?= $badge_color; ?>"><?= $persen; ?>%
                                        (<?= $realisasi; ?>/<?= $target; ?>)</span>
                                </td>
                                <td>
                                    <a href="<?= base_url('ranking/cek_progress/') . $k['id'] ?>"
                                        class="badge badge-warning">Progress</a>
                                    <a href="<?= base_url('ranking/nilai_akhir_ranking/') . $k['id'] ?>"
                                        class="badge badge-success">Lihat ranking</a>
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