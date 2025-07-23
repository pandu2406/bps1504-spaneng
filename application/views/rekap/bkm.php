<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg">

            <!-- Heading Periode -->
            <h5 class="mb-3 font-weight-bold text-dark">Periode: <?= $label_periode; ?></h5>

            <?php if ($selected_bulan == 0 || $selected_tahun == 0): ?>
                <div class="status-alert alert-warning">
                    <strong>Catatan:</strong> Silakan pilih <b>bulan dan tahun tertentu</b> untuk melihat rekap honor dan status kelebihan honor mitra.
                </div>
            <?php else: ?>
                <?php if (!empty($mitra_overlimit)): ?>
                    <div class="status-alert alert-danger">
                        <strong>⚠️ Peringatan!</strong> Ditemukan mitra yang melebihi honor maksimum pada periode <strong><?= $label_periode; ?></strong>:
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="table-danger">
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Total Honor</th>
                                    <th>Ambang Batas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mitra_overlimit as $o): ?>
                                    <tr>
                                    <td>
                                        <strong>
                                            <a href="<?= base_url('rekap/details_mitra/' . $o['id_mitra'] . '/' . $selected_bulan . '/' . $selected_tahun); ?>">
                                                <?= $o['nama']; ?>
                                            </a>
                                        </strong>
                                    </td>
                                        <td><?= $o['posisi']; ?></td>
                                        <td style="color:darkred">Rp<?= number_format($o['total'], 0, ',', '.'); ?></td>
                                        <td>Rp<?= number_format($o['batas'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="status-alert alert-success">
                        ✅ Tidak ada mitra yang melebihi batas honor pada periode <strong><?= $label_periode; ?></strong>.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <br>
            <?php
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            ?>

            <!-- Dropdown Bulan -->
            <div class="dropdown d-inline-block mb-3 mr-2">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <?= ($selected_bulan == 0) ? 'Semua Bulan' : $bulan[$selected_bulan - 1]; ?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= base_url('rekap/filter_periode/0/' . $selected_tahun); ?>">Semua Bulan</a>
                    <?php
                    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    foreach ($bulan as $i => $b): ?>
                        <a class="dropdown-item" href="<?= base_url('rekap/filter_periode/' . ($i + 1) . '/' . $selected_tahun); ?>">
                            <?= $b; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dropdown Tahun -->
            <div class="dropdown d-inline-block mb-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                    <?= ($selected_tahun == 0) ? 'Semua Tahun' : 'Tahun ' . $selected_tahun; ?>
                </button>
                <div class="dropdown-menu">
                    <!-- Tambahkan opsi Semua Tahun -->
                    <a class="dropdown-item" href="<?= base_url('rekap/filter_periode/' . $selected_bulan . '/0'); ?>">Semua Tahun</a>

                    <?php 
                    $tahun_sekarang = date('Y');
                    for ($t = $tahun_sekarang; $t >= $tahun_sekarang - 5; $t--): ?>
                        <a class="dropdown-item" href="<?= base_url('rekap/filter_periode/' . $selected_bulan . '/' . $t); ?>">
                            Tahun <?= $t; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>

            <a href="<?= site_url('rekap/export_excel/' . $selected_bulan . '/' . $selected_tahun); ?>" class="btn btn-success">
                Export Excel Beban Kerja Mitra
            </a>

            <a href="<?= site_url('rekap/export_nilai_excel/' . $selected_bulan . '/' . $selected_tahun); ?>" class="btn btn-danger">
                Export Excel Penilaian Kinerja Mitra
            </a>
            
            <?php
            $jumlah_terlibat = 0;
            foreach ($rekap as $r) {
                if ((int)$r['jk'] > 0 || (int)$r['jk_p'] > 0) {
                    $jumlah_terlibat++;
                }
            }
            ?>

            <?php if ($selected_bulan != 0 && $selected_tahun != 0): ?>
                <div class="alert-info font-weight-bold">
                    📊 Jumlah Mitra Terlibat Kegiatan pada periode <strong><?= $label_periode; ?></strong>: <span style="color:darkblue"><?= $jumlah_terlibat; ?> mitra</span>
                </div>
            <?php endif; ?>


            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Pengawasan</th>
                            <th scope="col">Pencacahan/Pengolahan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php $i = 1; ?>
                        <?php foreach ($rekap as $r) : ?>
                            <tr align="center">
                                <th scope="row"><?= $i++; ?></th>
                                <td><?= $r['nama']; ?></td>
                                <td><?= $r['jk']; ?></td>
                                <td><?= $r['jk_p']; ?></td>
                                <td>
                                    <a href="<?= base_url('rekap/details_mitra/' . $r['id_mitra'] . '/' . $selected_bulan . '/' . $selected_tahun); ?>" class="badge badge-success">Details Kegiatan</a>
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
<!-- End of Main Content -->
                        </div>
<!-- Optional Script -->
<script>
    document.querySelectorAll('.periode-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            const label = this.getAttribute('data-label');
            document.getElementById('dropdownPeriodeBtn').innerText = label;
        });
    });
</script>