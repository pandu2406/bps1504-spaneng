<?php

function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $ci->session->userdata('role_id');
        $menu = $ci->uri->segment(1);

        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}

function is_logged_in_user()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    static $access_cache = [];

    if (!isset($access_cache[$role_id])) {
        $ci->db->where('role_id', $role_id);
        $result = $ci->db->get('user_access_menu')->result_array();
        $access_cache[$role_id] = array_column($result, 'menu_id');
    }

    if (in_array($menu_id, $access_cache[$role_id])) {
        return "checked='checked'";
    }
}

function check_pencacah($kegiatan_id, $id_mitra)
{
    $ci = get_instance();

    static $pencacah_cache = [];

    if (!isset($pencacah_cache[$kegiatan_id])) {
        $ci->db->select('id_mitra');
        $ci->db->where('kegiatan_id', $kegiatan_id);
        $result = $ci->db->get('all_kegiatan_pencacah')->result_array();
        $pencacah_cache[$kegiatan_id] = array_column($result, 'id_mitra');
    }

    if (in_array($id_mitra, $pencacah_cache[$kegiatan_id])) {
        return "checked='checked'";
    }
}

function check_pengawas($kegiatan_id, $id_peg)
{
    $ci = get_instance();

    static $pengawas_cache = [];

    if (!isset($pengawas_cache[$kegiatan_id])) {
        $ci->db->select('id_pengawas');
        $ci->db->where('kegiatan_id', $kegiatan_id);
        $result = $ci->db->get('all_kegiatan_pengawas')->result_array();
        $pengawas_cache[$kegiatan_id] = array_column($result, 'id_pengawas');
    }

    if (in_array($id_peg, $pengawas_cache[$kegiatan_id])) {
        return "checked='checked'";
    }
}


function check_pencacahpengawas($kegiatan_id, $id_pengawas, $id_pencacah, $type = 'mitra')
{
    $ci = get_instance();

    $ci->db->where('kegiatan_id', $kegiatan_id);
    $ci->db->where('id_pengawas', $id_pengawas);

    if ($type == 'pegawai') {
        $ci->db->where('id_peg', $id_pencacah);
    } else {
        $ci->db->where('id_mitra', $id_pencacah);
    }

    $result = $ci->db->get('all_kegiatan_pencacah');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function check_nilai($all_kegiatan_pencacah_id, $kriteria_id, $nilai)
{
    $ci = get_instance();

    static $nilai_cache = [];

    // Create a key for the parent (all_kegiatan_pencacah_id)
    if (!isset($nilai_cache[$all_kegiatan_pencacah_id])) {
        $ci->db->select('kriteria_id, nilai');
        $ci->db->where('all_kegiatan_pencacah_id', $all_kegiatan_pencacah_id);
        $result = $ci->db->get('all_penilaian')->result_array();

        $nilai_cache[$all_kegiatan_pencacah_id] = [];
        foreach ($result as $row) {
            // Group by kriteria_id -> nilai
            $nilai_cache[$all_kegiatan_pencacah_id][$row['kriteria_id']] = $row['nilai'];
        }
    }

    if (
        isset($nilai_cache[$all_kegiatan_pencacah_id][$kriteria_id]) &&
        $nilai_cache[$all_kegiatan_pencacah_id][$kriteria_id] == $nilai
    ) {
        return "checked='checked'";
    }
}

function get_nilai($all_kegiatan_pencacah_id, $kriteria_id)
{
    $CI =& get_instance();

    static $get_nilai_cache = [];

    if (!isset($get_nilai_cache[$all_kegiatan_pencacah_id])) {
        $CI->db->select('kriteria_id, nilai');
        $CI->db->where('all_kegiatan_pencacah_id', $all_kegiatan_pencacah_id);
        $result = $CI->db->get('all_penilaian')->result_array();

        $get_nilai_cache[$all_kegiatan_pencacah_id] = [];
        foreach ($result as $row) {
            $get_nilai_cache[$all_kegiatan_pencacah_id][$row['kriteria_id']] = $row['nilai'];
        }
    }

    return isset($get_nilai_cache[$all_kegiatan_pencacah_id][$kriteria_id]) ?
        $get_nilai_cache[$all_kegiatan_pencacah_id][$kriteria_id] : '';
}

function get_nilai_pengawas($all_kegiatan_pengawas_id, $kriteria_id)
{
    $CI =& get_instance();

    static $get_nilai_pengawas_cache = [];

    if (!isset($get_nilai_pengawas_cache[$all_kegiatan_pengawas_id])) {
        $CI->db->select('kriteria_id, nilai');
        $CI->db->where('all_kegiatan_pengawas_id', $all_kegiatan_pengawas_id);
        $result = $CI->db->get('all_penilaian_pengawas')->result_array();

        $get_nilai_pengawas_cache[$all_kegiatan_pengawas_id] = [];
        foreach ($result as $row) {
            $get_nilai_pengawas_cache[$all_kegiatan_pengawas_id][$row['kriteria_id']] = $row['nilai'];
        }
    }

    return isset($get_nilai_pengawas_cache[$all_kegiatan_pengawas_id][$kriteria_id]) ?
        $get_nilai_pengawas_cache[$all_kegiatan_pengawas_id][$kriteria_id] : '';
}

// function check_nilai($kegiatan_id, $id_mitra, $kriteria_id, $nilai)
// {
//     $ci = get_instance();

//     $ci->db->where('kegiatan_id', $kegiatan_id);
//     $ci->db->where('id_mitra', $id_mitra);
//     $ci->db->where('kriteria_id', $kriteria_id);
//     $ci->db->where('nilai', $nilai);
//     $result = $ci->db->get('all_penilaian');

//     if ($result->num_rows() > 0) {
//         return "checked='checked'";
//     }
// }
