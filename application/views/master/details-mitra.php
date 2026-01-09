<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle mr-2"></i>Detail Mitra
        </h1>
        <a href="<?= base_url('master/mitra') ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <img src="<?= base_url('assets/img/profile/') . ($user['image'] ?? 'default.jpg'); ?>"
                        class="rounded-circle mb-3 shadow" alt="Profile" width="150" height="150"
                        style="object-fit: cover; border: 4px solid #4e73df;">

                    <h4 class="font-weight-bold text-primary mb-1"><?= $mitra['nama']; ?></h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-id-card mr-1"></i><?= $mitra['nik'] ?>
                    </p>
                    <p class="mb-3">
                        <span class="badge badge-info badge-lg px-3 py-2">
                            <?= $mitra['posisi'] ?? 'Mitra Pendataan'; ?>
                        </span>
                    </p>

                    <?php if ($mitra['is_active'] == '1'): ?>
                        <span class="badge badge-success shadow-sm px-3 py-2">
                            <i class="fas fa-check-circle mr-1"></i> Active
                        </span>
                    <?php else: ?>
                        <span class="badge badge-danger shadow-sm px-3 py-2">
                            <i class="fas fa-times-circle mr-1"></i> Inactive
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3" style="background-color: #4e73df;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Lengkap
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </label>
                                <p class="text-gray-800"><?= $mitra['email'] ?: '-'; ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-phone mr-1"></i>No. HP / WhatsApp
                                </label>
                                <p class="text-gray-800">
                                    <?php if ($mitra['no_hp']): ?>
                                        <a href="https://wa.me/62<?= ltrim($mitra['no_hp'], '0') ?>" target="_blank"
                                            class="text-success">
                                            <i class="fab fa-whatsapp mr-1"></i><?= $mitra['no_hp']; ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-venus-mars mr-1"></i>Jenis Kelamin
                                </label>
                                <p class="text-gray-800">
                                    <?php if ($mitra['jk'] == '1' || strtoupper($mitra['jk']) == 'L'): ?>
                                        <i class="fas fa-mars text-primary mr-1"></i> Laki-laki
                                    <?php else: ?>
                                        <i class="fas fa-venus text-danger mr-1"></i> Perempuan
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-id-badge mr-1"></i>Sobat ID
                                </label>
                                <p class="text-gray-800"><?= $mitra['sobat_id'] ?: '-'; ?></p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Kecamatan
                                </label>
                                <p class="text-gray-800"><?= $mitra['nama_kecamatan'] ?: $mitra['kecamatan'] ?: '-'; ?>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-building mr-1"></i>Desa / Kelurahan
                                </label>
                                <p class="text-gray-800"><?= $mitra['nama_desa'] ?: $mitra['desa'] ?: '-'; ?></p>
                            </div>

                            <div class="mb-3">
                                <label class="small font-weight-bold text-primary">
                                    <i class="fas fa-home mr-1"></i>Alamat Lengkap
                                </label>
                                <p class="text-gray-800"><?= $mitra['alamat'] ?: '-'; ?></p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-tasks mr-2"></i>Aksi
                            </h6>
                            <a href="<?= base_url('kegiatan/details_mitra_kegiatan/') . $mitra['id_mitra']; ?>"
                                class="btn btn-primary shadow-sm mr-2">
                                <i class="fas fa-chart-line mr-1"></i> Lihat Nilai & Kegiatan
                            </a>
                            <a href="<?= base_url('master/editmitra/') . $mitra['id_mitra']; ?>"
                                class="btn btn-success shadow-sm mr-2">
                                <i class="fas fa-edit mr-1"></i> Edit Data
                            </a>
                            <?php if ($mitra['is_active'] == '1'): ?>
                                <a href="<?= base_url('master/deactivated/') . $mitra['id_mitra']; ?>"
                                    class="btn btn-warning shadow-sm" onclick="return confirm('Nonaktifkan mitra ini?')">
                                    <i class="fas fa-power-off mr-1"></i> Nonaktifkan
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('master/activated/') . $mitra['id_mitra']; ?>"
                                    class="btn btn-info shadow-sm" onclick="return confirm('Aktifkan kembali mitra ini?')">
                                    <i class="fas fa-power-off mr-1"></i> Aktifkan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->