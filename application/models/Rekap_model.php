<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model
{
    // Fungsi ambil rekap kerja mitra (pencacah & pengawas), dengan filter bulan & tahun
    public function get_rekap_mitra($bulan, $tahun)
{
    $mitras = $this->db->get('mitra')->result_array();
    $rekap = [];

    foreach ($mitras as $m) {
        $id_mitra = $m['id_mitra'];
        $nama_mitra = $m['nama'];

        $this->db->from('all_kegiatan_pengawas');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pengawas.kegiatan_id');
        $this->db->where('all_kegiatan_pengawas.id_pengawas', $id_mitra);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        if ($tahun > 0) {
            $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        }
        $jk = $this->db->count_all_results();

        $this->db->from('all_kegiatan_pencacah');
        $this->db->join('kegiatan', 'kegiatan.id = all_kegiatan_pencacah.kegiatan_id');
        $this->db->where('all_kegiatan_pencacah.id_mitra', $id_mitra);
        if ($bulan > 0) {
            $this->db->where("MONTH(FROM_UNIXTIME(kegiatan.finish)) =", $bulan);
        }
        if ($tahun > 0) {
            $this->db->where("YEAR(FROM_UNIXTIME(kegiatan.finish)) =", $tahun);
        }
        $jk_p = $this->db->count_all_results();

        $rekap[] = [
            'id_mitra' => $id_mitra,
            'nama'     => $nama_mitra,
            'jk'       => $jk,
            'jk_p'     => $jk_p,
        ];
    }

    return $rekap;
}

public function getRekapHonor($bulan = 0, $tahun = null)
{
    $bulan = (int) $bulan;
    $tahun = $tahun ?? date('Y');

    $bulan_filter = ($bulan > 0) ? "AND MONTH(FROM_UNIXTIME(keg.finish)) = $bulan" : "";
    $tahun_filter = ($tahun > 0) ? "AND YEAR(FROM_UNIXTIME(keg.finish)) = $tahun" : "";

    $sql = "
    (
        SELECT
            keg.id AS kegiatan_id,
            keg.nama AS namakeg,
            keg.start,
            keg.finish,
            mit.nama AS mitranama,
            mit.id_mitra,
            mit.sobat_id,
            'pengawas' AS peran,
            rk.ob,
            sp.nama AS sistem_pembayaran,
            rk.beban,
            rk.honor,
            rk.total_honor,
            rk.posisi AS posisi_id,
            p.posisi AS posisi_nama,
            s.nama AS nama_seksi,
            sa.satuan AS satuan_nama
        FROM all_kegiatan_pengawas akp
        JOIN kegiatan keg ON akp.kegiatan_id = keg.id
        JOIN mitra mit ON mit.id_mitra = akp.id_pengawas
        LEFT JOIN rinciankegiatan rk ON rk.kegiatan_id = keg.id AND rk.id_mitra = akp.id_pengawas
        LEFT JOIN sistempembayaran sp ON rk.ob = sp.kode
        LEFT JOIN posisi p ON rk.posisi = p.id
        LEFT JOIN seksi s ON keg.seksi_id = s.id
        LEFT JOIN satuan sa ON rk.satuan = sa.id
        WHERE 1=1
        $bulan_filter
        $tahun_filter
    )
    UNION ALL
    (
        SELECT
            keg.id AS kegiatan_id,
            keg.nama AS namakeg,
            keg.start,
            keg.finish,
            mit.nama AS mitranama,
            mit.id_mitra,
            mit.sobat_id,
            'pencacah' AS peran,
            rk.ob,
            sp.nama AS sistem_pembayaran,
            rk.beban,
            rk.honor,
            rk.total_honor,
            rk.posisi AS posisi_id,
            p.posisi AS posisi_nama,
            s.nama AS nama_seksi,
            sa.satuan AS satuan_nama
        FROM all_kegiatan_pencacah ak
        JOIN kegiatan keg ON ak.kegiatan_id = keg.id
        JOIN mitra mit ON mit.id_mitra = ak.id_mitra
        LEFT JOIN rinciankegiatan rk ON rk.kegiatan_id = keg.id AND rk.id_mitra = ak.id_mitra
        LEFT JOIN sistempembayaran sp ON rk.ob = sp.kode
        LEFT JOIN posisi p ON rk.posisi = p.id
        LEFT JOIN seksi s ON keg.seksi_id = s.id
        LEFT JOIN satuan sa ON rk.satuan = sa.id
        WHERE 1=1
        $bulan_filter
        $tahun_filter
    )
    ORDER BY mitranama ASC, start ASC
    ";

    return $this->db->query($sql)->result();
}

public function getRekapTotalMitra($bulan = 0, $tahun = null)
{
    $bulan = (int) $bulan;
    $tahun = $tahun ?? date('Y');

    $bulan_filter = ($bulan > 0) ? "AND MONTH(FROM_UNIXTIME(k.finish)) = $bulan" : "";
    $tahun_filter = ($tahun > 0) ? "AND YEAR(FROM_UNIXTIME(k.finish)) = $tahun" : "";

    $sql = "
    SELECT 
        m.id_mitra,
        m.nama AS nama_mitra,
        m.sobat_id,

        -- Total posisi 1
        SUM(CASE WHEN rk.posisi = 1 THEN rk.total_honor ELSE 0 END) AS total_pos1,

        -- Total posisi 2
        SUM(CASE WHEN rk.posisi = 2 THEN rk.total_honor ELSE 0 END) AS total_pos2,

        -- Total posisi 4
        SUM(CASE WHEN rk.posisi = 4 THEN rk.total_honor ELSE 0 END) AS total_pos4,

        -- Total posisi 3 sebagai PPL
        (
            SELECT SUM(rk2.total_honor)
            FROM all_kegiatan_pencacah ak
            JOIN kegiatan k ON k.id = ak.kegiatan_id
            JOIN rinciankegiatan rk2 ON rk2.kegiatan_id = k.id AND rk2.id_mitra = ak.id_mitra
            WHERE ak.id_mitra = m.id_mitra AND rk2.posisi = 3
            $bulan_filter $tahun_filter
        ) AS total_ppl,

        -- Total posisi 3 sebagai PML
        (
            SELECT SUM(rk3.total_honor)
            FROM all_kegiatan_pengawas ap
            JOIN kegiatan k ON k.id = ap.kegiatan_id
            JOIN rinciankegiatan rk3 ON rk3.kegiatan_id = k.id AND rk3.id_mitra = ap.id_pengawas
            WHERE ap.id_pengawas = m.id_mitra AND rk3.posisi = 3
            $bulan_filter $tahun_filter
        ) AS total_pml

    FROM mitra m
    LEFT JOIN rinciankegiatan rk ON rk.id_mitra = m.id_mitra
    LEFT JOIN kegiatan k ON k.id = rk.kegiatan_id
    WHERE 1=1 $bulan_filter $tahun_filter
    GROUP BY m.id_mitra
    ";

    return $this->db->query($sql)->result();
}

public function getRekapTahunanPerMitra($tahun)
{
    // Ambil daftar semua mitra
    $mitra = $this->db->get('mitra')->result();

    $result = [];

    foreach ($mitra as $m) {
        $bulanan = array_fill(1, 12, 0); // default 0 untuk semua bulan

        $query = $this->db->query("
            SELECT 
                MONTH(FROM_UNIXTIME(k.finish)) AS bulan,
                SUM(rk.total_honor) AS total_honor
            FROM rinciankegiatan rk
            JOIN kegiatan k ON k.id = rk.kegiatan_id
            WHERE rk.id_mitra = ? AND YEAR(FROM_UNIXTIME(k.finish)) = ?
            GROUP BY MONTH(FROM_UNIXTIME(k.finish))
        ", [$m->id_mitra, $tahun]);

        foreach ($query->result() as $row) {
            $bulanan[(int) $row->bulan] = (float) $row->total_honor;
        }

        $result[] = (object)[
            'id_mitra' => $m->id_mitra,
            'nama_mitra' => $m->nama,
            'sobat_id' => $m->sobat_id,
            'bulanan' => $bulanan
        ];
    }

    return $result;
}

public function getRekapNilaiTahunanSAW($tahun)
{
    // Ambil semua mitra
    $all_mitra = $this->db->get('mitra')->result();
    $result = [];

    // Siapkan data awal (0 semua)
    foreach ($all_mitra as $mitra) {
        $result[$mitra->id_mitra] = (object)[
            'nama_mitra' => $mitra->nama,
            'bulanan' => []
        ];
        for ($i = 1; $i <= 12; $i++) {
            $result[$mitra->id_mitra]->bulanan[$i] = 0;
        }
    }

    // Loop setiap bulan
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $bulanan = $this->getTotalNilaiBulananSAW($bulan, $tahun);

        foreach ($bulanan as $id_mitra => $row) {
            $result[$id_mitra]->bulanan[$bulan] = round($row->rata_nilai, 2);
        }
    }

    return $result;
}

public function getTotalNilaiBulananSAW($bulan, $tahun)
{
    $kegiatan = $this->db->query("
        SELECT id, nama
        FROM kegiatan
        WHERE MONTH(FROM_UNIXTIME(finish)) = ? AND YEAR(FROM_UNIXTIME(finish)) = ?
    ", [$bulan, $tahun])->result();

    $result = [];

    foreach ($kegiatan as $keg) {
        $totalakhir = $this->Ranking_model->totalakhir($keg->id);

        foreach ($totalakhir as $row) {
            $key = $row->id_mitra;

            if (!isset($result[$key])) {
                $result[$key] = (object)[
                    'nama' => $row->nama,
                    'total' => 0,
                    'count' => 0
                ];
            }

            $result[$key]->total += $row->total;
            $result[$key]->count += 1;
        }
    }

    foreach ($result as $key => $row) {
        $row->rata_nilai = $row->count > 0 ? $row->total / $row->count : 0;
    }

    return $result;
}


}
