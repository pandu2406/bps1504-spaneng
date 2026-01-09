<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function deactivated($id)
    {
        $this->db->set('is_active', 0);
        $this->db->Where('id', $id);
        $this->db->update('user');
    }

    public function activated($id)
    {
        $this->db->set('is_active', 1);
        $this->db->Where('id', $id);
        $this->db->update('user');
    }
    public function delete($id)
    {
        $this->db->Where('id', $id);
        $this->db->delete('user');
    }
}
