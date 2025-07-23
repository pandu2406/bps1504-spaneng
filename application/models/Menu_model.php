<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                  FROM `user_sub_menu` JOIN `user_menu`
                  ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                  ORDER BY `menu_id`
        ";

        return $this->db->query($query)->result_array();
    }

    public function deletemenu($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_menu');
    }

    public function deletesubmenu($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user_sub_menu');
    }

    public function deactivated($id)
    {
        $this->db->set('is_active', 0);
        $this->db->Where('id', $id);
        $this->db->update('user_sub_menu');
    }

    public function activated($id)
    {
        $this->db->set('is_active', 1);
        $this->db->Where('id', $id);
        $this->db->update('user_sub_menu');
    }
}
