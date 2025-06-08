<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_model extends CI_Model
{
    public function deletesurvei($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kegiatan');
    }

    public function deletesurvei_all_kegiatan_pencacah($id)
    {
        $this->db->where('kegiatan_id', $id);
        $this->db->delete('all_kegiatan_pencacah');
    }

    public function deletesurvei_all_kegiatan_pengawas($id)
    {
        $this->db->where('kegiatan_id', $id);
        $this->db->delete('all_kegiatan_pengawas');
    }

    public function deletesensus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kegiatan');
    }

    public function deletesensus_all_kegiatan_pencacah($id)
    {
        $this->db->where('kegiatan_id', $id);
        $this->db->delete('all_kegiatan_pencacah');
    }

    public function deletesensus_all_kegiatan_pengawas($id)
    {
        $this->db->where('kegiatan_id', $id);
        $this->db->delete('all_kegiatan_pengawas');
    }

    public function check_email($email)
    {
        $this->db->where('email', $email);
        $data = $this->db->get('user');

        return $data->num_rows();
    }
}
