<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?= $title; ?>
        </h1>
    </div>

    <div class="card shadow-lg mb-4 border-0">
        <div class="card-header py-3 bg-gradient-primary d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-chart-bar mr-2"></i>Rekapitulasi Penilaian Mitra</h6>
        </div>
        
        <div class="card-body bg-white">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <form action="" method="get" class="form-row align-items-center bg-light p-3 rounded border">
                        <div class="col-auto">
                            <label class="sr-only" for="filterBulan">Bulan</label>
                            <div class="input-group mb-2 mb-sm-0">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-white border-right-0"><i class="far fa-calendar-alt text-primary"></i></div>
                                </div>
                                <select name="bulan" id="filterBulan" class="form-control border-left-0" style="font-size: 0.9rem;">
                                    <option value="">- Filter Bulan -</option>
                                    <?php
                                    $months = [
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    ];
                                    foreach ($months as $key => $val) {
                                        $selected = ($this->input->get('bulan') == $key) ? 'selected' : '';
                                        echo "<option value='$key' $selected>$val</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="sr-only" for="filterTahun">Tahun</label>
                            <div class="input-group mb-2 mb-sm-0">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-white border-right-0"><i class="fas fa-calendar text-primary"></i></div>
                                </div>
                                <select name="tahun" id="filterTahun" class="form-control border-left-0" style="font-size: 0.9rem;">
                                    <option value="">- Filter Tahun -</option>
                                    <?php
                                    $current_year = date('Y');
                                    for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                        $selected = ($this->input->get('tahun') == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group mb-2 mb-sm-0">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></div>
                                </div>
                                <input type="text" name="nama" class="form-control border-left-0" placeholder="Cari Nama Mitra..." value="<?= $this->input->get('nama'); ?>" style="font-size: 0.9rem;">
                            </div>
                        </div>
                        <div class="col-auto ml-auto">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-filter mr-1"></i> Terapkan</button>
                            <a href="<?= base_url('penilaian'); ?>" class="btn btn-outline-secondary ml-2 shadow-sm"><i class="fas fa-redo-alt mr-1"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTablePremium" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="align-middle text-center border-top-0 shadow-sm" style="min-width: 200px;">Nama Mitra / NIK</th>
                            <th class="align-middle text-center border-top-0 shadow-sm">Jenis Kelamin</th>
                            <th class="align-middle text-center border-top-0 shadow-sm">Total Kegiatan</th>
                            <th class="align-middle text-center border-top-0 shadow-sm">Rata-rata Nilai</th>
                            <th class="align-middle text-center border-top-0 shadow-sm" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mitra_summary as $ms): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-3 bg-gradient-light text-primary font-weight-bold d-flex justify-content-center align-items-center shadow-sm" style="width: 40px; height: 40px; border-radius: 50%;">
                                            <?= substr($ms['nama'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800"><?= $ms['nama']; ?></div>
                                            <div class="small text-muted"><i class="far fa-id-card mr-1"></i> <?= $ms['nik']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <?php if ($ms['jk'] == '1'): ?>
                                        <span class="badge badge-pill badge-primary px-3 py-2 shadow-sm"><i class="fas fa-mars mr-1"></i> Laki-laki</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-info px-3 py-2 shadow-sm"><i class="fas fa-venus mr-1"></i> Perempuan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="font-weight-bold text-gray-700 h5"><?= $ms['total_kegiatan']; ?></span>
                                    <div class="small text-muted">Kegiatan</div>
                                </td>
                                <td class="align-middle text-center">
                                    <?php if ($ms['rata_rata']): ?>
                                        <?php 
                                            $nilai = $ms['rata_rata'];
                                            $badge_class = 'badge-secondary';
                                            $text_class = 'text-muted';
                                            if ($nilai >= 90) { $badge_class = 'badge-success'; $text_class = 'text-success'; }
                                            elseif ($nilai >= 80) { $badge_class = 'badge-info'; $text_class = 'text-info'; }
                                            elseif ($nilai >= 70) { $badge_class = 'badge-warning'; $text_class = 'text-warning'; }
                                            else { $badge_class = 'badge-danger'; $text_class = 'text-danger'; }
                                        ?>
                                        <div class="h5 font-weight-bold mb-0 <?= $text_class ?>"><?= number_format($nilai, 2); ?></div>
                                        <div class="progress progress-sm mt-1" style="height: 4px; width: 60%; margin: 0 auto;">
                                            <div class="progress-bar bg-<?= str_replace('badge-', '', $badge_class) ?>" role="progressbar" style="width: <?= $nilai ?>%" aria-valuenow="<?= $nilai ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-light text-muted border">Belum Dinilai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center">
                                    <?php
                                    $filter_query = http_build_query([
                                        'bulan' => $this->input->get('bulan'),
                                        'tahun' => $this->input->get('tahun')
                                    ]);
                                    $link_detail = base_url('master/details_mitra/') . $ms['id_mitra'] . '?' . $filter_query;
                                    ?>
                                    <a href="<?= $link_detail; ?>" class="btn btn-sm btn-outline-primary shadow-sm rounded-circle p-2" title="Lihat Detail" data-toggle="tooltip">
                                        <i class="fas fa-chevron-right"></i>
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

<!-- Custom Page Styles -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    }
    .avatar-circle {
        font-size: 1.2rem;
    }
    table.dataTable thead th {
        border-bottom: 2px solid #e3e6f0 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }
    table.dataTable tbody td {
        vertical-align: middle !important;
    }
    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>

<!-- Functional Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Destroy existing if any (prevent duplication from demo scripts)
    if ($.fn.DataTable.isDataTable('#dataTablePremium')) {
        $('#dataTablePremium').DataTable().destroy();
    }

    // Initialize Premium Table
    var table = $('#dataTablePremium').DataTable({
        "order": [[3, "desc"], [2, "desc"]], // Sort by Rata-rata (3) then Total (2)
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        "columnDefs": [
            { "orderable": false, "targets": [4] }, // Disable sorting on Action
            { "searchable": false, "targets": [4] }
        ],
        "orderCellsTop": true,
        "fixedHeader": true,
        "language": {
            "search": "",
            "searchPlaceholder": "Filter cepat...",
            "lengthMenu": "Tampil _MENU_",
            "info": "Data _START_ - _END_ dari _TOTAL_",
            "paginate": {
                "first": "<i class='fas fa-angle-double-left'></i>",
                "last": "<i class='fas fa-angle-double-right'></i>",
                "next": "<i class='fas fa-angle-right'></i>",
                "previous": "<i class='fas fa-angle-left'></i>"
            },
            "emptyTable": "Tidak ada data yang ditemukan"
        },
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "drawCallback": function() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // Custom styling for DataTables elements
    $('.dataTables_filter input').addClass('form-control form-control-sm border-0 bg-light shadow-sm').parent().addClass('p-0');
    $('.dataTables_length select').addClass('form-control form-control-sm border-0 bg-light shadow-sm mx-2');
});
</script>