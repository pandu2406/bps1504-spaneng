<div class="login-card shadow-2xl">
    <div class="card-body p-5">

        <!-- Error Header -->
        <div class="text-center mb-4">
            <div class="error mx-auto mb-3" data-text="403"
                style="color: var(--text-muted); font-size: 5rem; font-weight: 800; text-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
                403</div>
            <h3 class="text-main-theme font-weight-bold mb-2">Access Forbidden</h3>
            <p class="text-muted-theme mb-4">Sepertinya Anda tersesat di dimensi lain...</p>
        </div>

        <div class="text-center">
            <a href="<?= base_url('user'); ?>" class="btn btn-auth btn-block btn-lg rounded-pill shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>