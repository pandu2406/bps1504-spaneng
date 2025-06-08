<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6" style="color:#00264d;">
            <form action="" method="post">
                <div class="form-group row">
                    <label for="prioritas" class="col-sm-2 col-form-label">Prioritas</label>
                    <div class="col-sm-10">
                        <label for="" class="col-sm-2 col-form-label"><?= $kriteria['prioritas']; ?></label>
                        <select name="prioritas" id="prioritas" class="form-control">
                            <option value="">Select Prioritas</option>
                            <?php for ($i = 1; $i <= $jumlahkriteria; $i++) : ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <?= form_error('prioritas', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $kriteria['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bobot" class="col-sm-2 col-form-label">Bobot</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="bobot" name="bobot" value="<?= $kriteria['bobot']; ?>">
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

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->