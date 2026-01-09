# Panduan Sistem Pembagian ID Survei dan Sensus

## Konsep Sistem

Untuk menghindari konflik ID antara kegiatan Survei dan Sensus, sistem menggunakan pembagian range ID:

- **Survei**: ID 1 - 999,999
- **Sensus**: ID 1,000,000 - 1,999,999

## Keuntungan Sistem Ini

1. ✅ **Tidak Ada Konflik ID** - Survei dan Sensus memiliki range ID yang terpisah
2. ✅ **Mudah Identifikasi** - Dari ID saja sudah bisa tahu jenis kegiatan
3. ✅ **Auto-Increment Otomatis** - Trigger database akan handle assignment ID
4. ✅ **Skalabilitas** - Bisa menampung hingga 999,999 survei dan 1 juta sensus

## Cara Implementasi

### Step 1: Backup Data

```bash
# Jalankan backup otomatis (sudah termasuk dalam script)
```

### Step 2: Jalankan Migration Script

```bash
mysql -u root spaneng < id_separation_system.sql
```

**Atau via PHP:**

```bash
php migrate_id_system.php
```

### Step 3: Verifikasi

Setelah migration, cek apakah ID sudah benar:

```sql
SELECT 'Survei' as jenis, MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total
FROM kegiatan WHERE jenis_kegiatan = 1
UNION ALL
SELECT 'Sensus' as jenis, MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total
FROM kegiatan WHERE jenis_kegiatan = 2;
```

**Expected Result:**
- Survei: min_id < 1000000, max_id < 1000000
- Sensus: min_id >= 1000000, max_id >= 1000000

## Contoh Sebelum dan Sesudah

### Sebelum Migration

| ID | Nama | Jenis |
|----|------|-------|
| 2 | Survei A | Survei (1) |
| 37 | Sensus Geospasial | Sensus (2) |
| 82 | Survei B | Survei (1) |

### Sesudah Migration

| ID | Nama | Jenis |
|----|------|-------|
| 2 | Survei A | Survei (1) |
| 82 | Survei B | Survei (1) |
| 1000037 | Sensus Geospasial | Sensus (2) |

## Cara Kerja Trigger

Trigger `before_insert_kegiatan` akan otomatis:

1. **Untuk Survei (jenis_kegiatan = 1)**:
   - Ambil ID tertinggi survei saat ini
   - Assign ID = max_survei_id + 1
   - Maksimal ID: 999,999

2. **Untuk Sensus (jenis_kegiatan = 2)**:
   - Ambil ID tertinggi sensus saat ini
   - Jika belum ada sensus, mulai dari 1,000,000
   - Assign ID = max_sensus_id + 1

## Troubleshooting

### Error: "Survei ID limit reached"

Jika survei sudah mencapai 999,999 entries:
1. Hapus survei lama yang tidak terpakai
2. Atau hubungi developer untuk extend range

### ID Sensus Tidak Dimulai dari 1000000

Jalankan manual update:

```sql
UPDATE kegiatan 
SET id = id + 1000000 
WHERE jenis_kegiatan = 2 AND id < 1000000;
```

### Referensi Tabel Tidak Terupdate

Update manual untuk tabel terkait:

```sql
-- Update pencacah
UPDATE all_kegiatan_pencacah 
SET kegiatan_id = kegiatan_id + 1000000 
WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE jenis_kegiatan = 2);

-- Update pengawas
UPDATE all_kegiatan_pengawas 
SET kegiatan_id = kegiatan_id + 1000000 
WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE jenis_kegiatan = 2);
```

## Catatan Penting

⚠️ **BACKUP DATABASE SEBELUM MIGRATION!**

```bash
mysqldump -u root spaneng > backup_before_id_migration.sql
```

⚠️ **Jangan Jalankan Migration 2x** - Bisa menyebabkan ID sensus menjadi 2000000+

✅ **Setelah Migration** - Semua form tambah survei/sensus akan otomatis menggunakan sistem ID baru

---

**Last Updated**: 2026-01-07
