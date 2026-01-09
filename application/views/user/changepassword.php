<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-key mr-2"></i> Change Password</h5>
                </div>
                <div class="card-body p-5">
                    <?= $this->session->flashdata('message'); ?>
                    <form action="<?= base_url('user/changepassword'); ?>" method="post">

                        <div class="form-group">
                            <label for="current_password" class="font-weight-bold text-gray-800">Current
                                Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="current_password"
                                    name="current_password" placeholder="Enter current password">
                            </div>
                            <?= form_error('current_password', '<small class="text-danger pl-3 font-weight-bold">', '</small>'); ?>
                        </div>

                        <hr class="my-4">

                        <div class="form-group">
                            <label for="new_password1" class="font-weight-bold text-gray-800">New Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                </div>
                                <input type="password" class="form-control" id="new_password1" name="new_password1"
                                    placeholder="Minimum 8 characters">
                            </div>
                            <?= form_error('new_password1', '<small class="text-danger pl-3 font-weight-bold">', '</small>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="new_password2" class="font-weight-bold text-gray-800">Repeat Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                </div>
                                <input type="password" class="form-control" id="new_password2" name="new_password2"
                                    placeholder="Repeat new password">
                            </div>
                            <?= form_error('new_password2', '<small class="text-danger pl-3 font-weight-bold">', '</small>'); ?>
                        </div>
                        <div class="form-group mt-4 mb-0">
                            <button type="submit" class="btn btn-danger btn-block shadow-sm font-weight-bold py-2">
                                Change Password
                            </button>
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