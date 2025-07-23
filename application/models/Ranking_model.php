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
    $result['data'] = [];

    // --- Data Penilaian Pencacah ---
    $query_pencacah = "
        SELECT 
            akp.kegiatan_id AS kegiatan_id, 
            akp.id_mitra AS id_mitra, 
            ap.kriteria_id AS kriteria_id, 
            ap.nilai AS nilai,
            'mitra' AS peran
        FROM all_penilaian ap
        JOIN all_kegiatan_pencacah akp ON ap.all_kegiatan_pencacah_id = akp.id
        WHERE akp.kegiatan_id = ?
        ORDER BY akp.id_mitra, ap.kriteria_id
    ";

    $data_pencacah = $this->db->query($query_pencacah, [$kegiatan_id])->result();

    if (!empty($data_pencacah)) {
        foreach ($data_pencacah as $data) {
            $result['data'][] = $data;
        }
    }

    // --- Data Penilaian Pengawas ---
    $query_pengawas = "
    SELECT 
    akp.kegiatan_id AS kegiatan_id, 
    akp.id_pengawas AS id_mitra, 
    app.kriteria_id AS kriteria_id,
    app.nilai AS nilai,
    'pengawas' AS peran
FROM all_penilaian_pengawas app
JOIN all_kegiatan_pencacah akp ON app.all_kegiatan_pengawas_id = akp.id
WHERE akp.kegiatan_id = 1
ORDER BY akp.id_pengawas, app.kriteria_id;
    ";

    $data_pengawas = $this->db->query($query_pengawas, [$kegiatan_id])->result();

    if (!empty($data_pengawas)) {
        foreach ($data_pengawas as $data) {
            $result['data'][] = $data;
        }
    }

    return $result;
}


public function normalized($kegiatan_id)
{
    $result['data'] = [];

    // --- Normalisasi data Pencacah ---
    $query_pencacah = "SELECT 
                akp.kegiatan_id AS kegiatan_id, 
                akp.id_mitra AS id_mitra, 
                ap.kriteria_id AS kriteria_id,
                SUM(ap.nilai) AS nilai
            FROM all_penilaian ap
            JOIN all_kegiatan_pencacah akp ON ap.all_kegiatan_pencacah_id = akp.id 
            WHERE akp.kegiatan_id = ? 
            GROUP BY id_mitra, kriteria_id 
            ORDER BY id_mitra, kriteria_id";

    $data_pencacah = $this->db->query($query_pencacah, [$kegiatan_id])->result();

    foreach ($data_pencacah as $data) {
        $data->bobot = $this->bobot_sk($data->kegiatan_id, $data->id_mitra, $data->kriteria_id);
        $data->nilai_asli = $data->nilai;
        $data->normalized = $data->nilai / 100;
        $data->peran = 'mitra';
        $result['data'][] = $data;
    }

    // --- Normalisasi data Pengawas ---
    $query_pengawas = "SELECT 
                akp.kegiatan_id AS kegiatan_id, 
                akp.id_pengawas AS id_mitra, 
                app.kriteria_id AS kriteria_id,
                SUM(app.nilai) AS nilai
            FROM all_penilaian_pengawas app
            JOIN all_kegiatan_pencacah akp ON app.all_kegiatan_pengawas_id = akp.id 
            WHERE akp.kegiatan_id = ? 
            GROUP BY id_mitra, kriteria_id 
            ORDER BY id_mitra, kriteria_id";

    $data_pengawas = $this->db->query($query_pengawas, [$kegiatan_id])->result();

    foreach ($data_pengawas as $data) {
        $data->bobot = $this->bobot_sk($data->kegiatan_id, $data->id_mitra, $data->kriteria_id);
        $data->nilai_asli = $data->nilai;
        $data->normalized = $data->nilai / 100;
        $data->peran = 'pengawas';
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

    // Ambil nilai normalized (pencacah + pengawas)
    $normalized = $this->normalized($kegiatan_id); // pastikan sudah mendukung peran
    $data_normalized = $normalized['data'];

    // Siapkan array rekap per mitra
    $rekap = [];

    foreach ($data_normalized as $row) {
        $id_mitra = $row->id_mitra;
        $peran = $row->peran;
        $kriteria_id = $row->kriteria_id;
        $normalized_value = $row->normalized;

        $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
        $utility_value = $normalized_value * $bobot;

        // Buat struktur per mitra
        $key = $id_mitra . '_' . $peran;
        if (!isset($rekap[$key])) {
            $rekap[$key] = new stdClass();
            $rekap[$key]->id_mitra = $id_mitra;
            $rekap[$key]->peran = $peran;
            $rekap[$key]->bobot = [];
        }

        $cell = new stdClass();
        $cell->kriteria_id = $kriteria_id;
        $cell->id_mitra = $id_mitra;
        $cell->peran = $peran;
        $cell->ut = $utility_value;

        $rekap[$key]->bobot[] = $cell;
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

    // Ambil nilai normalized (harus sudah gabungan pencacah dan pengawas)
    $normalized = $this->normalized($kegiatan_id);
    $data_normalized = $normalized['data'];

    // Ambil nama mitra pencacah
    $query_pencacah = $this->db->query("
        SELECT akp.id_mitra, m.nama
        FROM all_kegiatan_pencacah akp
        JOIN mitra m ON akp.id_mitra = m.id_mitra
        WHERE akp.kegiatan_id = $kegiatan_id
    ")->result();

    // Ambil nama mitra pengawas
    $query_pengawas = $this->db->query("
        SELECT akg.id_pengawas AS id_mitra, m.nama
        FROM all_kegiatan_pengawas akg
        JOIN mitra m ON akg.id_pengawas = m.id_mitra
        WHERE akg.kegiatan_id = $kegiatan_id
    ")->result();

    // Gabungkan semua nama mitra dan pengawas
    $nama_mitra = [];
    foreach (array_merge($query_pencacah, $query_pengawas) as $row) {
        $nama_mitra[$row->id_mitra] = $row->nama;
    }

    // Siapkan array rekap per mitra (pencacah/pengawas)
    $rekap = [];

    foreach ($data_normalized as $row) {
        $id_mitra = $row->id_mitra;
        $kriteria_id = $row->kriteria_id;
        $normalized_value = $row->normalized;
        $peran = $row->peran ?? 'mitra'; // pastikan peran ada di data normalized

        $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
        $utility_value = $normalized_value * $bobot * 100;

        $key = $id_mitra . '_' . $peran;

        // Buat struktur rekap
        if (!isset($rekap[$key])) {
            $rekap[$key] = new stdClass();
            $rekap[$key]->id_mitra = $id_mitra;
            $rekap[$key]->nama = $nama_mitra[$id_mitra] ?? 'Unknown';
            $rekap[$key]->peran = $peran;
            $rekap[$key]->bobot = [];
        }

        $cell = new stdClass();
        $cell->kriteria_id = $kriteria_id;
        $cell->id_mitra = $id_mitra;
        $cell->ut = $utility_value;

        $rekap[$key]->bobot[] = $cell;
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

    // Ambil nama mitra pencacah
    $query_pencacah = $this->db->query("
        SELECT akp.id_mitra, m.nama
        FROM all_kegiatan_pencacah akp
        JOIN mitra m ON akp.id_mitra = m.id_mitra
        WHERE akp.kegiatan_id = $kegiatan_id
    ")->result();

    // Ambil nama mitra pengawas
    $query_pengawas = $this->db->query("
        SELECT akg.id_pengawas AS id_mitra, m.nama
        FROM all_kegiatan_pengawas akg
        JOIN mitra m ON akg.id_pengawas = m.id_mitra
        WHERE akg.kegiatan_id = $kegiatan_id
    ")->result();

    // Gabungkan semua nama mitra dan pengawas
    $nama_mitra = [];
    foreach (array_merge($query_pencacah, $query_pengawas) as $row) {
        $nama_mitra[$row->id_mitra] = $row->nama;
    }

    // Rekap nilai total
    $rekap = [];

    foreach ($data_normalized as $row) {
        $id_mitra = $row->id_mitra;
        $kriteria_id = $row->kriteria_id;
        $normalized_value = $row->normalized;
        $peran = $row->peran ?? 'mitra'; // ← pastikan ada key ini di fungsi normalized

        $bobot = $bobot_kriteria[$kriteria_id] ?? 0;
        $utility_value = $normalized_value * $bobot * 100;

        $key = $id_mitra . '_' . $peran;

        if (!isset($rekap[$key])) {
            $rekap[$key] = new stdClass();
            $rekap[$key]->id_mitra = $id_mitra;
            $rekap[$key]->nama = $nama_mitra[$id_mitra] ?? 'Unknown';
            $rekap[$key]->peran = $peran;
            $rekap[$key]->total = 0;
        }

        $rekap[$key]->total += $utility_value;
    }

    // Ubah ke array dan urutkan total secara descending
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

    public function get_mitra_by_kegiatan($id_kegiatan)
{
    // Ambil ID pencacah dari all_kegiatan_pencacah
    $pencacah_ids = $this->db->select('id_mitra')
        ->from('all_kegiatan_pencacah')
        ->where('kegiatan_id', $id_kegiatan)
        ->get()
        ->result_array();
    $id_pencacah = array_column($pencacah_ids, 'id_mitra');

    // Ambil ID pengawas dari all_kegiatan_pencacah
    $pengawas_ids = $this->db->select('id_pengawas')
        ->from('all_kegiatan_pencacah')
        ->where('kegiatan_id', $id_kegiatan)
        ->get()
        ->result_array();
    $id_pengawas = array_unique(array_column($pengawas_ids, 'id_pengawas'));

    // Ambil data pencacah dari rinciankegiatan + mitra
    $this->db->select('mitra.id_mitra, mitra.email, mitra.nama, rinciankegiatan.posisi, "pencacah" as peran');
    $this->db->from('rinciankegiatan');
    $this->db->join('mitra', 'mitra.id_mitra = rinciankegiatan.id_mitra');
    $this->db->where('rinciankegiatan.kegiatan_id', $id_kegiatan);
    if (!empty($id_pencacah)) {
        $this->db->where_in('mitra.id_mitra', $id_pencacah);
    } else {
        $this->db->where('1=0'); // kosongkan jika tidak ada
    }
    $query_pencacah = $this->db->get()->result();

    // Ambil data pengawas langsung dari mitra berdasarkan id_pengawas
    $query_pengawas = [];
    if (!empty($id_pengawas)) {
        $this->db->select('mitra.id_mitra, mitra.email, mitra.nama, CAST(NULL AS SIGNED) as jeniskegiatan_id, "pengawas" as peran');
        $this->db->from('mitra');
        $this->db->where_in('id_mitra', $id_pengawas);
        $query_pengawas = $this->db->get()->result();
    }

    // Gabungkan hasil pencacah dan pengawas
    return array_merge($query_pencacah, $query_pengawas);
}


}
