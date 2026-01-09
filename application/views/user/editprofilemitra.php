<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <?= $this->session->flashdata('message'); ?>
            <?= form_open_multipart('user/editprofilemitra'); ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background-color: #4e73df;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-user-edit mr-2"></i>Edit Profile Mitra
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column: Personal Info -->
                        <div class="col-md-6 border-right detail-section">
                            <h6 class="font-weight-bold text-primary mb-3">Data Pribadi</h6>

                            <!-- Foto Profil -->
                            <div class="form-group row align-items-center">
                                <div class="col-sm-3">Foto</div>
                                <div class="col-sm-9">
                                    <div class="row align-items-center">
                                        <div class="col-sm-4">
                                            <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>"
                                                class="img-thumbnail rounded-circle shadow-sm"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image">
                                                <label class="custom-file-label" for="image">Ubah...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nik" class="font-weight-bold small">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik"
                                    value="<?= $mitra['nik']; ?>" readonly>
                                <small class="text-muted">NIK tidak dapat diubah.</small>
                            </div>

                            <div class="form-group">
                                <label for="nama" class="font-weight-bold small">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="<?= $mitra['nama']; ?>">
                                <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>

                            <div class="form-group">
                                <label for="jk" class="font-weight-bold small">Jenis Kelamin</label>
                                <select class="form-control" id="jk" name="jk">
                                    <option value="1" <?= ($mitra['jk'] == '1' || strtoupper($mitra['jk']) == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="2" <?= ($mitra['jk'] == '2' || strtoupper($mitra['jk']) == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                                <?= form_error('jk', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>

                            <div class="form-group">
                                <label for="email" class="font-weight-bold small">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="<?= $user['email']; ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="no_hp" class="font-weight-bold small">No. HP / WhatsApp</label>
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

                        <!-- Right Column: Wilayah & Penugasan -->
                        <div class="col-md-6 detail-section">
                            <h6 class="font-weight-bold text-primary mb-3">Wilayah & Penugasan</h6>

                            <div class="form-group">
                                <label for="sobat_id" class="font-weight-bold small">Sobat ID</label>
                                <input type="text" class="form-control" id="sobat_id" name="sobat_id"
                                    value="<?= $mitra['sobat_id']; ?>">
                                <?= form_error('sobat_id', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>

                            <div class="form-group">
                                <label for="posisi" class="font-weight-bold small">Posisi (Tahun
                                    <?= $mitra['tahun_posisi'] ?? date('Y') ?>)</label>
                                <input type="text" class="form-control" value="<?= $mitra['posisi']; ?>" readonly>
                                <small class="text-muted">Posisi ditentukan oleh Admin.</small>
                            </div>

                            <div class="form-group">
                                <label for="kecamatan" class="font-weight-bold small">Kecamatan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan">
                                    <option value="">Pilih Kecamatan</option>
                                    <?php foreach ($kode_kecamatan as $k): ?>
                                        <option value="<?= $k['kode']; ?>" <?= ($mitra['kecamatan'] == $k['kode']) ? 'selected' : ''; ?>>
                                            <?= $k['nama']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('kecamatan', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>

                            <div class="form-group">
                                <label for="desa" class="font-weight-bold small">Desa</label>
                                <select class="form-control" id="desa" name="desa">
                                    <option value="<?= $mitra['desa']; ?>" selected><?= $nama_desa ?? 'Pilih Desa'; ?>
                                    </option>
                                    <?php foreach ($kode_keldes as $d): ?>
                                        <?php
                                        // Handle kode desa matching
                                        $suffix = substr($d['kode'], -3);
                                        $isSelected = ($suffix == $mitra['desa']) ? 'selected' : '';
                                        if (!$isSelected)
                                            continue; // Don't duplicate selected if populated by loop
                                        ?>
                                        <!-- Loop populate handled by JS mostly, but initial if loaded -->
                                        <option value="<?= $d['kode']; ?>" <?= $isSelected; ?>><?= $d['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('desa', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>

                            <div class="form-group">
                                <label for="alamat" class="font-weight-bold small">Alamat Lengkap</label>
                                <textarea class="form-control" id="alamat" name="alamat"
                                    rows="3"><?= $mitra['alamat']; ?></textarea>
                                <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                        </div>
                    </div>

                    <hr class="sidebar-divider">

                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="<?= base_url('user'); ?>" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <br>

    <!-- Dark Mode Support -->
    <style>
        /* Force Theme Adaptation for Inputs */
        .form-control {
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        .form-control:focus {
            background-color: var(--bg-content) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-control[readonly] {
            background-color: var(--border-color) !important;
            /* Slightly darker/distinct */
            opacity: 0.7;
        }

        /* Label Colors */
        label,
        .col-sm-3,
        .small {
            color: var(--text-main) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Input Group Text (Icons) */
        .input-group-text {
            background-color: var(--bg-sidebar) !important;
            border-color: var(--border-color) !important;
            color: var(--text-sidebar) !important;
        }

        /* Custom File Input */
        .custom-file-label {
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        .custom-file-label::after {
            background-color: var(--bg-sidebar) !important;
            color: var(--text-main) !important;
        }

        /* Border right fix for mobile */
        @media (max-width: 768px) {
            .border-right {
                border-right: none !important;
                border-bottom: 1px solid var(--border-color) !important;
                margin-bottom: 1.5rem;
                padding-bottom: 1.5rem;
            }
        }

        /* Dark Mode Support via Attribute */
        [data-theme="dark"] .container-fluid {
            color: #e2e8f0;
        }

        [data-theme="dark"] input.form-control,
        [data-theme="dark"] textarea.form-control,
        [data-theme="dark"] select.form-control,
        [data-theme="dark"] .custom-file-label {
            background-color: #2d3748 !important;
            border-color: #4a5568 !important;
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] input.form-control:focus,
        [data-theme="dark"] textarea.form-control:focus,
        [data-theme="dark"] select.form-control:focus {
            background-color: #2d3748 !important;
            color: #fff !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 179, 237, 0.25) !important;
        }

        [data-theme="dark"] .form-group label,
        [data-theme="dark"] .form-group .small,
        [data-theme="dark"] .form-group .text-dark {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .text-muted {
            color: #a0aec0 !important;
        }

        [data-theme="dark"] .input-group-text {
            background-color: #4a5568 !important;
            border-color: #4a5568 !important;
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .custom-file-label::after {
            background-color: #4a5568 !important;
            color: #fff !important;
        }

        /* Override the inline style from the view if possible, or use !important */
        [data-theme="dark"] div[style*="color:#00264d"] {
            color: #e2e8f0 !important;
        }
    </style>

    <!-- Use jQuery if available (usually loaded in template footer). If not, ensure it is loaded or use vanilla JS carefully. -->
    <!-- Assuming SB Admin 2 template loads jQuery in footer, but we can put this script at bottom or use DOMContentLoaded -->
    <script>
        // Check if jQuery is loaded, if not, fallback or wait. 
        // We'll write vanilla JS that mimics the logic but robustly.
        document.addEventListener('DOMContentLoaded', function () {
            const kecSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');

            kecSelect.addEventListener('change', function () {
                const kodeKec = this.value;
                desaSelect.innerHTML = '<option value="">Memuat...</option>';

                if (kodeKec) {
                    // Using jQuery AJAX style via Fetch for standard compliance, 
                    // OR use jQuery syntax if user prefers. 
                    // Let's stick to fetch but ensure headers are correct for CodeIgniter is_ajax_request().

                    const formData = new FormData();
                    formData.append('kode_kec', kodeKec);

                    fetch('<?= base_url("user/get_desa_ajax") ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
                            data.forEach(d => {
                                const option = document.createElement('option');
                                // Value should probably be the full code so Controller can handle it (strip suffix)
                                option.value = d.kode;
                                option.textContent = d.nama;
                                desaSelect.appendChild(option);
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            desaSelect.innerHTML = '<option value="">Gagal memuat desa</option>';
                        });
                } else {
                    desaSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
                }
            });
        });
    </script>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->