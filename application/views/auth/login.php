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
                                    <img src="<?= base_url('assets/img/spaneng.png'); ?>" style="width:20%;" alt="logo">
                                    <br>
                                    <br>
                                    <h1 style="color:black; font-weight: bold;">SPANENG</h1>
                                    <p style="color:black;">Sistem Penilaian dan Evaluasi Beban Kerja Mitra Terintegrasi</p>
                                </div>

                                <?= $this->session->flashdata('message'); ?>

                                <hr>

                                <form class="user" method="post" action="<?= base_url('auth') ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <select name="role_id" id="role_id" class="form-control-sm">
                                            <option value="">Login sebagai :</option>
                                            <?php foreach ($role as $r) : ?>

                                                <option value="<?= $r['id']; ?>"><?= $r['role']; ?></option>

                                            <?php endforeach ?>
                                        </select>
                                        <?= form_error('role_id', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <hr>
                                <!-- <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/forgotpassword') ?>">Forgot Password?</a>
                                </div> -->
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
                    - Text area yang pertama untuk input email.<br>
                    - Text area yang kedua untuk input password.<br>
                    - Pilih role akses dengan klik "login sebagai :"<br>
                    - Jika pertama kali login, password default yaitu "12345678", tanpa petik.<br>
                    - Silahkan mengganti password setelah berhasil login untuk pertama kalinya.<br>
                    - Jika lupa password, silahkan hubungi admin.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>

        </div>
    </div>
</div>