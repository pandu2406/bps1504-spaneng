<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends CI_Model
{
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_role');
    }
}
