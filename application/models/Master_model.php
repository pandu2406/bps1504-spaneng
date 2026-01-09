<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public function deletemitra($id_mitra)
    {
        $this->db->where('id_mitra', $id_mitra);
        $this->db->delete('mitra');
    }

    public function deletemitrafromuser($email)
    {
        $this->db->where('email', $email);
        $this->db->delete('user');
    }

    public function deactivated($id_mitra, $tahun = 2025)
    {
        // Update mitra_tahun
        $this->db->set('is_active', 0);
        $this->db->where('id_mitra', $id_mitra);
        $this->db->where('tahun', $tahun);
        $this->db->update('mitra_tahun');

        // Sync to mitra_old (legacy support)
        // Check if exists in mitra_old first to avoid errors? Or just try update
        $this->db->set('is_active', 0);
        $this->db->where('id_mitra', $id_mitra);
        $this->db->update('mitra_old');
    }

    public function activated($id_mitra, $tahun = 2025)
    {
        // Update mitra_tahun
        $this->db->set('is_active', 1);
        $this->db->where('id_mitra', $id_mitra);
        $this->db->where('tahun', $tahun);
        $this->db->update('mitra_tahun');

        // Sync to mitra_old
        $this->db->set('is_active', 1);
        $this->db->where('id_mitra', $id_mitra);
        $this->db->update('mitra_old');
    }

    public function deletepegawai($id_peg)
    {
        $this->db->where('id_peg', $id_peg);
        $this->db->delete('pegawai');
    }

    public function deletepegawaifromuser($email)
    {
        $this->db->where('email', $email);
        $this->db->delete('user');
    }

    public function insert_batch($data, $tahun = 2025)
    {
        $count = 0;
        $errors = [];

        foreach ($data as $m) {
            try {
                // Normalize and Trim Inputs
                $nik = trim($m['nik'] ?? '');
                if (empty($nik)) {
                    continue;
                }

                $nama = trim($m['nama'] ?? '');
                $email = trim($m['email'] ?? '');
                $kecamatan = trim($m['kecamatan'] ?? '');
                $desa = trim($m['desa'] ?? '');
                $alamat = trim($m['alamat'] ?? '');
                $no_hp = trim($m['no_hp'] ?? '');
                $sobat_id = trim($m['sobat_id'] ?? '');

                // Normalize Gender (JK)
                // Excel might have 1, 2, L, P, Laki-laki, Perempuan
                $raw_jk = strtoupper(trim($m['jk'] ?? ''));
                $jk = 0; // Default unknown

                if ($raw_jk == '1' || $raw_jk == 'L' || $raw_jk == 'LAKI-LAKI') {
                    $jk = 1;
                } elseif ($raw_jk == '2' || $raw_jk == 'P' || $raw_jk == 'PEREMPUAN') {
                    $jk = 2;
                }
                // If 0, it will likely show as Female/Unknown depending on View logic, but 1 is strictly Male now.

                // Step 1: Check if mitra exists by NIK in BASE TABLE (mitra_old)
                // We use mitra_old because 'mitra' is a VIEW and we want direct table access for reliability
                $existing_mitra = $this->db->get_where('mitra_old', ['nik' => $nik])->row_array();

                if ($existing_mitra) {
                    // Mitra exists - UPDATE biodata in mitra_old
                    $id_mitra = $existing_mitra['id_mitra'];

                    $profile = [
                        'nama' => $nama ?: $existing_mitra['nama'],
                        'email' => $email ?: $existing_mitra['email'],
                        'kecamatan' => $kecamatan ?: $existing_mitra['kecamatan'],
                        'desa' => $desa ?: $existing_mitra['desa'],
                        'alamat' => $alamat ?: $existing_mitra['alamat'],
                        'jk' => ($jk != 0) ? $jk : $existing_mitra['jk'], // Only update if valid new gender
                        'no_hp' => $no_hp ?: $existing_mitra['no_hp'],
                        'sobat_id' => $sobat_id ?: $existing_mitra['sobat_id']
                    ];

                    $this->db->where('id_mitra', $id_mitra);
                    $this->db->update('mitra_old', $profile);

                } else {
                    // Mitra doesn't exist - INSERT new mitra into mitra_old
                    $profile = [
                        'nik' => $nik,
                        'nama' => $nama,
                        'email' => $email,
                        'kecamatan' => $kecamatan,
                        'desa' => $desa,
                        'alamat' => $alamat,
                        'jk' => $jk,
                        'no_hp' => $no_hp,
                        'sobat_id' => $sobat_id
                    ];

                    $this->db->insert('mitra_old', $profile);
                    $id_mitra = $this->db->insert_id();
                }

                // Step 2: Register mitra for specific year in mitra_tahun
                // Check if already registered for this year
                $existing_year = $this->db->get_where('mitra_tahun', [
                    'id_mitra' => $id_mitra,
                    'tahun' => $tahun
                ])->row_array();

                $year_data = [
                    'posisi' => trim($m['posisi'] ?? 'Mitra Pendataan'),
                    'is_active' => 1
                ];

                if ($existing_year) {
                    // Update existing year registration
                    $this->db->where('id_mitra', $id_mitra);
                    $this->db->where('tahun', $tahun);
                    $this->db->update('mitra_tahun', $year_data);
                } else {
                    // Insert new year registration
                    $year_data['id_mitra'] = $id_mitra;
                    $year_data['tahun'] = $tahun;
                    $this->db->insert('mitra_tahun', $year_data);
                }

                $count++;

            } catch (\Throwable $e) {
                $errors[] = 'NIK ' . ($m['nik'] ?? 'unknown') . ': ' . $e->getMessage();
                log_message('error', 'Import Error Row ' . $count . ': ' . $e->getMessage());
            }
        }

        // Log errors if any
        if (!empty($errors)) {
            log_message('error', 'Mitra import errors: ' . implode('; ', $errors));
        }

        return $count;
    }

    public function check_email($email)
    {
        $this->db->where('email', $email);
        $data = $this->db->get('mitra');

        return $data->num_rows();
    }

    public function insert_batch_pegawai($data)
    {
        $this->db->insert_batch('pegawai', $data);

        return $this->db->affected_rows();
    }

    public function check_email_pegawai($email)
    {
        $this->db->where('email', $email);
        $data = $this->db->get('pegawai');

        return $data->num_rows();
    }
}
