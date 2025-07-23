<!-- Begin Page Content -->
<div class="container-fluid">
    <?php if ($this->session->flashdata('file_generated')): ?>
<script>
    // Auto download file
    window.onload = function() {
        const link = document.createElement('a');
        link.href = "<?= base_url('uploads/laporan/' . $this->session->flashdata('file_generated')) ?>";
        link.download = "";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Setelah 2 detik, redirect ulang ke halaman ini (opsional)
        setTimeout(function() {
            window.location.href = "<?= base_url('persuratan/lpd') ?>";
        }, 2000); // tunggu 2 detik agar file selesai didownload
    };
</script>
<?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success'); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

    <div class="card shadow p-4">
        <h4 class="mb-4 font-weight-bold text-success">Pilih Jenis Laporan Perjalanan Dinas</h4>
        <div class="row">
            <?php if ($user['role_id'] != 5): ?>
            <!-- Card LPD Pegawai -->
            <div class="col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Laporan Pegawai</h5>
                        <p class="card-text">Buat dokumen Laporan Perjalanan Dinas untuk petugas pegawai.</p>
                        <a href="<?= base_url('persuratan/lpd_pegawai'); ?>" class="btn btn-primary">Generate LPD Pegawai</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Card LPD Mitra -->
            <div class="col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">Laporan Mitra</h5>
                        <p class="card-text">Buat dokumen Laporan Perjalanan Dinas untuk petugas mitra.</p>
                        <a href="<?= base_url('persuratan/lpd_mitra'); ?>" class="btn btn-success">Generate LPD Mitra</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($user['role_id'] != 5): ?>
        <!-- Upload Template Excel -->
        <hr>
        <h5 class="mt-4 font-weight-bold text-dark">Upload Template LPD Massal (Excel)</h5>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= base_url('assets/template/template_lpd_bulk_new.xlsx'); ?>" class="btn btn-outline-info btn-sm">
                <i class="fas fa-file-download"></i> Download Template Excel
            </a>
        </div>
        
        
        <form action="<?= base_url('persuratan/import_lpd_bulk') ?>" method="post" enctype="multipart/form-data" class="mb-4">
            <div class="form-group">
                <label for="file_excel">Upload File Excel</label>
                <input type="file" name="file_excel" id="file_excel" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-upload"></i> Import & Generate
            </button>
        </form>
        <?php endif; ?>

        <!-- Tabel Riwayat Surat LPD -->
        <hr>
        <h5 class="mt-4 font-weight-bold text-dark">Riwayat Surat LPD yang Telah Dibuat</h5>
        <div class="table-responsive mt-3">
            <table id="table-lpd" class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Tanggal Tugas</th>
                        <th>No. Surat Tugas</th>
                        <th>Tanggal Dibuat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($lpd_list) >= 0): ?>
                        <?php foreach ($lpd_list as $i => $lpd): ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= $lpd->nama ?></td>
                                <td class="text-center"><?= ucfirst($lpd->jenis) ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($lpd->tgl_tugas)) ?></td>
                                <td><?= $lpd->no_st ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($lpd->tanggal_buat)) ?></td>
                                <td class="text-center">
                                    <?php
                                        $filepath = base_url('uploads/laporan/' . $lpd->nama_file_word);
                                    ?>
                                    <a href="<?= $filepath ?>" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">Belum ada LPD yang dibuat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<!-- jQuery & DataTables CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Inisialisasi DataTables -->
<script>
    $(document).ready(function () {
        $('#table-lpd').DataTable({
            dom: 'lfrtip',
            language: {
                search: "Cari:",
                searchPlaceholder: "Ketik untuk mencari...",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                },
                zeroRecords: "Tidak ditemukan data yang cocok",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari total _MAX_ entri)"
            },
            initComplete: function () {
                // Perbesar dropdown jumlah entri
                $('div.dataTables_length select').css({
                    'min-width': '70px',
                    'padding': '0.3rem'
                });

                // Perbesar kolom pencarian
                $('div.dataTables_filter input').css({
                    'width': '180px',
                    'display': 'inline-block',
                    'padding': '0.3rem'
                });

                // Renggangkan label jika perlu
                $('div.dataTables_length label').css({
                    'margin-right': '10px'
                });
            }
        });
    });
</script>
