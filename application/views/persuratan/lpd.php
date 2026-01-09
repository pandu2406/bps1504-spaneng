<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Perjalanan Dinas</h1>
    </div>

    <?php if ($this->session->flashdata('file_generated')): ?>
        <script>
            // Auto download file
            window.onload = function () {
                const link = document.createElement('a');
                link.href = "<?= base_url('uploads/laporan/' . $this->session->flashdata('file_generated')) ?>";
                link.download = "";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(function () {
                    window.location.href = "<?= base_url('persuratan/lpd') ?>";
                }, 2000);
            };
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i><?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Section: Create New LPD -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">Buat Laporan Baru</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if ($user['role_id'] != 5): ?>
                            <!-- Card LPD Pegawai -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card h-100 border-left-primary shadow-sm hover-scale">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="icon-circle bg-primary text-white mr-3">
                                                <i class="fas fa-user-tie fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title text-primary font-weight-bold mb-0">Laporan Pegawai
                                                </h5>
                                                <small class="text-muted">Untuk Petugas Organik/PNS</small>
                                            </div>
                                        </div>
                                        <p class="card-text flex-grow-1 text-gray-600">Buat dokumen Laporan Perjalanan Dinas
                                            resmi untuk pegawai internal BPS.</p>
                                        <a href="<?= base_url('persuratan/lpd_pegawai'); ?>"
                                            class="btn btn-primary btn-block font-weight-bold mt-3">
                                            <i class="fas fa-plus-circle mr-2"></i>Buat LPD Pegawai
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Card LPD Mitra -->
                        <div class="<?= ($user['role_id'] != 5) ? 'col-md-6' : 'col-12'; ?>">
                            <div class="card h-100 border-left-success shadow-sm hover-scale">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success text-white mr-3">
                                            <i class="fas fa-users fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-success font-weight-bold mb-0">Laporan Mitra</h5>
                                            <small class="text-muted">Untuk Mitra Statistik</small>
                                        </div>
                                    </div>
                                    <p class="card-text flex-grow-1 text-gray-600">Buat dokumen Laporan Perjalanan Dinas
                                        untuk mitra statistik lapangan.</p>
                                    <a href="<?= base_url('persuratan/lpd_mitra'); ?>"
                                        class="btn btn-success btn-block font-weight-bold mt-3">
                                        <i class="fas fa-plus-circle mr-2"></i>Buat LPD Mitra
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($user['role_id'] != 5): ?>
                        <hr class="mt-4 mb-4">
                        <h6 class="font-weight-bold text-dark mb-3"><i
                                class="fas fa-file-excel mr-2 text-success"></i>Import Data Massal</h6>
                        <div class="bg-light p-3 rounded border">
                            <div class="row align-items-center">
                                <div class="col-md-7 mb-3 mb-md-0">
                                    <form action="<?= base_url('persuratan/import_lpd_bulk') ?>" method="post"
                                        enctype="multipart/form-data" class="d-flex align-items-center">
                                        <div class="custom-file mr-2">
                                            <input type="file" class="custom-file-input" name="file_excel" id="file_excel"
                                                required>
                                            <label class="custom-file-label" for="file_excel">Pilih file Excel...</label>
                                        </div>
                                        <button type="submit" class="btn btn-success font-weight-bold text-nowrap">
                                            <i class="fas fa-upload mr-1"></i> Import
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-5 text-md-right">
                                    <span class="text-muted small mr-2">Belum punya format?</span>
                                    <a href="<?= base_url('assets/template/template_lpd_bulk_new.xlsx'); ?>"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-download mr-2"></i>Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: History Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Riwayat Laporan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-lpd" class="table table-hover table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Petugas</th>
                            <th width="10%">Jenis</th>
                            <th width="15%">Tanggal Tugas</th>
                            <th width="20%">No. Surat Tugas</th>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($lpd_list) > 0): ?>
                            <?php foreach ($lpd_list as $i => $lpd): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $i + 1 ?></td>
                                    <td class="align-middle font-weight-bold text-dark"><?= $lpd->nama ?></td>
                                    <td class="text-center align-middle">
                                        <?php if (strtolower($lpd->jenis) == 'pegawai'): ?>
                                            <span class="badge badge-primary px-2 py-1">Pegawai</span>
                                        <?php else: ?>
                                            <span class="badge badge-success px-2 py-1">Mitra</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle"
                                        data-order="<?= date('Y-m-d', strtotime($lpd->tgl_tugas)) ?>">
                                        <?= date('d M Y', strtotime($lpd->tgl_tugas)) ?>
                                    </td>
                                    <td class="align-middle text-muted small"><?= $lpd->no_st ?></td>
                                    <td class="text-center align-middle text-muted small"
                                        data-order="<?= date('Y-m-d', strtotime($lpd->tanggal_buat)) ?>">
                                        <?= date('d/m/Y', strtotime($lpd->tanggal_buat)) ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php $filepath = base_url('uploads/laporan/' . $lpd->nama_file_word); ?>
                                        <a href="<?= $filepath ?>" class="btn btn-sm btn-info shadow-sm" target="_blank"
                                            title="Download Word">
                                            <i class="fas fa-file-word"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<!-- Styles for Card Hover & File Input -->
<style>
    .hover-scale {
        transition: transform 0.2s ease-in-out;
    }

    .hover-scale:hover {
        transform: translateY(-5px);
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- jQuery & DataTables CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<!-- Bootstrap 4 Theme -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Init Scripts -->
<script>
    $(document).ready(function () {
        // Init File Input Label
        $('.custom-file-input').on('change', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Init DataTable with Bootstrap 4 styling
        $('#table-lpd').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[5, "desc"]] // Urutkan berdasarkan tanggal buat (desc)
        });
    });
</script>