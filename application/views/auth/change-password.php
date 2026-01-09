<div class="login-card shadow-2xl">

    <!-- Help Icon -->
    <a href="#" class="help-trigger" data-toggle="modal" data-target="#askModal">
        <i class="fas fa-question-circle"></i>
    </a>

    <div class="card-body p-4 p-md-5">

        <!-- Logo Header -->
        <div class="text-center mb-4">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                style="width: 70px; height: 70px;">
                <i class="fas fa-unlock-alt fa-2x text-primary"></i>
            </div>
            <h3 class="text-white font-weight-bold mb-1">Ubah Password</h3>
            <p class="text-white-50 mb-0 small"><?= $this->session->userdata('reset_email'); ?></p>
        </div>

        <?= $this->session->flashdata('message'); ?>

        <form class="user" method="post" action="<?= base_url('auth/changepassword') ?>">

            <div class="input-group-material">
                <input type="password" id="password1" name="password1" placeholder=" " required>
                <label for="password1">New Password</label>
                <?= form_error('password1', '<div class="text-warning small mt-1 pl-1">', '</div>'); ?>
            </div>

            <div class="input-group-material">
                <input type="password" id="password2" name="password2" placeholder=" " required>
                <label for="password2">Confirm Password</label>
                <?= form_error('password2', '<div class="text-warning small mt-1 pl-1">', '</div>'); ?>
            </div>

            <button type="submit" class="btn btn-auth btn-block btn-lg rounded-pill shadow-sm mt-4">
                Simpan Password
            </button>
        </form>

    </div>
</div>

<div class="modal fade" id="askModal" tabindex="-1" role="dialog" aria-labelledby="askModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-gradient-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold" id="askModalLabel"><i
                        class="fas fa-info-circle mr-2"></i>Panduan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark p-4">
                <ul class="pl-3 mb-0" style="line-height: 1.8;">
                    <li>Masukkan password baru pada kedua kolom yang tersedia.</li>
                    <li>Pastikan kedua password sama persis.</li>
                    <li>Gunakan kombinasi huruf dan angka untuk keamanan lebih baik.</li>
                </ul>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4">
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
                    data-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>