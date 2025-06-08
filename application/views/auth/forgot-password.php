<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-6 mt-5">

            <div class="card o-hidden border-0 shadow-lg my-5" style="background-color: #b3b3b3;">
                <div class="card-body p-0">
                    <div class="text-right mr-2 mt-2">
                        <a href="" data-toggle="modal" data-target="#askModal"><i class="fas fa-question-circle" title="Panduan"></i></a>
                    </div>
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Forgot your password?</h1>
                                </div>

                                <?= $this->session->flashdata('message'); ?>

                                <hr>

                                <form class="user" method="post" action="<?= base_url('auth/forgotpassword') ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth'); ?>">Back to login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="askModal" tabindex="-1" role="dialog" aria-labelledby="askModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="color: black;">
            <div class="modal-header">
                <h5 class="modal-title" id="askModalLabel" style="color: black;">Panduan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    - Untuk menggunakan fitur "forgot password", perlu untuk menginput email terlebih dahulu.<br>
                    - Selanjutnya silahkan cek email untuk mengubah password.

                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>

        </div>
    </div>
</div>