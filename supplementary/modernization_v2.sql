-- PHASE 1: MITRA NORMALIZATION

-- 1. Create the annual status table
CREATE TABLE IF NOT EXISTS `mitra_tahun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mitra` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `posisi` varchar(32) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_mitra_id` (`id_mitra`),
  KEY `idx_tahun` (`tahun`),
  CONSTRAINT `fk_mitra_tahun_mitra` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Migrate data from 'mitra' to 'mitra_tahun'
INSERT INTO `mitra_tahun` (id_mitra, tahun, posisi, is_active)
SELECT id_mitra, tahun, posisi, is_active FROM `mitra`;

-- 3. Cleanup 'mitra' table (remove redundant columns)
-- We will do this later in the code to ensure we don't break things immediately.
-- For now, let's just make NIK and Email UNIQUE to enforce profile integrity.
ALTER TABLE `mitra` ADD UNIQUE INDEX `idx_nik_unique` (`nik`);
ALTER TABLE `mitra` ADD UNIQUE INDEX `idx_email_unique` (`email`);


-- PHASE 2: AUDIT LOGGING

CREATE TABLE IF NOT EXISTS `system_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(20) NOT NULL, -- INSERT, UPDATE, DELETE
  `table_name` varchar(64) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_values` text, -- JSON representation
  `new_values` text, -- JSON representation
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_table_record` (`table_name`, `record_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PHASE 3: DYNAMIC GRADING SNAPSHOTS

CREATE TABLE IF NOT EXISTS `kegiatan_kriteria_snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kegiatan_id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `subkriteria_id` int(11) DEFAULT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_kegiatan_id` (`kegiatan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
