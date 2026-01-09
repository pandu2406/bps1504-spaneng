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
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                    style="width: 70px; height: 70px; background-color: var(--input-focus-bg);">
                    <i class="fas fa-key fa-2x text-primary"></i>
                </div>
                <h3 class="text-main-theme font-weight-bold mb-1">Lupa Password?</h3>
                <p class="text-muted-theme mb-0 small">Kami akan mengirimkan link reset ke email Anda.</p>
            </div>

            <?= $this->session->flashdata('message'); ?>

            <form class="user" method="post" action="<?= base_url('auth/forgotpassword') ?>">

                <div class="input-group-material">
                    <input type="text" id="email" name="email" placeholder=" " value="<?= set_value('email'); ?>" required>
                    <label for="email">Email Address</label>
                    <?= form_error('email', '<div class="text-warning small mt-1 pl-1">', '</div>'); ?>
                </div>

                <button type="submit" class="btn btn-auth btn-block btn-lg rounded-pill shadow-sm mt-4">
                    Reset Password
                </button>
            </form>

            <div class="text-center mt-4">
                <a class="small text-brand font-weight-bold" href="<?= base_url('auth'); ?>">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>

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
                <div class="modal-body p-4" style="color: var(--text-main);">
                    <ul class="pl-3 mb-0" style="line-height: 1.8;">
                        <li>Masukkan email yang terdaftar untuk mereset password.</li>
                        <li>Sistem akan mengirimkan link reset password ke email tersebut.</li>
                        <li>Cek folder Inbox atau Spam pada email Anda.</li>
                    </ul>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
                        data-dismiss="modal">Mengerti</button>
                </div>
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

        // 2. Float Label Fix for Autofill
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

        // Fallback polling for stubborn autofillers
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
            /* background: rgba(0, 0, 0, 0.05); */
        }

        .close {
            color: var(--text-main);
            text-shadow: none;
        }
    </style>