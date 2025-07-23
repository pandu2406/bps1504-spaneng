<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-10" style="color:#00264d;">
            <form action="" method="post">
                <div class="form-group row">
                    <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nik" name="nik" value="<?= $mitra['nik']; ?>">
                        <?= form_error('nik', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $mitra['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="posisi" class="col-sm-2 col-form-label">Posisi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="posisi" name="posisi" value="<?= $mitra['posisi']; ?>">
                        <?= form_error('posisi', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email" value="<?= $mitra['email']; ?>">
                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kecamatan" class="col-sm-2 col-form-label">Kecamatan</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="kecamatan" name="kecamatan">
                            <option value="">-- Pilih Kecamatan --</option>
                            <?php foreach ($kode_kecamatan as $kc) : ?>
                                <option value="<?= $kc['kode']; ?>" <?= ($kc['kode'] == $mitra['kecamatan']) ? 'selected' : ''; ?>>
                                    <?= $kc['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('kecamatan', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="desa" class="col-sm-2 col-form-label">Desa</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="desa" name="desa">
                        <option value="<?= $mitra['desa']; ?>" selected><?= $nama_desa; ?></option>
                            <?php foreach ($kode_keldes as $kd) : ?>
                                <?php $kode_desa = substr($kd['kode'], 3, 3); ?>
                                <option value="<?= $kode_desa; ?>" <?= ($kode_desa == $mitra['desa']) ? 'selected' : ''; ?>>
                                    <?= $kd['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('desa', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <label for="desa" class="col-sm-2 col-form-label">Desa</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="desa" name="desa" value="<?= $mitra['desa']; ?>">
                        <?= form_error('desa', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div> -->
                <div class="form-group row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $mitra['alamat']; ?>">
                        <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jk" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jk" name="jk" value="<?= $mitra['jk']; ?>">
                        <?= form_error('jk', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_hp" class="col-sm-2 col-form-label">No. HP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $mitra['no_hp']; ?>">
                        <?= form_error('no_hp', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sobat_id" class="col-sm-2 col-form-label">Sobat ID</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="sobat_id" name="sobat_id" value="<?= $mitra['sobat_id']; ?>">
                        <?= form_error('sobat_id', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

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
</div>
<!-- End of Main Content -->