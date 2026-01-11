<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model
{
    // Fungsi ambil rekap kerja mitra (pencacah & pengawas), dengan filter bulan & tahun
    public function get_rekap_mitra($bulan, $tahun)
    {
        // OLD: $mitras = $this->db->get('mitra')->result_array();

        // NEW: Filter by year using mitra_tahun table
        $this->db->select('m.*, mt.posisi as posisi_tahun, mt.is_active as active_tahun');
        $this->db->from('mitra m'); // View mitra (mitra_old)

        if ($tahun > 0) {
            $this->db->join('mitra_tahun mt', 'm.id_mitra = mt.id_mitra');
            $this->db->where('mt.tahun', $tahun);
        } else {
            // "Semua Tahun" condition: Only show mitras registered in MORE THAN 1 YEAR
            $this->db->join('mitra_tahun mt', 'm.id_mitra = mt.id_mitra');
            $this->db->group_by('m.id_mitra');
            $this->db->having('COUNT(DISTINCT mt.tahun) > 1');
        }

        $mitras = $this->db->get()->result_array();

        $rekap = [];

        foreach ($mitras as $m) {
            $id_mitra = $m['id_mitra'];
            $nama_mitra = $m['nama'];

            // Only count if name is not empty
            if (empty($nama_mitra))
                continue;

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
                'nama' => $nama_mitra,
                'jk' => $jk,
                'jk_p' => $jk_p,
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
        // Condition for year: if 0, select all years
        $yearCondition = ($tahun > 0) ? "AND YEAR(FROM_UNIXTIME(k.finish)) = " . (int) $tahun : "";

        foreach ($mitra as $m) {
            $bulanan = array_fill(1, 12, 0); // default 0 untuk semua bulan

            $sql = "
            SELECT 
                MONTH(FROM_UNIXTIME(k.finish)) AS bulan,
                SUM(rk.total_honor) AS total_honor
            FROM rinciankegiatan rk
            JOIN kegiatan k ON k.id = rk.kegiatan_id
            WHERE rk.id_mitra = ? $yearCondition
            GROUP BY MONTH(FROM_UNIXTIME(k.finish))
            ";

            $query = $this->db->query($sql, [$m->id_mitra]);

            foreach ($query->result() as $row) {
                $bulanan[(int) $row->bulan] = (float) $row->total_honor;
            }

            $result[] = (object) [
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

        // Siapkan data awal
        foreach ($all_mitra as $mitra) {
            $result[$mitra->id_mitra] = (object) [
                'nama_mitra' => $mitra->nama,
                'bulanan' => array_fill(1, 12, 0),
                'total_kegiatan' => 0 // New field
            ];
        }

        // Loop setiap bulan
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanan = $this->getTotalNilaiBulananSAW($bulan, $tahun);

            foreach ($bulanan as $id_mitra => $row) {
                if (isset($result[$id_mitra])) {
                    $result[$id_mitra]->bulanan[$bulan] = round($row->rata_nilai, 2);
                    $result[$id_mitra]->total_kegiatan += $row->count; // Aggregate count
                }
            }
        }

        return $result;
    }

    public function getTotalNilaiBulananSAW($bulan, $tahun)
    {
        $yearCondition = ($tahun > 0) ? "AND YEAR(FROM_UNIXTIME(finish)) = " . (int) $tahun : "";

        $kegiatan = $this->db->query("
            SELECT id, nama
            FROM kegiatan
            WHERE MONTH(FROM_UNIXTIME(finish)) = ? $yearCondition
        ", [$bulan])->result();

        $result = [];

        foreach ($kegiatan as $keg) {
            // Assuming Ranking_model->totalakhir returns list of mitras with scores for this activity
            $totalakhir = $this->Ranking_model->totalakhir($keg->id);

            foreach ($totalakhir as $row) {
                $key = $row->id_mitra;

                if (!isset($result[$key])) {
                    $result[$key] = (object) [
                        'nama' => $row->nama,
                        'total' => 0,
                        'count' => 0
                    ];
                }

                $result[$key]->total += $row->total; // Sum of scores
                $result[$key]->count += 1; // Count of activities
            }
        }

        foreach ($result as $key => $row) {
            $row->rata_nilai = $row->count > 0 ? $row->total / $row->count : 0;
        }

        return $result;
    }

    public function getRiwayatMitra($id_mitra)
    {
        $sql = "
            SELECT 
                YEAR(FROM_UNIXTIME(k.finish)) AS tahun,
                COUNT(rk.kegiatan_id) AS jumlah_kegiatan,
                SUM(rk.total_honor) AS total_honor
            FROM rinciankegiatan rk
            JOIN kegiatan k ON k.id = rk.kegiatan_id
            WHERE rk.id_mitra = ?
            GROUP BY YEAR(FROM_UNIXTIME(k.finish))
            ORDER BY YEAR(FROM_UNIXTIME(k.finish)) DESC
        ";

        return $this->db->query($sql, [$id_mitra])->result_array();
    }


    public function sync_rincian_honor($id_mitra, $kegiatan_id)
    {
        // 1. Ambil data kegiatan
        $keg = $this->db->get_where('kegiatan', ['id' => $kegiatan_id])->row_array();
        if (!$keg)
            return false;

        // 2. Cek alokasi di pencacah atau pengawas
        $alokasi_pencacah = $this->db->get_where('all_kegiatan_pencacah', ['id_mitra' => $id_mitra, 'kegiatan_id' => $kegiatan_id])->row_array();
        $alokasi_pengawas = $this->db->get_where('all_kegiatan_pengawas', ['id_pengawas' => $id_mitra, 'kegiatan_id' => $kegiatan_id])->row_array();

        if (!$alokasi_pencacah && !$alokasi_pengawas) {
            // Jika tidak ada alokasi lagi, hapus record di rinciankegiatan
            $this->db->delete('rinciankegiatan', ['id_mitra' => $id_mitra, 'kegiatan_id' => $kegiatan_id]);
            return true;
        }

        // 3. Tentukan peran/posisi
        $peran = $alokasi_pengawas ? 'pengawas' : 'pencacah';
        $posisi = isset($keg['posisi']) && $keg['posisi'] > 0 ? $keg['posisi'] : ($peran === 'pengawas' ? 2 : 3);

        // 4. Data insert/update
        $honor = (float) ($keg['honor'] ?? 0);
        $beban = (int) ($keg['beban_standar'] ?? 1);
        $total_honor = $honor * $beban;

        $data = [
            'id_mitra' => $id_mitra,
            'kegiatan_id' => $kegiatan_id,
            'honor' => $honor,
            'beban' => $beban,
            'total_honor' => $total_honor,
            'satuan' => $keg['satuan'] ?? null,
            'ob' => $keg['ob'] ?? null,
            'posisi' => $posisi,
            'start' => $keg['start'] ?? null,
            'finish' => $keg['finish'] ?? null,
            'seksi_id' => $keg['seksi_id'] ?? null,
        ];

        // 5. Cek apakah sudah ada
        $cek = $this->db->get_where('rinciankegiatan', ['id_mitra' => $id_mitra, 'kegiatan_id' => $kegiatan_id])->row();

        if ($cek) {
            $this->db->update('rinciankegiatan', $data, ['id' => $cek->id]);
        } else {
            $this->db->insert('rinciankegiatan', $data);
        }

        return true;
    }

    public function remove_rincian_honor($id_mitra, $kegiatan_id)
    {
        $this->db->delete('rinciankegiatan', ['id_mitra' => $id_mitra, 'kegiatan_id' => $kegiatan_id]);
    }
}
