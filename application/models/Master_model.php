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

    public function deactivated($id_mitra)
    {
        $this->db->set('is_active', 0);
        $this->db->Where('id_mitra', $id_mitra);
        $this->db->update('mitra');
    }

    public function activated($id_mitra)
    {
        $this->db->set('is_active', 1);
        $this->db->Where('id_mitra', $id_mitra);
        $this->db->update('mitra');
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

    public function insert_batch($data)
    {
        $this->db->insert_batch('mitra', $data);

        return $this->db->affected_rows();
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
