<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">

        <?= form_open_multipart('user/editprofilemitra'); ?>
        <div class="row" style=" color:#00264d;">
            <div class=" col-lg-6">
                <div class="form-group row">
                    <div class="col-sm-3">Picture</div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail">
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_mitra" class="col-sm-3 col-form-label">NIK</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nik" name="nik" value="<?= $mitra['nik']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" name="email" value="<?= $user['email']; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $mitra['nama']; ?>">
                        <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>


            </div>

            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $mitra['alamat']; ?>">
                        <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_hp" class="col-sm-3 col-form-label">No. HP</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $mitra['no_hp']; ?>">
                        <?= form_error('no_hp', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <br>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->