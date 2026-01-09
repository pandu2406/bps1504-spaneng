<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>

    <div class="row">
        <div class="col-lg-6" style="color:#00264d;">
            <form action="" method="post">
                <!-- Nama Kegiatan -->
                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $sensus['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Periodisitas -->
                <div class="form-group row">
                    <label for="periodisitas" class="col-sm-3 col-form-label">Periodisitas</label>
                    <div class="col-sm-8">
                        <select name="periodisitas" id="periodisitas" class="form-control" required>
                            <option value="">-- Pilih Periodisitas --</option>
                            <option value="Mingguan" <?= ($sensus['periodisitas'] == 'Mingguan') ? 'selected' : ''; ?>>
                                Mingguan</option>
                            <option value="Bulanan" <?= ($sensus['periodisitas'] == 'Bulanan') ? 'selected' : ''; ?>>
                                Bulanan</option>
                            <option value="Triwulanan" <?= ($sensus['periodisitas'] == 'Triwulanan') ? 'selected' : ''; ?>>
                                Triwulanan</option>
                            <option value="Subround (4 Bulanan)" <?= ($sensus['periodisitas'] == 'Subround (4 Bulanan)') ? 'selected' : ''; ?>>Subround (4 Bulanan)</option>
                            <option value="Semesteran" <?= ($sensus['periodisitas'] == 'Semesteran') ? 'selected' : ''; ?>>
                                Semesteran</option>
                            <option value="Tahunan" <?= ($sensus['periodisitas'] == 'Tahunan') ? 'selected' : ''; ?>>
                                Tahunan</option>
                        </select>
                        <?= form_error('periodisitas', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Start -->
                <div class="form-group row">
                    <label for="start" class="col-sm-3 col-form-label">Start</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control datepicker" id="start" name="start"
                            value="<?= date('d F Y', $sensus['start']); ?>">
                        <?= form_error('start', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Finish -->
                <div class="form-group row">
                    <label for="finish" class="col-sm-3 col-form-label">Finish</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control datepicker" id="finish" name="finish"
                            value="<?= date('d F Y', $sensus['finish']); ?>">
                        <?= form_error('finish', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Kuota Pengawas -->
                <div class="form-group row">
                    <label for="k_pengawas" class="col-sm-3 col-form-label">Kuota Pengawas</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="k_pengawas" name="k_pengawas"
                            value="<?= $sensus['k_pengawas']; ?>">
                        <?= form_error('k_pengawas', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Kuota Pencacah -->
                <div class="form-group row">
                    <label for="k_pencacah" class="col-sm-3 col-form-label">Kuota Pencacah</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="k_pencacah" name="k_pencacah"
                            value="<?= $sensus['k_pencacah']; ?>">
                        <?= form_error('k_pencacah', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Penanggung Jawab -->
                <div class="form-group row">
                    <label for="seksi_id" class="col-sm-3 col-form-label">Penanggung Jawab</label>
                    <div class="col-sm-8">
                        <select name="seksi_id" id="seksi_id" class="form-control">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            <?php foreach ($daftar_seksi as $seksi): ?>
                                <option value="<?= $seksi['id']; ?>" <?= ($sensus['seksi_id'] == $seksi['id']) ? 'selected' : ''; ?>>
                                    <?= $seksi['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('seksi_id', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Jenis Kegiatan -->
                <div class="form-group row">
                    <label for="posisi" class="col-sm-3 col-form-label">Jenis Kegiatan</label>
                    <div class="col-sm-8">
                        <select name="posisi" class="form-control" required>
                            <option value="">-- Pilih Jenis Kegiatan --</option>
                            <?php foreach ($posisi as $p): ?>
                                <option value="<?= $p['id']; ?>" <?= ($sensus['posisi'] == $p['id']) ? 'selected' : ''; ?>>
                                    <?= $p['posisi']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('posisi', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Satuan -->
                <div class="form-group row">
                    <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
                    <div class="col-sm-8">
                        <select name="satuan" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            <?php foreach ($satuan as $s): ?>
                                <option value="<?= $s['id']; ?>" <?= ($sensus['satuan'] == $s['id']) ? 'selected' : ''; ?>>
                                    <?= $s['satuan']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('satuan', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Honor -->
                <div class="form-group row">
                    <label for="honor" class="col-sm-3 col-form-label">Honor (Rp)</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.01" class="form-control" name="honor"
                            value="<?= $sensus['honor']; ?>" placeholder="Honor per Satuan">
                        <?= form_error('honor', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- OB / Sistem Pembayaran -->
                <div class="form-group row">
                    <label for="ob" class="col-sm-3 col-form-label">Terdapat Pulsa/Paket Data</label>
                    <div class="col-sm-8">
                        <select name="ob" id="ob" class="form-control" required>
                            <option value="">-- Terdapat/ Tidak --</option>
                            <?php foreach ($sistempembayaran_list as $ob): ?>
                                <option value="<?= $ob['kode']; ?>" <?= ($sensus['ob'] == $ob['kode']) ? 'selected' : ''; ?>>
                                    <?= $ob['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('ob', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <br>
</div>
<!-- /.container-fluid -->