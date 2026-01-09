<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?= $title; ?>
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white mb-2"><i class="fas fa-list mr-2"></i>Rekapitulasi Penilaian
                Seluruh Mitra</h6>

            <form action="" method="get" class="form-inline">
                <select name="bulan" class="form-control form-control-sm mr-2" style="font-size: 0.85rem;">
                    <option value="">- Semua Bulan -</option>
                    <?php
                    $months = [
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
                    foreach ($months as $key => $val) {
                        $selected = ($this->input->get('bulan') == $key) ? 'selected' : '';
                        echo "<option value='$key' $selected>$val</option>";
                    }
                    ?>
                </select>
                <select name="tahun" class="form-control form-control-sm mr-2" style="font-size: 0.85rem;">
                    <option value="">- Semua Tahun -</option>
                    <?php
                    $current_year = date('Y');
                    for ($i = $current_year; $i >= $current_year - 5; $i--) {
                        $selected = ($this->input->get('tahun') == $i) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
                <input type="text" name="nama" class="form-control form-control-sm mr-2"
                    placeholder="Cari Nama Mitra..." value="<?= $this->input->get('nama'); ?>"
                    style="font-size: 0.85rem;">

                <button type="submit" class="btn btn-sm btn-light text-primary font-weight-bold">Filter</button>
                <a href="<?= base_url('penilaian'); ?>" class="btn btn-sm btn-outline-light ml-2">Reset</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableSummary" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr align="center">
                            <th>Nama Mitra</th>
                            <th>NIK</th>
                            <th>JK</th>
                            <th>Rata-rata Nilai</th>
                            <th>Total Kegiatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mitra_summary as $ms): ?>
                            <tr>
                                <td>
                                    <?= $ms['nama']; ?>
                                </td>
                                <td align="center">
                                    <?= $ms['nik']; ?>
                                </td>
                                <td align="center">
                                <td align="center">
                                    <?= $ms['jk'] == '1' ? 'Laki-laki' : 'Perempuan'; ?>
                                </td>
                                </td>
                                <td align="center">
                                    <?php if ($ms['rata_rata']): ?>
                                        <span
                                            class="font-weight-bold <?= $ms['rata_rata'] >= 80 ? 'text-success' : 'text-primary' ?>">
                                            <?= number_format($ms['rata_rata'], 2); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted italic">Belum dinilai</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <span class="badge badge-info shadow-sm px-3">
                                        <?= $ms['total_kegiatan']; ?>
                                    </span>
                                </td>
                                <td align="center">
                                    <a href="<?= base_url('master/details_mitra/') . $ms['id_mitra']; ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        $('#dataTableSummary').DataTable({
            "order": [[3, "desc"], [4, "desc"]],
            "pageLength": 25,
            "language": {
                "search": "Cari Mitra:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>