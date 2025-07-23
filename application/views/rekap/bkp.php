<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg">

            <!-- Heading Periode -->
            <h5 class="mb-3 font-weight-bold text-dark">Periode: <?= $label_periode; ?></h5>

            <!-- Dropdown Bulan -->
            <div class="dropdown d-inline-block mb-3 mr-2">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    Pilih Bulan
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= base_url('rekap/filter_periode_pegawai/0/' . $selected_tahun); ?>">Semua Bulan</a>
                    <?php
                    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    foreach ($bulan as $i => $b): ?>
                        <a class="dropdown-item" href="<?= base_url('rekap/filter_periode_pegawai/' . ($i + 1) . '/' . $selected_tahun); ?>"><?= $b; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dropdown Tahun -->
            <div class="dropdown d-inline-block mb-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                    Pilih Tahun
                </button>
                <div class="dropdown-menu">
                    <?php 
                    $tahun_sekarang = date('Y');
                    for ($t = $tahun_sekarang; $t >= $tahun_sekarang - 5; $t--): ?>
                        <a class="dropdown-item" href="<?= base_url('rekap/filter_periode_pegawai/' . $selected_bulan . '/' . $t); ?>">Tahun <?= $t; ?></a>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Pengawasan</th>
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
                                <td>
                                    <a href="<?= base_url('rekap/details_pegawai/' . $r['id_peg'] . '/' . $selected_bulan . '/' . $selected_tahun); ?>" class="badge badge-success">Details Kegiatan</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <br>
</div>
<!-- End of Main Content -->
                        </div>