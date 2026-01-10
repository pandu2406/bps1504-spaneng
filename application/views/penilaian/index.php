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

            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <form action="" method="get" class="form-inline">
                        <label class="mr-2 font-weight-bold" style="color:#00264d;">Filter Periode:</label>

                        <select name="bulan" class="form-control form-control-sm mr-2">
                            <option value="">- Semua Bulan -</option>
                            <?php
                            $bulan_list = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];
                            foreach ($bulan_list as $k => $v):
                                ?>
                                <option value="<?= $k ?>" <?= ($selected_bulan == $k) ? 'selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>

                        <select name="tahun" class="form-control form-control-sm mr-2">
                            <option value="">- Semua Tahun -</option>
                            <?php for ($t = date('Y') - 1; $t <= date('Y') + 1; $t++): ?>
                                <option value="<?= $t ?>" <?= ($selected_tahun == $t) ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endfor; ?>
                        </select>

                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="<?= base_url('penilaian') ?>" class="btn btn-sm btn-secondary ml-1">Reset</a>
                    </form>
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
                            <th scope="col">Progress Penilaian</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($kegiatan as $k): ?>
                            <tr align=center>
                                <td><?= $k['nama']; ?></td>
                                <td><?= date('d F Y', $k['start']); ?></td>
                                <td><?= date('d F Y', $k['finish']); ?></td>
                                <?php $now = (time()); ?>
                                <?php if ($now < $k['start']): ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $k['finish']): ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else: ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
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
                                    <a href="<?= base_url('penilaian/daftar_pencacah/') . $k['kegiatan_id'] . '/' . $id_peg; ?>"
                                        class="badge badge-info">Lihat daftar pencacah</a>
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