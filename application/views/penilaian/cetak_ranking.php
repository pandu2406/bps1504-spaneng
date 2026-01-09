<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Ranking Mitra - <?= htmlspecialchars($kegiatan['nama']); ?></title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }

        .table th, .table td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
        }

        h2, h4 {
            text-align: center;
            margin: 0;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body, html {
                width: 210mm;
                height: 297mm;
            }

            .page {
                margin: 0;
                border: none;
                border-radius: 0;
                box-shadow: none;
                background: none;
                page-break-after: always;
            }
        }
    </style>

    <script>
        setTimeout(function() {
  window.print();
}, 500);
    </script>
</head>

<body>
    <div class="page">
        <table style="width: 100%;">
            <tr>
                <td align="left"><img src="<?= base_url('assets/img/bps.png'); ?>" style="width:280px;"></td>
                <td align="right"><img src="<?= base_url('assets/img/tagline.png'); ?>" style="width:160px;"></td>
            </tr>
        </table>

        <br>
        <h2>Laporan Ranking Kinerja Mitra</h2>
        <h4><?= htmlspecialchars($kegiatan['nama']); ?></h4>
        <h4>PJ Kegiatan: <?= htmlspecialchars($pjk); ?></h4>
        <br>

        <table class="table">
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Nama Mitra</th>
                    <th>Nilai Total</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ranking as $r): 
                    $nilai = $r['nilai_akhir'];
                    if ($nilai <= 50) {
                        $kategori = 'Sangat Kurang (E)';
                    } elseif ($nilai <= 60) {
                        $kategori = 'Kurang (D)';
                    } elseif ($nilai <= 75) {
                        $kategori = 'Cukup (C)';
                    } elseif ($nilai <= 90) {
                        $kategori = 'Baik (B)';
                    } else {
                        $kategori = 'Sangat Baik (A)';
                    }
                ?>
                    <tr>
                        <td><?= $r['ranking']; ?></td>
                        <td style="text-align:left"><?= htmlspecialchars($r['nama_mitra']); ?></td>
                        <td><?= number_format($nilai, 2); ?></td>
                        <td><?= $kategori; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br><br>
        <div style="width:100%; text-align:center; margin-top:80px;">
            <strong>Penanggung Jawab Kegiatan</strong><br><br><br><br><br><br><br>
            <b><?= htmlspecialchars($pjk); ?></b><br>
            <u><?= htmlspecialchars($nip_pjk); ?></u>
        </div>
    </div>
</body>
</html>