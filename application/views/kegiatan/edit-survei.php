<!-- Page Styles (Dark Mode Support) -->
<style>
    :root {
        --page-bg: #f8f9fc;
        --card-bg: #ffffff;
        --text-main: #5a5c69;
        --text-heading: #00264d;
        --input-bg: #ffffff;
        --input-border: #d1d3e2;
        --input-text: #6e707e;
    }

    /* Dark Mode Override */
    @media (prefers-color-scheme: dark) {
        :root {
            --page-bg: #121212;
            --card-bg: #1e1e2d;
            --text-main: #d1d3e2;
            --text-heading: #ffffff;
            --input-bg: #2b2b40;
            --input-border: #444460;
            --input-text: #e3e6f0;
        }

        body {
            background-color: var(--page-bg) !important;
            color: var(--text-main) !important;
        }

        .dark-mode-text {
            color: var(--text-main) !important;
        }
    }

    .card-modern {
        background-color: var(--card-bg);
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
    }

    .form-control-modern {
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--input-text);
        border-radius: 10px;
        padding: 12px 15px;
        height: auto;
        transition: all 0.2s;
    }

    .form-control-modern:focus {
        background-color: var(--input-bg);
        color: var(--input-text);
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .section-title {
        color: var(--text-heading);
        font-weight: 700;
        border-left: 4px solid #4e73df;
        padding-left: 10px;
        margin-bottom: 20px;
    }

    label {
        color: var(--text-main);
        font-weight: 600;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-modern mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    style="background: linear-gradient(45deg, #00264d, #004080); border-radius: 15px 15px 0 0;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-edit mr-2"></i> Edit Data Kegiatan
                    </h6>
                    <a href="<?= base_url('kegiatan') ?>" class="btn btn-sm btn-light text-primary font-weight-bold">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="<?= base_url('kegiatan/editsurvei/' . $survei['id']) ?>" method="post">

                        <!-- Section: Informasi Utama -->
                        <h5 class="section-title">Informasi Umum</h5>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama"><i class="fas fa-heading mr-2 text-primary"></i>Nama
                                        Kegiatan</label>
                                    <input type="text" class="form-control form-control-modern" id="nama" name="nama"
                                        value="<?= $survei['nama']; ?>" placeholder="Masukkan nama kegiatan...">
                                    <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="periodisitas"><i
                                            class="fas fa-clock mr-2 text-info"></i>Periodisitas</label>
                                    <select name="periodisitas" id="periodisitas"
                                        class="form-control form-control-modern" required>
                                        <option value="">-- Pilih Periodisitas --</option>
                                        <?php
                                        $options = ['Mingguan', 'Bulanan', 'Triwulanan', 'Subround (4 Bulanan)', 'Semesteran', 'Tahunan'];
                                        foreach ($options as $opt):
                                            ?>
                                            <option value="<?= $opt ?>" <?= ($survei['periodisitas'] == $opt) ? 'selected' : ''; ?>>
                                                <?= $opt ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('periodisitas', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="posisi"><i class="fas fa-tags mr-2 text-success"></i>Jenis
                                        Kegiatan</label>
                                    <select name="posisi" class="form-control form-control-modern" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <?php foreach ($posisi as $p): ?>
                                            <option value="<?= $p['id']; ?>" <?= ($survei['posisi'] == $p['id']) ? 'selected' : ''; ?>>
                                                <?= $p['posisi']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('posisi', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Waktu Pelaksanaan -->
                        <h5 class="section-title mt-4">Waktu Pelaksanaan</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start"><i class="fas fa-calendar-alt mr-2 text-warning"></i>Tanggal
                                        Mulai</label>
                                    <input type="text" class="form-control form-control-modern datepicker" id="start"
                                        name="start" value="<?= date('d F Y', $survei['start']); ?>">
                                    <?= form_error('start', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="finish"><i class="fas fa-flag-checkered mr-2 text-danger"></i>Tanggal
                                        Selesai</label>
                                    <input type="text" class="form-control form-control-modern datepicker" id="finish"
                                        name="finish" value="<?= date('d F Y', $survei['finish']); ?>">
                                    <?= form_error('finish', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Alokasi & Tanggung Jawab -->
                        <h5 class="section-title mt-4">Alokasi & Tanggung Jawab</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="k_pengawas"><i class="fas fa-user-tie mr-2 text-secondary"></i>Kuota
                                        Pengawas</label>
                                    <input type="number" class="form-control form-control-modern" id="k_pengawas"
                                        name="k_pengawas" value="<?= $survei['k_pengawas']; ?>">
                                    <?= form_error('k_pengawas', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="k_pencacah"><i class="fas fa-users mr-2 text-secondary"></i>Kuota
                                        Pencacah</label>
                                    <input type="number" class="form-control form-control-modern" id="k_pencacah"
                                        name="k_pencacah" value="<?= $survei['k_pencacah']; ?>">
                                    <?= form_error('k_pencacah', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="seksi_id"><i class="fas fa-building mr-2 text-secondary"></i>Penanggung
                                        Jawab</label>
                                    <select name="seksi_id" id="seksi_id" class="form-control form-control-modern">
                                        <option value="">-- Pilih Tim --</option>
                                        <?php foreach ($daftar_seksi as $seksi): ?>
                                            <option value="<?= $seksi['id']; ?>" <?= ($survei['seksi_id'] == $seksi['id']) ? 'selected' : ''; ?>>
                                                <?= $seksi['nama']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('seksi_id', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Teknis & Honor -->
                        <h5 class="section-title mt-4">Detail Teknis & Honorarium</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="satuan"><i class="fas fa-box mr-2 text-purple"></i>Satuan Target</label>
                                    <select name="satuan" class="form-control form-control-modern" required>
                                        <option value="">-- Pilih Satuan --</option>
                                        <?php foreach ($satuan as $s): ?>
                                            <option value="<?= $s['id']; ?>" <?= ($survei['satuan'] == $s['id']) ? 'selected' : ''; ?>>
                                                <?= $s['satuan']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('satuan', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="beban_standar"><i
                                            class="fas fa-weight-hanging mr-2 text-purple"></i>Volume / Beban
                                        Standar</label>
                                    <input type="number" step="1" class="form-control form-control-modern"
                                        name="beban_standar"
                                        value="<?= isset($survei['beban_standar']) ? $survei['beban_standar'] : 1; ?>">
                                    <?= form_error('beban_standar', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ob"><i class="fas fa-wifi mr-2 text-info"></i>Tunjangan
                                        Pulsa/Data</label>
                                    <select name="ob" id="ob" class="form-control form-control-modern" required>
                                        <option value="">-- Pilih Opsi --</option>
                                        <?php foreach ($sistempembayaran_list as $ob): ?>
                                            <option value="<?= $ob['kode']; ?>" <?= ($survei['ob'] == $ob['kode']) ? 'selected' : ''; ?>>
                                                <?= $ob['nama']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('ob', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="honor"><i class="fas fa-money-bill-wave mr-2 text-success"></i>Honor per
                                        Satuan (Rp)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0 font-weight-bold">Rp</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control form-control-modern"
                                            name="honor" value="<?= $survei['honor']; ?>" placeholder="0">
                                    </div>
                                    <small class="text-muted mt-1">*Input nominal saja tanpa titik/koma</small>
                                    <?= form_error('honor', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-5">
                            <div class="col-md-12 text-right">
                                <button type="reset" class="btn btn-secondary px-4 py-2 mr-2 rounded-pill">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow">
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