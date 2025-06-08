<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ranking_model extends CI_Model
{

    public function deletekriteria($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('kriteria');
    }
    public function data_awal($kegiatan_id)
    {

        $query = "SELECT all_kegiatan_pencacah.kegiatan_id as kegiatan_id, all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id";

        $temp = $this->db->query($query)->result();
        // var_dump($temp);
        // die;

        $result['data'] = array();
        foreach ($temp as $data) {
            $data->nilai = $this->nilai($data->kegiatan_id, $data->id_mitra, $data->kriteria_id);
            $result['data'][] = $data;
        }
        return $result;
    }

    public function nilai($kegiatan_id, $id_mitra, $kriteria_id)
    {
        $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";
        $result = $this->db->query($query)->result();

        // var_dump($result);
        // die;
        return $result;
    }

    public function normalized($kegiatan_id)
{
    // Ambil data id_mitra dan kriteria_id
    $query = "SELECT 
                all_kegiatan_pencacah.kegiatan_id AS kegiatan_id, 
                all_kegiatan_pencacah.id_mitra AS id_mitra, 
                all_penilaian.kriteria_id AS kriteria_id,
                SUM(all_penilaian.nilai) AS nilai
              FROM all_penilaian 
              JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id 
              WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id 
              GROUP BY id_mitra, kriteria_id 
              ORDER BY id_mitra, kriteria_id";

    $temp = $this->db->query($query)->result();

    $result['data'] = array();
    foreach ($temp as $data) {
        $data->bobot = $this->bobot_sk($data->kegiatan_id, $data->id_mitra, $data->kriteria_id);
        $data->nilai_asli = $data->nilai;
        $data->normalized = $data->nilai / 100;

        $result['data'][] = $data;
    }

    return $result;
}


    public function bobot_sk($kegiatan_id, $id_mitra, $kriteria_id)
    {
        $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai, subkriteria.bobot as bobotsk FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";
        $result = $this->db->query($query)->result();

        // var_dump($result);
        // die;
        return $result;
    }

    public function utility($kegiatan_id)
    {
        // Ambil semua kriteria dan bobotnya
        $kriteria = $this->db->query("SELECT id, bobot FROM kriteria")->result();
        $bobot_kriteria = [];
        foreach ($kriteria as $k) {
            $bobot_kriteria[$k->id] = $k->bobot;
        }

        // Ambil nilai normalized dari fungsi normalized()
        $normalized = $this->normalized($kegiatan_id);
        $data_normalized = $normalized['data'];

        // Siapkan array rekap per mitra
        $rekap = [];

        foreach ($data_normalized as $row) {
            $id_mitra = $row->id_mitra;
            $kriteria_id = $row->kriteria_id;
            $normalized_value = $row->normalized;

            $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
            $utility_value = $normalized_value * $bobot;

            // Buat struktur per mitra
            if (!isset($rekap[$id_mitra])) {
                $rekap[$id_mitra] = new stdClass();
                $rekap[$id_mitra]->id_mitra = $id_mitra;
                $rekap[$id_mitra]->bobot = [];
            }

            $cell = new stdClass();
            $cell->kriteria_id = $kriteria_id;
            $cell->id_mitra = $id_mitra;
            $cell->ut = $utility_value;

            $rekap[$id_mitra]->bobot[] = $cell;
        }

        return ['data' => array_values($rekap)];
    }


    public function bobot($kegiatan_id, $id_mitra, $kriteria_id, $nilai)
    {
        $queryminbobot = "SELECT min(subkriteria.bobot) as minbobot FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_penilaian.kriteria_id = $kriteria_id";
        // $ut = $this->db->query($queryminbobot)->result();
        // return $ut;

        $querymaxbobot = "SELECT max(subkriteria.bobot) as maxbobot FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_penilaian.kriteria_id = $kriteria_id";

        $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai, subkriteria.bobot as bobotsk, ((subkriteria.bobot - ($queryminbobot))/(($querymaxbobot) - ($queryminbobot))) as ut FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id  WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";

        // bener
        // $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai, kriteria.bobot*subkriteria.bobot as bobot FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";

        // $query = "SELECT all_penilaian.id_mitra, all_penilaian.kriteria_id, all_penilaian.nilai, kriteria.bobot*subkriteria.bobot as bobot FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai WHERE all_penilaian.kegiatan_id = $kegiatan_id AND all_penilaian.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY all_penilaian.id_mitra, all_penilaian.kriteria_id ORDER BY all_penilaian.id_mitra, all_penilaian.kriteria_id ";
        $result = $this->db->query($query)->result();
        return $result;
    }

    public function total($kegiatan_id)
{
    // Ambil semua kriteria dan bobotnya
    $kriteria = $this->db->query("SELECT id, bobot FROM kriteria")->result();
    $bobot_kriteria = [];
    foreach ($kriteria as $k) {
        $bobot_kriteria[$k->id] = $k->bobot;
    }

    // Ambil nilai normalized dari fungsi normalized()
    $normalized = $this->normalized($kegiatan_id);
    $data_normalized = $normalized['data'];

    // Ambil nama mitra berdasarkan kegiatan
    $query_mitra = $this->db->query("
        SELECT akp.id_mitra, m.nama 
        FROM all_kegiatan_pencacah akp
        JOIN mitra m ON akp.id_mitra = m.id_mitra
        WHERE akp.kegiatan_id = $kegiatan_id
    ")->result();

    // Buat array asosiatif untuk akses cepat nama mitra
    $nama_mitra = [];
    foreach ($query_mitra as $row) {
        $nama_mitra[$row->id_mitra] = $row->nama;
    }

    // Siapkan array rekap per mitra
    $rekap = [];

    foreach ($data_normalized as $row) {
        $id_mitra = $row->id_mitra;
        $kriteria_id = $row->kriteria_id;
        $normalized_value = $row->normalized;

        $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
        $utility_value = $normalized_value * $bobot * 100;

        // Buat struktur per mitra
        if (!isset($rekap[$id_mitra])) {
            $rekap[$id_mitra] = new stdClass();
            $rekap[$id_mitra]->id_mitra = $id_mitra;
            $rekap[$id_mitra]->nama = $nama_mitra[$id_mitra] ?? 'Unknown'; // 🟢 FIX
            $rekap[$id_mitra]->bobot = [];
        }

        $cell = new stdClass();
        $cell->kriteria_id = $kriteria_id;
        $cell->id_mitra = $id_mitra;
        $cell->ut = $utility_value;

        $rekap[$id_mitra]->bobot[] = $cell;
    }

    return ['data' => array_values($rekap)];
}

public function totalakhir($kegiatan_id)
{
    // Ambil semua kriteria dan bobotnya
    $kriteria = $this->db->query("SELECT id, bobot FROM kriteria")->result();
    $bobot_kriteria = [];
    foreach ($kriteria as $k) {
        $bobot_kriteria[$k->id] = $k->bobot;
    }

    // Ambil nilai normalized dari fungsi normalized()
    $normalized = $this->normalized($kegiatan_id);
    $data_normalized = $normalized['data'];

    // Ambil nama mitra berdasarkan kegiatan
    $query_mitra = $this->db->query("
        SELECT akp.id_mitra, m.nama 
        FROM all_kegiatan_pencacah akp
        JOIN mitra m ON akp.id_mitra = m.id_mitra
        WHERE akp.kegiatan_id = $kegiatan_id
    ")->result();

    $nama_mitra = [];
    foreach ($query_mitra as $row) {
        $nama_mitra[$row->id_mitra] = $row->nama;
    }

    // Rekap nilai total
    $rekap = [];

    foreach ($data_normalized as $row) {
        $id_mitra = $row->id_mitra;
        $kriteria_id = $row->kriteria_id;
        $normalized_value = $row->normalized;

        $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
        $utility_value = $normalized_value * $bobot * 100;

        if (!isset($rekap[$id_mitra])) {
            $rekap[$id_mitra] = new stdClass();
            $rekap[$id_mitra]->id_mitra = $id_mitra;
            $rekap[$id_mitra]->nama = $nama_mitra[$id_mitra] ?? 'Unknown';
            $rekap[$id_mitra]->total = 0;
        }

        $rekap[$id_mitra]->total += $utility_value;
    }

    // Ubah ke array dan urutkan descending berdasarkan total
    $result = array_values($rekap);
    usort($result, function($a, $b) {
        return $b->total <=> $a->total;
    });

    return $result;
}



    // public function total_utility($kegiatan_id)
    // {
    //     $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, SUM(kriteria.bobot*subkriteria.bobot) as bobot
    //     FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id
    //     WHERE all_penilaian.kegiatan_id = $kegiatan_id
    //     GROUP BY all_penilaian.id_mitra
    //     ORDER BY all_penilaian.id_mitra";

    //     // $query = "SELECT all_penilaian.id_mitra as id_mitra, SUM(kriteria.bobot*subkriteria.bobot) as bobot
    //     // FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai
    //     // WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id
    //     // GROUP BY id_mitra
    //     // ORDER BY id_mitra";

    //     $result = $this->db->query($query)->result();
    //     return $result;
    // }
    public function ranking($kegiatan_id)
    {


        $query = "SELECT all_kegiatan_pencacah.kegiatan_id as kegiatan_id, all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id";

        // $query = "SELECT kegiatan_id, id_mitra, kriteria_id, nilai FROM all_penilaian WHERE kegiatan_id = $kegiatan_id GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id";

        $temp = $this->db->query($query)->result();
        $result['data'] = array();
        foreach ($temp as $data) {
            $data->bobot = $this->akhir($data->kegiatan_id, $data->id_mitra, $data->kriteria_id, $data->nilai);
            $result['data'][] = $data;
        }
        return $result;
    }

    public function akhir($kegiatan_id, $id_mitra, $kriteria_id, $nilai)
    {
        $queryminbobot = "SELECT min(subkriteria.bobot) as minbobot FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_penilaian.kriteria_id = $kriteria_id";
        // $ut = $this->db->query($queryminbobot)->result();
        // return $ut;

        $querymaxbobot = "SELECT max(subkriteria.bobot) as maxbobot FROM all_penilaian JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id JOIN subkriteria ON all_penilaian.nilai = subkriteria.nilai WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_penilaian.kriteria_id = $kriteria_id";

        $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai, subkriteria.bobot as bobotsk, kriteria.bobot*((subkriteria.bobot - ($queryminbobot))/(($querymaxbobot) - ($queryminbobot))) as ut FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id  WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";

        // bener
        // $query = "SELECT all_kegiatan_pencacah.id_mitra as id_mitra, all_penilaian.kriteria_id as kriteria_id, all_penilaian.nilai as nilai, kriteria.bobot*subkriteria.bobot as bobot FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai JOIN all_kegiatan_pencacah ON all_penilaian.all_kegiatan_pencacah_id = all_kegiatan_pencacah.id WHERE all_kegiatan_pencacah.kegiatan_id = $kegiatan_id AND all_kegiatan_pencacah.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY id_mitra, kriteria_id ORDER BY id_mitra, kriteria_id ";

        // $query = "SELECT all_penilaian.id_mitra, all_penilaian.kriteria_id, all_penilaian.nilai, kriteria.bobot*subkriteria.bobot as bobot FROM all_penilaian JOIN kriteria ON all_penilaian.kriteria_id = kriteria.id JOIN subkriteria  ON all_penilaian.nilai = subkriteria.nilai WHERE all_penilaian.kegiatan_id = $kegiatan_id AND all_penilaian.id_mitra = $id_mitra AND all_penilaian.kriteria_id = $kriteria_id AND all_penilaian.nilai = $nilai GROUP BY all_penilaian.id_mitra, all_penilaian.kriteria_id ORDER BY all_penilaian.id_mitra, all_penilaian.kriteria_id ";
        $result = $this->db->query($query)->result();
        return $result;
    }
}
