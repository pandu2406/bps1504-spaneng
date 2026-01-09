-- =====================================================
-- Sistem Pembagian ID untuk Survei dan Sensus
-- =====================================================
-- 
-- KONSEP:
-- - Survei: ID 1 - 999,999
-- - Sensus: ID 1,000,000 - 1,999,999
--
-- Ini memastikan tidak ada konflik ID antara survei dan sensus
-- =====================================================

-- Step 1: Update ID sensus yang ada ke range 1,000,000+
-- Backup dulu data sensus yang ada
CREATE TABLE IF NOT EXISTS kegiatan_sensus_backup AS 
SELECT * FROM kegiatan WHERE jenis_kegiatan = 2;

-- Update ID sensus yang sudah ada (ID 37 menjadi 1000037)
UPDATE kegiatan 
SET id = id + 1000000 
WHERE jenis_kegiatan = 2 AND id < 1000000;

-- Step 2: Update referensi di tabel terkait
-- Update all_kegiatan_pencacah
UPDATE all_kegiatan_pencacah akp
INNER JOIN kegiatan_sensus_backup ksb ON akp.kegiatan_id = ksb.id
SET akp.kegiatan_id = ksb.id + 1000000
WHERE ksb.jenis_kegiatan = 2;

-- Update all_kegiatan_pengawas
UPDATE all_kegiatan_pengawas akpw
INNER JOIN kegiatan_sensus_backup ksb ON akpw.kegiatan_id = ksb.id
SET akpw.kegiatan_id = ksb.id + 1000000
WHERE ksb.jenis_kegiatan = 2;

-- Update rinciankegiatan
UPDATE rinciankegiatan rk
INNER JOIN kegiatan_sensus_backup ksb ON rk.kegiatan_id = ksb.id
SET rk.kegiatan_id = ksb.id + 1000000
WHERE ksb.jenis_kegiatan = 2;

-- Step 3: Set AUTO_INCREMENT untuk survei dan sensus
-- Untuk survei, mulai dari ID tertinggi + 1
-- Untuk sensus, akan dihandle di aplikasi dengan trigger

-- Step 4: Buat trigger untuk auto-assign ID sensus
DELIMITER $$

DROP TRIGGER IF EXISTS before_insert_kegiatan$$

CREATE TRIGGER before_insert_kegiatan
BEFORE INSERT ON kegiatan
FOR EACH ROW
BEGIN
    IF NEW.jenis_kegiatan = 2 THEN
        -- Sensus: ambil ID tertinggi sensus + 1
        SET @max_sensus_id = (SELECT COALESCE(MAX(id), 999999) FROM kegiatan WHERE jenis_kegiatan = 2);
        IF @max_sensus_id < 1000000 THEN
            SET NEW.id = 1000000;
        ELSE
            SET NEW.id = @max_sensus_id + 1;
        END IF;
    ELSE
        -- Survei: ambil ID tertinggi survei + 1 (max 999999)
        SET @max_survei_id = (SELECT COALESCE(MAX(id), 0) FROM kegiatan WHERE jenis_kegiatan = 1);
        IF @max_survei_id >= 999999 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Survei ID limit reached (max 999999)';
        ELSE
            SET NEW.id = @max_survei_id + 1;
        END IF;
    END IF;
END$$

DELIMITER ;

-- Verifikasi
SELECT 'Survei' as jenis, MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total
FROM kegiatan WHERE jenis_kegiatan = 1
UNION ALL
SELECT 'Sensus' as jenis, MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as total
FROM kegiatan WHERE jenis_kegiatan = 2;
