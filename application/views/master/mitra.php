<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('mitra', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <div class="card shadow mb-4">
                <!-- Card Header with Toggle -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    style="background-color: #00264d;">
                    <h6 class="m-0 font-weight-bold text-white">Daftar Mitra - Tahun <?= $tahun ?></h6>
                </div>
                <div class="card-body">
                    <!-- Nav Tabs for Years -->
                    <ul class="nav nav-tabs mb-4">
                        <?php
                        $available_years = [2024, 2025, 2026];
                        foreach ($available_years as $y):
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($tahun == $y) ? 'active font-weight-bold text-primary border-bottom-0' : 'text-secondary' ?>"
                                    href="<?= base_url('master/mitra/' . $y) ?>">
                                    <i class="fas fa-calendar-alt mr-1"></i> Mitra <?= $y ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <a href="" class="btn btn-primary mr-2" data-toggle="modal" data-target="#newMitraModal">
                                <i class="fas fa-plus-circle mr-1"></i> Add New Mitra (<?= $tahun ?>)
                            </a>
                            <a href="" class="btn btn-success mr-2" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-file-upload mr-1"></i> Import Data
                            </a>
                            <a href="<?= base_url('master/download_format') ?>" class="btn btn-outline-danger">
                                <i class="fas fa-file-download mr-1"></i> Format Import
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive" id="tableContainer">
                        <table class="table table-hover table-bordered" id="mydata" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr align=center>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col">Nama / Sobat ID</th>
                                    <th scope="col">Posisi</th>
                                    <th scope="col">Kecamatan</th>
                                    <th scope="col">JK</th>
                                    <th scope="col">Kontak</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($mitra as $m): ?>
                                    <tr align=center>
                                        <th scope="row"><?= $i; ?></th>
                                        <td align="left">
                                            <div class="font-weight-bold text-dark text-capitalize"><?= $m['nama']; ?></div>
                                            <small class="text-muted">ID: <?= $m['sobat_id']; ?></small>
                                        </td>
                                        <td><span class="badge badge-info shadow-sm"><?= $m['posisi']; ?></span></td>
                                        <td><?= $m['nama_kecamatan']; ?></td>
                                        <td>
                                            <?php if ($m['jk'] == '1' || strtoupper($m['jk']) == 'L'): ?>
                                                <i class="fas fa-mars fa-lg text-primary" title="Laki-laki"></i>
                                            <?php else: ?>
                                                <i class="fas fa-venus fa-lg text-danger" title="Perempuan"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Ensure clean number for URL
                                            $wa_num = preg_replace('/[^0-9]/', '', $m['no_hp']);
                                            // Convert 08 to 628 just in case viewing legacy data not yet script-fixed
                                            if (substr($wa_num, 0, 2) == '08')
                                                $wa_num = '62' . substr($wa_num, 1);
                                            ?>
                                            <a href="https://wa.me/<?= $wa_num ?>" target="_blank"
                                                class="text-success font-weight-bold" style="text-decoration: none;">
                                                <i class="fab fa-whatsapp fa-lg mr-1"></i> <?= $m['no_hp']; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($m['is_active'] == '1'): ?>
                                                <span class="badge badge-success shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i> Active
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger shadow-sm">
                                                    <i class="fas fa-times-circle mr-1"></i> Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('master/details_mitra/') . $m['id_mitra']; ?>"
                                                    class="btn btn-sm btn-info" title="Details">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <a href="<?= base_url('master/editmitra/') . $m['id_mitra']; ?>"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($m['is_active'] == '1'): ?>
                                                    <a href="<?= base_url('master/deactivated/') . $m['id_mitra'] . '/' . $tahun; ?>"
                                                        class="btn btn-sm btn-warning" title="Deactivate"
                                                        onclick="return confirm('Apakah anda yakin ingin menonaktifkan mitra ini?');">
                                                        <i class="fas fa-power-off"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('master/activated/') . $m['id_mitra'] . '/' . $tahun; ?>"
                                                        class="btn btn-sm btn-primary" title="Activate">
                                                        <i class="fas fa-power-off"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= base_url('master/deletemitra/') . $m['id_mitra'] . '/' . $tahun; ?>"
                                                    class="btn btn-sm btn-danger" title="Delete"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-upload mr-2"></i>Import Data Mitra (Excel)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/import/') . $tahun; ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Hidden input for year -->
                    <input type="hidden" name="tahun_input" value="<?= $tahun ?>">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Perhatian:</strong> Data yang diimport akan didaftarkan untuk tahun
                        <strong><?= $tahun ?></strong>.
                        <br>
                        <small>Jika NIK sudah ada, biodata akan di-update dan mitra akan terdaftar untuk tahun
                            ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih File Excel:</label>
                        <input type="file" class="form-control-file border p-2 rounded" name="excel" accept=".xls,.xlsx"
                            required>
                        <small class="form-text text-muted">
                            Format: .xls atau .xlsx | Max: 5MB
                        </small>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('master/download_format') ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-download mr-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-upload mr-1"></i> Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="newMitraModal" tabindex="-1" role="dialog" aria-labelledby="newMitraModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMitraModalLabel">Add New Mitra (Tahun <?= $tahun ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/mitra/') . $tahun ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="tahun_input" value="<?= $tahun ?>">

                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold text-primary mb-3">Data Pribadi</h6>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" placeholder="16 digit NIK"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Sesuai KTP"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="contoh@mail.com" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">No. HP / WhatsApp</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Jenis Kelamin</label>
                                <select name="jk" id="jk" class="form-control" required>
                                    <option value="">Pilih JK</option>
                                    <option value="1">Laki-Laki</option>
                                    <option value="2">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-primary mb-3">Wilayah & Penugasan</h6>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Sobat ID</label>
                                <input type="text" class="form-control" id="sobat_id" name="sobat_id"
                                    placeholder="ID Sobat BPS" required>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Posisi</label>
                                <select name="posisi" id="posisi" class="form-control" required>
                                    <option value="">Pilih Posisi</option>
                                    <option value="Mitra Pendataan">Mitra Pendataan</option>
                                    <option value="Mitra Pengolahan">Mitra Pengolahan</option>
                                    <option value="Mitra (Pendataan dan Pengolahan)">Mitra (Pendataan dan Pengolahan)
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Kecamatan Domisili</label>
                                <select name="kecamatan" id="kecamatan" class="form-control" required>
                                    <option value="">Pilih Kecamatan</option>
                                    <?php foreach ($kec as $k): ?>
                                        <option value="<?= $k['kode']; ?>"><?= $k['nama']; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Desa/Kelurahan</label>
                                <select name="desa" id="desa" class="form-control" required>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Alamat Detail</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2"
                                    placeholder="Nama Jalan, No. Rumah, dll." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fas fa-file-upload mr-2"></i>Import Data Mitra - Tahun <?= $tahun ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('master/import/' . $tahun) ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Perhatian:</strong> Data yang diimport akan didaftarkan untuk tahun
                        <strong><?= $tahun ?></strong>.
                        <br>
                        <small>Jika NIK sudah ada, biodata akan di-update dan mitra akan terdaftar untuk tahun
                            ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih File Excel:</label>
                        <input type="file" class="form-control-file border p-2 rounded" name="excel" accept=".xls,.xlsx"
                            required>
                        <small class="form-text text-muted">
                            Format: .xls atau .xlsx | Max: 5MB
                        </small>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('master/download_format') ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-download mr-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-upload mr-1"></i> Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Kecamatan - Desa Dynamic Dropdown
        $('#kecamatan').change(function () {
            var kodeKec = $(this).val();
            if (kodeKec) {
                $.ajax({
                    url: "<?= base_url('master/get_desa_ajax') ?>",
                    type: "POST",
                    data: { kode_kec: kodeKec },
                    dataType: "json",
                    success: function (data) {
                        $('#desa').empty();
                        $('#desa').append('<option value="">Pilih Desa</option>');
                        $.each(data, function (i, item) {
                            $('#desa').append('<option value="' + item.kode + '">' + item.nama + '</option>');
                        });
                    }
                });
            } else {
                $('#desa').empty().append('<option value="">Pilih Desa</option>');
            }
        });

        // Delete Confirmation with Event Delegation
        $(document).on('click', '.btn-delete-mitra', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const nama = $(this).data('nama');

            Swal.fire({
                title: 'Hapus Mitra?',
                text: "Anda akan menghapus mitra: " + nama + ". Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Deactivate Confirmation with Event Delegation
        $(document).on('click', '.btn-deactivate-mitra', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Nonaktifkan Mitra?',
                text: "Mitra ini tidak akan bisa login atau menerima tugas baru.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6c23e',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Nonaktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });

        // Activate Confirmation with Event Delegation
        $(document).on('click', '.btn-activate-mitra', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Aktifkan Mitra?',
                text: "Mitra akan kembali aktif dan bisa menerima tugas.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    </script>
</div>