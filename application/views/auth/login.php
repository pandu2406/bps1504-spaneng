<!-- Theme Toggle (Top Left) -->
<div class="theme-switch shadow-sm" id="themeToggle" title="Ganti Tema">
    <i class="fas fa-moon"></i>
</div>

<div class="login-card shadow-2xl">

    <!-- Help Icon -->
    <a href="#" class="help-trigger" data-toggle="modal" data-target="#askModal"
        style="position: absolute; top: 20px; right: 20px; color: var(--text-muted); font-size: 1.2rem; z-index:10;">
        <i class="fas fa-question-circle"></i>
    </a>

    <div class="card-body p-4 p-md-5">

        <!-- Logo Header -->
        <div class="text-center mb-4">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                style="width: 80px; height: 80px;">
                <img src="<?= base_url('assets/img/spaneng.png'); ?>" alt="logo" style="width: 55px;">
            </div>
            <h3 class="text-main-theme font-weight-bold mb-1">SPANENG</h3>
            <p class="text-muted-theme mb-0 small">Sistem Penilaian & Evaluasi Beban Kerja</p>
        </div>

        <?= $this->session->flashdata('message'); ?>

        <?= form_open('auth', ['class' => 'user', 'method' => 'post']) ?>

        <!-- CSRF TOKEN -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
            value="<?= $this->security->get_csrf_hash(); ?>">

        <!-- Email Input -->
        <div class="input-group-material">
            <input type="text" id="email" name="email" placeholder=" " value="<?= html_escape(set_value('email')); ?>"
                autocomplete="username" required>
            <label for="email">Email Address</label>
            <?= form_error('email', '<small class="text-warning pl-2 font-weight-bold">', '</small>'); ?>
        </div>

        <!-- Password Input -->
        <div class="input-group-material">
            <input type="password" id="password" name="password" placeholder=" " autocomplete="current-password"
                required>
            <label for="password">Password</label>
            <i class="fas fa-eye pass-toggle" id="togglePassword"></i>
            <?= form_error('password', '<small class="text-warning pl-2 font-weight-bold">', '</small>'); ?>
        </div>

        <button type="submit" class="btn btn-auth btn-block btn-lg rounded-pill shadow-sm mt-4">
            LOGIN
        </button>

        <?= form_close(); ?>

        <div class="text-center my-4">
            <span class="text-muted-theme small">atau masuk dengan</span>
        </div>

        <div class="row">
            <div class="col-6 pr-2">
                <a href="<?= base_url('auth/sso_mitra'); ?>"
                    class="btn btn-light btn-block rounded-pill sso-btn shadow-sm py-2">
                    <i class="fas fa-users text-primary mr-1"></i> <small
                        class="font-weight-bold text-primary">Mitra</small>
                </a>
            </div>
            <div class="col-6 pl-2">
                <a href="<?= base_url('auth/sso_pegawai'); ?>"
                    class="btn btn-light btn-block rounded-pill sso-btn shadow-sm py-2">
                    <i class="fas fa-id-card text-success mr-1"></i> <small
                        class="font-weight-bold text-success">Pegawai</small>
                </a>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="text-brand small text-decoration-none font-weight-bold"
                href="<?= base_url('auth/forgotpassword'); ?>">Lupa Password?</a>
        </div>

    </div>
</div>

<!-- Scripts for Interaction -->
<script>
    // 1. Theme Toggle Logic
    const toggleBtn = document.getElementById('themeToggle');
    const icon = toggleBtn.querySelector('i');

    // Check saved theme or default to dark (since original was dark glass)
    const savedTheme = localStorage.getItem('auth_theme');
    if (savedTheme === 'light') {
        document.body.setAttribute('data-theme', 'light');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }

    toggleBtn.addEventListener('click', () => {
        const isLight = document.body.getAttribute('data-theme') === 'light';

        if (isLight) {
            document.body.removeAttribute('data-theme');
            localStorage.setItem('auth_theme', 'dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            document.body.setAttribute('data-theme', 'light');
            localStorage.setItem('auth_theme', 'light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    });

    // 2. Password Visibility Toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            // toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    }

    // 3. Float Label Fix for Autofill
    // This script checks all inputs for values periodically and on events to ensure labels float
    const inputs = document.querySelectorAll('.input-group-material input');

    function checkInputs() {
        inputs.forEach(input => {
            if (input.value.length > 0 || input.matches(':-webkit-autofill')) {
                input.classList.add('has-val');
            } else {
                input.classList.remove('has-val');
            }
        });
    }

    // Run on load, input, and periodically for slow browsers filling
    window.addEventListener('load', checkInputs);
    inputs.forEach(input => {
        input.addEventListener('input', checkInputs);
        input.addEventListener('blur', checkInputs);
        input.addEventListener('animationstart', (e) => {
            if (e.animationName === 'onAutoFillStart') {
                checkInputs();
            }
        });
    });

    // Fallback polling for stubborn autofillers (Chrome/LastPass)
    setInterval(checkInputs, 500);
</script>

<!-- Custom Modal Style Override for Theme Compatibility -->
<style>
    .modal-content {
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        border: var(--card-border);
        color: var(--text-main);
    }

    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.05);
    }

    .close {
        color: var(--text-main);
        text-shadow: none;
    }
</style>

<!-- Modal Panduan -->
<div class="modal fade" id="askModal" tabindex="-1" role="dialog" aria-labelledby="askModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-gradient-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold" id="askModalLabel"><i class="fas fa-book-open mr-2"></i>Panduan
                    Masuk</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="color: var(--text-main);">
                <div class="alert alert-primary border-0 shadow-sm mb-4" style="background-color: rgba(78, 115, 223, 0.1); color: var(--text-main); border-left: 4px solid #4e73df;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-lg mr-3 text-primary"></i>
                        <div>
                            <strong>Akun Baru?</strong><br>
                            <span class="small">Silakan hubungi admin untuk aktivasi akun baru.</span>
                        </div>
                    </div>
                </div>
                <h6 class="font-weight-bold mb-3"><i class="fas fa-check-circle text-success mr-2"></i>Langkah Masuk:</h6>
                <ul class="pl-4 mb-0" style="line-height: 1.8; font-size: 0.95rem;">
                    <li class="mb-2">Masukkan <strong>Email</strong> yang terdaftar pada sistem.</li>
                    <li class="mb-2">Password default untuk login pertama: <code class="bg-light px-2 py-1 rounded text-danger font-weight-bold">12345678</code></li>
                    <li class="mb-2">Demi keamanan, <span class="text-warning font-weight-bold">wajib ganti password</span> setelah berhasil login.</li>
                    <li>Jika mengalami kendala, silakan hubungi tim IPDS.</li>
                </ul>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4">
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
                    data-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>