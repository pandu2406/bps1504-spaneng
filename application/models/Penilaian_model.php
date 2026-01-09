<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian_model extends CI_Model
{
    public function simpan_nilai($all_kegiatan_pencacah_id, $kriteria_id, $nilai)
    {
        $data = [
            'all_kegiatan_pencacah_id' => $all_kegiatan_pencacah_id,
            'kriteria_id' => $kriteria_id,
            'nilai' => $nilai
        ];

        $this->db->where('all_kegiatan_pencacah_id', $all_kegiatan_pencacah_id);
        $this->db->where('kriteria_id', $kriteria_id);
        $query = $this->db->get('all_penilaian');

        if ($query->num_rows() > 0) {
            $this->db->where('all_kegiatan_pencacah_id', $all_kegiatan_pencacah_id);
            $this->db->where('kriteria_id', $kriteria_id);
            $this->db->update('all_penilaian', ['nilai' => $nilai]);
        } else {
            $this->db->insert('all_penilaian', $data);
        }
    }

    public function simpan_nilai_pengawas($all_kegiatan_pengawas_id, $kriteria_id, $nilai)
{
    $data = [
        'all_kegiatan_pengawas_id' => $all_kegiatan_pengawas_id,
        'kriteria_id' => $kriteria_id,
        'nilai' => $nilai
    ];

    // Cek apakah data sudah ada
    $this->db->where('all_kegiatan_pengawas_id', $all_kegiatan_pengawas_id);
    $this->db->where('kriteria_id', $kriteria_id);
    $query = $this->db->get('all_penilaian_pengawas');

    if ($query->num_rows() > 0) {
        // Update
        $this->db->where('all_kegiatan_pengawas_id', $all_kegiatan_pengawas_id);
        $this->db->where('kriteria_id', $kriteria_id);
        $this->db->update('all_penilaian_pengawas', ['nilai' => $nilai]);
    } else {
        // Insert
        $this->db->insert('all_penilaian_pengawas', $data);
    }
}

    
        public function getRincianKegiatanMitra($id_mitra)
    {
        $this->db->select('rk.*, k.nama as nama_kegiatan');
        $this->db->from('rinciankegiatan rk');
        $this->db->join('kegiatan k', 'rk.kegiatan_id = k.id');
        $this->db->where('rk.id_mitra', $id_mitra);
        return $this->db->get()->result_array();
    }

}

