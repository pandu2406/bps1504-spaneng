<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-6 mt-5">

            <div class="card border-0 shadow-sm my-5" style="background-color: #eaf4ff; border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="text-right mt-3 mr-3">
                        <a href="#" data-toggle="modal" data-target="#askModal">
                            <i class="fas fa-question-circle text-primary" title="Panduan"></i>
                        </a>
                    </div>

                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">

                                <div class="text-center mb-4">
                                    <img src="<?= base_url('assets/img/spaneng.png'); ?>" style="width: 60px;" alt="logo">
                                    <h2 class="text-primary font-weight-bold mt-3">SPANENG</h2>
                                    <p class="text-muted">Sistem Penilaian dan Evaluasi Beban Kerja Mitra Terintegrasi</p>
                                </div>

                                <?= $this->session->flashdata('message'); ?>
                                
                                <form class="user" method="post" action="<?= base_url('auth') ?>">
                                    <div class="form-group">
                                        <label for="email" class="text-primary small font-weight-bold">Email</label>
                                        <input type="text" class="form-control rounded-pill" id="email" name="email" placeholder="Masukkan Email..." value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="text-primary small font-weight-bold">Password</label>
                                        <input type="password" class="form-control rounded-pill" id="password" name="password" placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="role_id" class="text-primary small font-weight-bold">Login Sebagai</label>
                                        <select name="role_id" id="role_id" class="form-control rounded-pill">
                                            <option value="">-- Pilih Role --</option>
                                            <?php foreach ($role as $r) : ?>
                                                <option value="<?= $r['id']; ?>"><?= $r['role']; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <?= form_error('role_id', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block rounded-pill mt-4 shadow-sm">
                                        Login
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Modal Panduan -->
<div class="modal fade" id="askModal" tabindex="-1" role="dialog" aria-labelledby="askModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="askModalLabel">Panduan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <ul>
                    <li>Isi email Anda di kolom pertama.</li>
                    <li>Masukkan password di kolom kedua.</li>
                    <li>Pilih role akses dari dropdown.</li>
                    <li>Password default saat pertama login adalah <strong>"12345678"</strong>.</li>
                    <li>Silakan ubah password setelah login pertama.</li>
                    <li>Jika lupa password, hubungi admin.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary rounded-pill" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
