# Cara Hard Refresh Browser untuk Memuat SweetAlert Baru

## Masalah
Browser masih menampilkan `confirm()` lama padahal file sudah diupdate dengan SweetAlert.

## Solusi: Hard Refresh

### Windows / Linux
- **Chrome / Edge**: `Ctrl + Shift + R` atau `Ctrl + F5`
- **Firefox**: `Ctrl + Shift + R` atau `Ctrl + F5`

### Mac
- **Chrome / Edge / Firefox**: `Cmd + Shift + R`

### Alternatif: Clear Cache Manual
1. Buka DevTools (`F12`)
2. Klik kanan pada tombol Refresh
3. Pilih "Empty Cache and Hard Reload"

## Verifikasi SweetAlert Sudah Benar

Setelah hard refresh, cek di DevTools Console:

```javascript
// Ketik di Console:
typeof confirmDeleteSurvei
// Harus return: "function"

typeof confirmDeleteSensus  
// Harus return: "function"
```

## Test Delete Button

1. Klik tombol "Hapus" pada survei/sensus
2. **Yang BENAR**: Popup SweetAlert muncul dengan styling modern (icon warning, 2 tombol berwarna)
3. **Yang SALAH**: Popup browser default (hitam putih, sederhana)

Jika masih muncul popup default, lakukan hard refresh lagi!

---

**File Updated:**
- `application/views/kegiatan/survei.php` ✅
- `application/views/kegiatan/sensus.php` ✅
