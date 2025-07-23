<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6" style="color:#00264d;">
            <form action="" method="post">
                <div class="form-group row">
                    <label for="nip" class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nip" name="nip" value="<?= $pegawai['nip']; ?>" readonly>
                        <?= form_error('nip', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $pegawai['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email" value="<?= $pegawai['email']; ?>" readonly>
                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= $pegawai['jabatan']; ?>">
                        <?= form_error('jabatan', '<small class="text-danger pl-3">', '</small>'); ?>
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