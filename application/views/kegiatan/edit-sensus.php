<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>

    <div class="row">
        <div class="col-lg-6" style="color:#00264d;">
            <form action="" method="post">
                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $sensus['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="start" class="col-sm-3 col-form-label">Start</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control datepicker" id="start" name="start" value="<?= date('d F Y', $sensus['start']); ?>">
                        <?= form_error('start', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="finish" class="col-sm-3 col-form-label">Finish</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control datepicker" id="finish" name="finish" value="<?= date('d F Y', $sensus['finish']); ?>">
                        <?= form_error('finish', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="k_pengawas" class="col-sm-3 col-form-label">Kuota Pengawas</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="k_pengawas" name="k_pengawas" value="<?= $sensus['k_pengawas']; ?>">
                        <?= form_error('k_pengawas', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="k_pencacah" class="col-sm-3 col-form-label">Kuota Pencacah</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="k_pencacah" name="k_pencacah" value="<?= $sensus['k_pencacah']; ?>">
                        <?= form_error('k_pencacah', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="seksi_id" class="col-sm-3 col-form-label">Penanggung Jawab</label>
                    <div class="col-sm-8">
                        <select name="seksi_id" id="seksi_id" class="form-control">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            <?php foreach ($daftar_seksi as $seksi) : ?>
                                <option value="<?= $seksi['id']; ?>" <?= ($sensus['seksi_id'] == $seksi['id']) ? 'selected' : ''; ?>>
                                <?= $seksi['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('seksi_id', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ob" class="col-sm-3 col-form-label">Satuan Honor</label>
                    <div class="col-sm-8">
                        <select name="ob" id="ob" class="form-control">
                            <?php if ($sensus['ob'] == 1) : ?>
                                <option value="1">Orang Bulan (OB)</option>
                                <option value="0">Selain OB</option>
                            <?php elseif ($sensus['ob'] == 0) : ?>
                                <option value="0">Selain OB</option>
                                <option value="1">Orang Bulan (OB)</option>
                            <?php endif; ?>
                        </select>
                        <?= form_error('ob', '<small class="text-danger pl-3">', '</small>'); ?>
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