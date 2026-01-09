# Quick Setup Guide - SSO Client Secret

## Cara Cepat Setup Client Secret

### Option 1: Menggunakan Setup Script (Recommended)

1. Buka terminal/command prompt
2. Navigate ke folder project:
   ```bash
   cd e:/Ngoding/spaneng
   ```

3. Jalankan setup script:
   ```bash
   php setup_sso_secret.php
   ```

4. Masukkan Client Secret saat diminta
5. Konfirmasi dengan ketik `y`
6. Done! ✅

### Option 2: Manual Edit

1. Buka file `application/config/sso.php`
2. Cari baris:
   ```php
   'client_secret' => 'YOUR_CLIENT_SECRET_HERE',
   ```

3. Ganti `YOUR_CLIENT_SECRET_HERE` dengan Client Secret yang sebenarnya:
   ```php
   'client_secret' => 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
   ```

4. Save file

---

## Cara Mendapatkan Client Secret

### Dari Admin SSO BPS

1. Hubungi administrator SSO BPS
2. Request Client Secret untuk aplikasi:
   - **Client ID**: `03340-mitra-gnm`
   - **Aplikasi**: SPANENG (Sistem Penilaian dan Evaluasi Beban Kerja Mitra)

### Dari SSO Admin Panel (jika punya akses)

1. Login ke: `https://sso.bps.go.id/admin`
2. Navigate ke: **Clients** → **03340-mitra-gnm**
3. Tab **Credentials**
4. Copy **Client Secret**

---

## Testing SSO

Setelah setup Client Secret:

1. Buka browser: `http://localhost:8000/auth`
2. Klik tombol **"Login dengan SSO Mitra"**
3. Akan redirect ke halaman login SSO BPS
4. Login dengan credential Mitra
5. Setelah berhasil, akan kembali ke aplikasi dan auto-login

---

## Troubleshooting

### Error: "Invalid client_secret"
- Pastikan Client Secret sudah benar
- Tidak ada spasi atau karakter tambahan
- Hubungi admin SSO untuk konfirmasi

### Error: "Redirect URI mismatch"
- Check redirect URI di config: `http://localhost:8000/auth/sso_callback/mitra`
- Untuk production, update ke domain yang sebenarnya
- Pastikan redirect URI sudah didaftarkan di SSO provider

### SSO button tidak berfungsi
- Check apakah `sso.php` config sudah ter-load
- Check log file di `application/logs/`
- Enable debug mode di config

---

## Production Checklist

Sebelum deploy ke production:

- [ ] Update `client_secret` dengan secret production
- [ ] Update `redirect_uri` ke domain production
- [ ] Daftarkan redirect URI production ke SSO provider
- [ ] Enable HTTPS (WAJIB!)
- [ ] Disable debug mode
- [ ] Test SSO login flow
- [ ] Backup config file

---

**Last Updated**: 2026-01-07
