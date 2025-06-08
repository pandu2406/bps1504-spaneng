<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model
{
    // Fungsi ambil rekap kerja mitra (pencacah & pengawas), dengan filter bulan & tahun
    public function get_rekap_mitra($bulan = 0, $tahun = 0)
{
    $this->db->select('mitra.id_mitra, mitra.nama');
    $this->db->select('COUNT(DISTINCT CASE WHEN all_kegiatan_pengawas.kegiatan_id IS NOT NULL THEN all_kegiatan_pengawas.kegiatan_id END) AS jk', false);
    $this->db->select('COUNT(DISTINCT CASE WHEN all_kegiatan_pencacah.kegiatan_id IS NOT NULL THEN all_kegiatan_pencacah.kegiatan_id END) AS jk_p', false);
    $this->db->from('mitra');

    // Join dengan kegiatan pengawas dengan kondisi bulan dan tahun
    $this->db->join('all_kegiatan_pengawas', 'mitra.id_mitra = all_kegiatan_pengawas.id_pengawas', 'left');
    $this->db->join('kegiatan as k1', 'all_kegiatan_pengawas.kegiatan_id = k1.id', 'left');

    // Join dengan kegiatan pencacah dengan kondisi bulan dan tahun
    $this->db->join('all_kegiatan_pencacah', 'mitra.id_mitra = all_kegiatan_pencacah.id_mitra', 'left');
    $this->db->join('kegiatan as k2', 'all_kegiatan_pencacah.kegiatan_id = k2.id', 'left');

    // Filter bulan dan tahun untuk pengawas dan pencacah
    if ($bulan > 0) {
        $this->db->where("(MONTH(FROM_UNIXTIME(k1.finish)) = $bulan OR k1.finish IS NULL)");
        $this->db->where("(MONTH(FROM_UNIXTIME(k2.finish)) = $bulan OR k2.finish IS NULL)");
    }
    $this->db->where("(YEAR(FROM_UNIXTIME(k1.finish)) = $tahun OR k1.finish IS NULL)");
    $this->db->where("(YEAR(FROM_UNIXTIME(k2.finish)) = $tahun OR k2.finish IS NULL)");

    $this->db->group_by('mitra.id_mitra');
    $this->db->order_by('mitra.id_mitra', 'ASC');

    $query = $this->db->get();

    return $query->result_array();
}


}
