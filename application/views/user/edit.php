<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row" style="color:#00264d">
        <div class="col-lg-6">
            <?= form_open_multipart('user/edit'); ?>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="email" name="email" value="<?= $user['email']; ?>" readonly>
                    <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
            </div>
            <!-- <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Full name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $user['nama']; ?>">
                    <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
            </div> -->
            <div class="form-group row">
                <div class="col-sm-2">Picture</div>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" width="100" height="100">
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
            <div class="form-group row justify-content-end">
                <div class="sm-10">
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