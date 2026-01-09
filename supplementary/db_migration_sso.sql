/*
 * Database Migration: Add SSO Support
 * 
 * CARA MENJALANKAN:
 * 1. Buka phpMyAdmin atau MySQL client
 * 2. Pilih database 'spaneng'
 * 3. Copy-paste script ini dan jalankan
 * 
 * Script ini akan menambahkan kolom untuk tracking SSO login
 */

-- Add SSO tracking columns to user table
ALTER TABLE `user` 
ADD COLUMN `sso_provider` VARCHAR(20) NULL COMMENT 'mitra/pegawai/null' AFTER `date_created`,
ADD COLUMN `sso_id` VARCHAR(255) NULL COMMENT 'ID dari SSO provider' AFTER `sso_provider`,
ADD COLUMN `last_sso_sync` BIGINT NULL COMMENT 'Timestamp last sync from SSO' AFTER `sso_id`;

-- Add indexes for better performance
ALTER TABLE `user`
ADD INDEX `idx_sso_provider` (`sso_provider`),
ADD INDEX `idx_sso_id` (`sso_id`);

-- Verify changes
DESCRIBE `user`;

-- Expected output should show new columns:
-- sso_provider | varchar(20) | YES | MUL | NULL
-- sso_id | varchar(255) | YES | MUL | NULL  
-- last_sso_sync | bigint | YES | | NULL
