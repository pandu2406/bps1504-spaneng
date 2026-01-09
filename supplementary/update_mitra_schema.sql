-- Add 'tahun' column to mitra table
-- Default to 2025 for existing records
ALTER TABLE mitra ADD COLUMN tahun INT(4) NOT NULL DEFAULT 2025 AFTER sobat_id;

-- Ensure we can have multiple entries for the same NIK/SOBAT ID if needed in different years?
-- For now, just adding the column is safe.
