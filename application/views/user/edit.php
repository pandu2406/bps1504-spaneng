<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Edit Profile</h5>
                </div>
                <div class="card-body p-5">
                    <?= form_open_multipart('user/edit'); ?>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label font-weight-bold">Email</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="<?= $user['email']; ?>" readonly style="background-color: #f8f9fa;">
                            </div>
                            <small class="text-muted">Email cannot be changed.</small>
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <div class="col-sm-3 font-weight-bold">Profile Picture</div>
                        <div class="col-sm-9">
                            <div class="row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>"
                                        class="img-thumbnail rounded-circle shadow-sm"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="image">Choose file...</label>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Max size 2MB. Allowed formats: JPG,
                                        PNG.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row justify-content-end mt-4">
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                            <a href="<?= base_url('user'); ?>" class="btn btn-outline-secondary px-4 ml-2">Cancel</a>
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
</div>
<!-- End of Main Content -->