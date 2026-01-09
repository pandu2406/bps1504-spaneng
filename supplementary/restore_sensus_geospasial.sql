-- =====================================================
-- Script untuk Mengembalikan Data Sensus yang Terhapus
-- Sensus: Pemutakhiran Kerangka Geospasial dan Muatan Wilkerstat SE2026 2025
-- =====================================================

-- Data sensus yang akan dikembalikan
INSERT INTO `kegiatan` (`id`, `nama`, `start`, `finish`, `k_pengawas`, `k_pencacah`, `jenis_kegiatan`, `seksi_id`, `ob`, `posisi`, `satuan`, `honor`) VALUES
(37, 'Pemutakhiran Kerangka Geospasial dan Muatan Wilkerstat SE2026 2025', '1754006400', '1756598400', 23, 116, 2, 3, 1, 3, 1, 3298000.00);

-- Informasi Detail Sensus:
-- ID: 37
-- Nama: Pemutakhiran Kerangka Geospasial dan Muatan Wilkerstat SE2026 2025
-- Start: 1754006400 (31 Desember 2025)
-- Finish: 1756598400 (30 Januari 2026)
-- Kuota Pengawas: 23
-- Kuota Pencacah: 116
-- Jenis Kegiatan: 2 (Sensus)
-- Seksi ID: 3
-- OB: 1
-- Posisi: 3
-- Satuan: 1
-- Honor: Rp 3.298.000,00

-- Catatan:
-- - Pastikan ID 37 belum digunakan oleh kegiatan lain
-- - Jika ID 37 sudah terpakai, hapus baris pertama (yang berisi ID) dan biarkan auto-increment
-- - Setelah restore, sync dengan master_kegiatan jika diperlukan
