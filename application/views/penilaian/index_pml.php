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

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Kegiatan</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('penilaian/index_pml') ?>" method="get">
                        <div class="form-row align-items-end">
                            <!-- Filter Penanggung Jawab (Seksi) -->
                            <div class="form-group col-md-4">
                                <label for="seksi_id">Penanggung Jawab</label>
                                <select name="seksi_id" id="seksi_id" class="form-control">
                                    <option value="">-- Semua Penanggung Jawab --</option>
                                    <?php foreach ($seksi_list as $seksi): ?>
                                        <option value="<?= $seksi['id'] ?>" <?= ($selected_seksi == $seksi['id']) ? 'selected' : '' ?>>
                                            <?= $seksi['nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div class="form-group col-md-3">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">-- Semua Bulan --</option>
                                    <?php
                                    $bulan_indo = [
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
                                    foreach ($bulan_indo as $key => $val): ?>
                                        <option value="<?= $key ?>" <?= ($selected_bulan == $key) ? 'selected' : '' ?>>
                                            <?= $val ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Filter Tahun -->
                            <div class="form-group col-md-2">
                                <label for="tahun">Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">-- Semua Tahun --</option>
                                    <?php
                                    $current_year = date('Y');
                                    for ($i = $current_year - 2; $i <= $current_year + 2; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($selected_tahun == $i) ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-group col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                <a href="<?= base_url('penilaian/index_pml') ?>"
                                    class="btn btn-secondary btn-block">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
                            <th scope="col">Status</th>
                            <th scope="col">Progress Penilaian</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">

                        <?php foreach ($kegiatan as $k): ?>
                            <tr align="center">
                                <td><?= $k['nama']; ?></td>
                                <td><?= date('d F Y', $k['start']); ?></td>
                                <td><?= date('d F Y', $k['finish']); ?></td>
                                <?php $now = time(); ?>
                                <?php if ($now < $k['start']): ?>
                                    <td><span class="badge badge-warning">Belum Mulai</span></td>
                                <?php elseif ($now > $k['finish']): ?>
                                    <td><span class="badge badge-danger">Selesai</span></td>
                                <?php else: ?>
                                    <td><span class="badge badge-primary">Sedang Berjalan</span></td>
                                <?php endif; ?>

                                <td>
                                    <?php
                                    $percent = ($k['total_mitra'] > 0) ? round(($k['assessed_mitra'] / $k['total_mitra']) * 100) : 0;
                                    $badge_class = ($percent == 100) ? 'badge-success' : (($percent > 0) ? 'badge-info' : 'badge-secondary');
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $k['assessed_mitra'] ?> / <?= $k['total_mitra'] ?> (<?= $percent ?>%)
                                    </span>
                                </td>

                                <td>
                                    <a href="<?= base_url('penilaian/daftar_pengawas/') . $k['id'] ?>"
                                        class="badge badge-info">Lihat Daftar Pengawas</a>
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