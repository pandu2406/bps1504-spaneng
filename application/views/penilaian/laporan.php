<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($kegiatan['nama']); ?> - <?= htmlspecialchars($mitra['nama']); ?> - Laporan Penilaian
        Kinerja Mitra</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
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

        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .table th {
            padding: 8px 8px;
            border: 1px solid #000000;
            text-align: center;
        }

        .table td {
            padding: 3px 3px;
            border: 1px solid #000000;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page">
        <table style="width: 100%;">
            <tr>
                <td align="left"><img src="<?= base_url('assets/img/bps.png'); ?>" style="width:300px;"
                        alt="bpsBatang Hari"></td>
                <td align="right"><img src="<?= base_url('assets/img/tagline.png'); ?>" style="width: 170px;"
                        alt="tagline"></td>
            </tr>
        </table>
        <br>
        <h2 align="center">Laporan Penilaian Kinerja Mitra</h2>
        <br>
        <table>
            <tr>
                <th align="left">Kegiatan</th>
                <td>: <?= htmlspecialchars($kegiatan['nama']); ?></td>
            </tr>
            <tr>
                <th align="left">Nama Mitra</th>
                <td>: <?= htmlspecialchars($mitra['nama']); ?></td>
            </tr>
        </table>
        <br>

        <div style="width:40%; margin:auto;">
            <canvas id="myChart"></canvas>
        </div>

        <br>

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kriteria</th>
                    <th>Nilai</th>
                    <th>Bobot</th>
                    <th>Nilai Terbobot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($jumlah_kriteria)) {
                    $jumlah_kriteria = count($penilaian);
                }

                $i = 1;
                $total_nilai = 0;
                $total_bobot = 0;
                $total_nilai_terbobot = 0;

                foreach ($penilaian as $p):
                    $nilai = $p['nilai'];
                    $bobot = isset($p['bobot']) ? $p['bobot'] : 0;
                    $nilai_terbobot = $nilai * $bobot;

                    $total_nilai += $nilai;
                    $total_bobot += $bobot;
                    $total_nilai_terbobot += $nilai_terbobot;
                    ?>
                    <tr align="center">
                        <td><?= $i ?></td>
                        <td align="left"><?= htmlspecialchars($p['nama']); ?></td>
                        <td><?= $nilai; ?></td>
                        <td><?= number_format($bobot, 2); ?></td>
                        <td><?= number_format($nilai_terbobot, 2); ?></td>
                    </tr>
                    <?php
                    $i++;
                endforeach;

                $rata_rata = $jumlah_kriteria > 0 ? $total_nilai / $jumlah_kriteria : 0;
                $rata_rata_berbobot = $total_bobot > 0 ? $total_nilai_terbobot / $total_bobot : 0;

                // Kategori berdasarkan rata-rata berbobot
                if ($rata_rata_berbobot <= 50) {
                    $kategori = 'Sangat Kurang (E)';
                } elseif ($rata_rata_berbobot <= 60) {
                    $kategori = 'Kurang (D)';
                } elseif ($rata_rata_berbobot <= 75) {
                    $kategori = 'Cukup (C)';
                } elseif ($rata_rata_berbobot <= 90) {
                    $kategori = 'Baik (B)';
                } else {
                    $kategori = 'Sangat Baik (A)';
                }
                ?>
                <tr align="center" style="font-weight:bold; background-color:#f0f0f0;">
                    <td colspan="3">Rata-rata</td>
                    <td><?= number_format($total_bobot, 2); ?></td>
                    <td><?= number_format($rata_rata_berbobot, 2); ?> (<?= $kategori; ?>)</td>
                </tr>
            </tbody>
        </table>

        <br><br><br><br>

        <table style="width:100%;">
            <tr>
                <th align="center" style="width:50%">Penilai</th>
                <th align="center" style="width:50%">Penanggung Jawab Kegiatan</th>
            </tr>
            <tr>
                <td align="center" style="padding-top: 80px;">
                    <?= htmlspecialchars($penilai['nama'] ?? 'Penilai tidak ditemukan'); ?>
                </td>
                <td align="center" style="padding-top: 80px;">
                    <?= htmlspecialchars($pjk ?? 'Penanggung Jawab tidak ditemukan'); ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

<?php
// Ambil nilai maksimum dari penilaian
$nilai_max = max(array_column($penilaian, 'nilai'));

// Tentukan stepSize berdasarkan nilai maksimum
if ($nilai_max > 80) {
    $step = 20;
} elseif ($nilai_max > 50) {
    $step = 10;
} else {
    $step = 5;
}
?>

<?php
// Ambil nilai maksimum dari penilaian
$nilai_max = max(array_column($penilaian, 'nilai'));

// Tentukan stepSize berdasarkan nilai maksimum
if ($nilai_max > 80) {
    $step = 20;
} elseif ($nilai_max > 50) {
    $step = 10;
} else {
    $step = 5;
}
?>

<script>
    var ctx = document.getElementById("myChart").getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: [
                <?php
                foreach ($penilaian as $data) {
                    echo json_encode($data['nama']) . ",";
                }
                ?>
            ],
            datasets: [{
                label: 'Kinerja Mitra (Nilai)',
                data: [
                    <?php
                    foreach ($penilaian as $data) {
                        echo $data['nilai'] . ",";
                    }
                    ?>
                ],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(255, 99, 132, 1)',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Aspek Penilaian Terbobot',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + context.raw.toFixed(2);
                        }
                    }
                }
            },
            elements: {
                line: {
                    borderWidth: 2
                }
            },
            scales: {
                r: {
                    min: 0,
                    max: 100, // tetapkan maksimal
                    ticks: {
                        stepSize: <?= $step ?>,
                        backdropColor: 'transparent'
                    },
                    pointLabels: {
                        font: {
                            size: 14
                        },
                        color: '#000'
                    },
                    grid: {
                        color: '#ccc'
                    },
                    angleLines: {
                        color: '#aaa'
                    }
                }
            }
        }
    });
    setTimeout(function () {
        window.print();
    }, 800);
</script>