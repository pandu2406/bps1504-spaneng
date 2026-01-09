<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit mr-2"></i>Edit Data Mitra
        </h1>
        <a href="<?= base_url('master/mitra') ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background-color: #4e73df;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-pen-square mr-2"></i>Form Edit Mitra
                    </h6>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <!-- Left Column: Personal Info -->
                            <div class="col-md-6 border-right">
                                <h6 class="font-weight-bold text-primary mb-3">Data Pribadi</h6>

                                <div class="form-group">
                                    <label for="nik" class="font-weight-bold small text-dark">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik"
                                        value="<?= $mitra['nik']; ?>" readonly>
                                    <?= form_error('nik', '<small class="text-danger pl-3">', '</small>'); ?>
                                    <small class="form-text text-muted">NIK tidak dapat diubah.</small>
                                </div>

                                <div class="form-group">
                                    <label for="nama" class="font-weight-bold small text-dark">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="<?= $mitra['nama']; ?>">
                                    <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="jk" class="font-weight-bold small text-dark">Jenis Kelamin</label>
                                    <select class="form-control" id="jk" name="jk">
                                        <option value="1" <?= ($mitra['jk'] == '1' || strtoupper($mitra['jk']) == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="2" <?= ($mitra['jk'] == '2' || strtoupper($mitra['jk']) == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                    <?= form_error('jk', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="font-weight-bold small text-dark">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="email" name="email"
                                            value="<?= $mitra['email']; ?>">
                                    </div>
                                    <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="no_hp" class="font-weight-bold small text-dark">No. HP /
                                        WhatsApp</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                                            value="<?= $mitra['no_hp']; ?>">
                                    </div>
                                    <?= form_error('no_hp', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>

                            <!-- Right Column: Assignment Info -->
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-primary mb-3">Wilayah & Penugasan</h6>

                                <div class="form-group">
                                    <label for="sobat_id" class="font-weight-bold small text-dark">Sobat ID</label>
                                    <input type="text" class="form-control" id="sobat_id" name="sobat_id"
                                        value="<?= $mitra['sobat_id']; ?>">
                                    <?= form_error('sobat_id', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="posisi" class="font-weight-bold small text-dark">Posisi</label>
                                    <select name="posisi" id="posisi" class="form-control">
                                        <option value="Mitra Pendataan" <?= ($mitra['posisi'] == 'Mitra Pendataan') ? 'selected' : ''; ?>>Mitra Pendataan</option>
                                        <option value="Mitra Pengolahan" <?= ($mitra['posisi'] == 'Mitra Pengolahan') ? 'selected' : ''; ?>>Mitra Pengolahan</option>
                                        <option value="Mitra (Pendataan dan Pengolahan)" <?= ($mitra['posisi'] == 'Mitra (Pendataan dan Pengolahan)') ? 'selected' : ''; ?>>Mitra (Pendataan dan
                                            Pengolahan)</option>
                                    </select>
                                    <?= form_error('posisi', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="kecamatan" class="font-weight-bold small text-dark">Kecamatan</label>
                                    <select class="form-control" id="kecamatan" name="kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <?php foreach ($kode_kecamatan as $kc): ?>
                                            <option value="<?= $kc['kode']; ?>" <?= ($kc['kode'] == $mitra['kecamatan']) ? 'selected' : ''; ?>>
                                                <?= $kc['nama']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('kecamatan', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="desa" class="font-weight-bold small text-dark">Desa</label>
                                    <select class="form-control" id="desa" name="desa">
                                        <option value="<?= $mitra['desa']; ?>" selected><?= $nama_desa ?? ''; ?>
                                        </option>
                                        <?php foreach ($kode_keldes as $kd): ?>
                                            <?php $kode_desa = substr($kd['kode'], 3, 3); ?>
                                            <option value="<?= $kode_desa; ?>" <?= ($kode_desa == $mitra['desa']) ? 'selected' : ''; ?>>
                                                <?= $kd['nama']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('desa', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="alamat" class="font-weight-bold small text-dark">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamat" name="alamat"
                                        rows="2"><?= $mitra['alamat']; ?></textarea>
                                    <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="<?= base_url('master/mitra') ?>" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- Scripts for dynamic dropdown -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle Kecamatan Change
        $('#kecamatan').change(function () {
            var kodeKec = $(this).val();
            if (kodeKec) {
                // Show loading state
                $('#desa').empty().append('<option value="">Memuat data desa...</option>');

                $.ajax({
                    url: "<?= base_url('master/get_desa_ajax') ?>", // Dedicated endpoint
                    type: "POST",
                    data: {
                        kode_kec: kodeKec
                    },
                    dataType: "json",
                    success: function (data) {
                        $('#desa').empty();
                        $('#desa').append('<option value="">-- Pilih Desa --</option>');
                        $.each(data, function (i, item) {
                            // item.kode is the full code (e.g., 010001)
                            $('#desa').append('<option value="' + item.kode + '">' + item.nama + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " + error);
                        $('#desa').empty().append('<option value="">Gagal memuat desa</option>');
                    }
                });
            } else {
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
            }
        });
    });
</script>