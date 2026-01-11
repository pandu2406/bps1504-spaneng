<!-- Begin Page Content -->
<style>
    :root {
        --bg-color: #f8f9fc;
        --card-bg: #ffffff;
        --text-color: #3a3b45;
        --heading-color: #00264d;
        --table-header-bg: #00264d;
        --table-header-text: #e6e6e6;
        --table-row-hover: #f1f1f1;
        --input-bg: #ffffff;
        --input-border: #d1d3e2;
        --input-text: #6e707e;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-color: #e0e0e0;
            --heading-color: #bb86fc;
            --table-header-bg: #2c2c2c;
            --table-header-text: #ffffff;
            --table-row-hover: #333333;
            --input-bg: #2c2c2c;
            --input-border: #444444;
            --input-text: #ffffff;
        }

        body {
            background-color: var(--bg-color) !important;
            color: var(--text-color) !important;
        }

        .card {
            background-color: var(--card-bg) !important;
            border-color: #333 !important;
        }

        .text-primary {
            color: #bb86fc !important;
        }

        h2,
        h4,
        h6 {
            color: var(--text-color) !important;
        }
    }

    /* Custom Styles */
    .page-container {
        padding-top: 20px;
    }

    .header-section {
        margin-bottom: 25px;
        border-bottom: 2px solid var(--heading-color);
        padding-bottom: 10px;
    }

    .header-title {
        color: var(--heading-color);
        font-weight: 700;
    }

    .info-card {
        border-left: 5px solid var(--heading-color);
        background-color: var(--card-bg);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .table-custom thead {
        background-color: var(--table-header-bg);
        color: var(--table-header-text);
    }

    .table-custom tbody tr:hover {
        background-color: var(--table-row-hover);
    }

    .table-custom tbody tr td {
        color: var(--text-color);
        vertical-align: middle;
        font-size: 1.05rem;
    }

    .form-control-custom {
        border-radius: 20px;
        text-align: center;
        font-weight: bold;
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--input-text);
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 38, 77, 0.25);
        border-color: var(--heading-color);
    }

    .btn-custom-back {
        background-color: #6c757d;
        color: white;
        border-radius: 20px;
        padding: 8px 20px;
    }

    .btn-custom-submit {
        background-color: #28a745;
        color: white;
        border-radius: 20px;
        padding: 10px 40px;
        font-size: 1.1rem;
        box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3);
        transition: transform 0.2s;
    }

    .btn-custom-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(40, 167, 69, 0.4);
    }
</style>

<div class="container-fluid page-container">

    <?= $this->session->flashdata('message'); ?>

    <div class="d-flex justify-content-between align-items-center header-section">
        <div>
            <h2 class="header-title">Input Penilaian</h2>
            <p class="mb-0" style="color: var(--text-color);">Isi nilai kinerja untuk kegiatan ini.</p>
        </div>
        <div>
            <?php if ($peran == 'pengawas'): ?>
                <a href="<?= base_url('penilaian/daftar_pengawas/') . $kegiatan['id']; ?>" class="btn btn-custom-back"><i
                        class="fas fa-arrow-left"></i> Kembali</a>
            <?php else: ?>
                <a href="<?= base_url('penilaian/daftar_pencacah/') . $kegiatan['id'] . '/' . $id_peg; ?>"
                    class="btn btn-custom-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="info-card">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="font-weight-bold mb-2"><?= htmlspecialchars($kegiatan['nama']) ?></h4>
                        <h5 class="text-muted mb-0"><?= ucfirst($peran) ?>: <span
                                class="text-primary font-weight-bold"><?= htmlspecialchars($target['nama']) ?></span>
                        </h5>
                    </div>
                    <div class="col-md-4 text-md-right align-self-center">
                        <div class="alert alert-info py-2 px-3 d-inline-block mb-0">
                            <i class="fas fa-info-circle"></i> Input nilai 0 - 100
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?= form_open('penilaian/simpannilai'); ?>
            <!-- Hidden inputs -->
            <?php if ($peran === 'pengawas' && isset($all_kegiatan_pengawas['id'])): ?>
                <input type="hidden" name="peran" value="pengawas">
                <input type="hidden" name="all_id" value="<?= $all_kegiatan_pengawas['id']; ?>">
            <?php elseif ($peran === 'mitra' && isset($all_kegiatan_pencacah['id'])): ?>
                <input type="hidden" name="peran" value="mitra">
                <input type="hidden" name="all_id" value="<?= $all_kegiatan_pencacah['id']; ?>">
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr align="center">
                            <th scope="col" width="60%">Kriteria Penilaian</th>
                            <th scope="col" width="40%">Nilai (0-100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $k): ?>
                            <?php
                            $nilai = '';
                            if ($peran === 'pengawas' && isset($all_kegiatan_pengawas['id'])) {
                                $nilai = get_nilai_pengawas($all_kegiatan_pengawas['id'], $k['id']);
                            } elseif ($peran === 'mitra' && isset($all_kegiatan_pencacah['id'])) {
                                $nilai = get_nilai($all_kegiatan_pencacah['id'], $k['id']);
                            }
                            ?>
                            <tr>
                                <td class="pl-4 font-weight-bold">
                                    <?= htmlspecialchars($k['nama']) ?>
                                </td>
                                <td align="center">
                                    <input type="number" min="0" max="100" class="form-control form-control-custom"
                                        name="nilai[<?= $k['id']; ?>]" value="<?= htmlspecialchars($nilai) ?>"
                                        placeholder="0" required style="max-width: 150px;">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-right mt-4 mr-3">
                <button type="submit" class="btn btn-custom-submit">
                    <i class="fas fa-save mr-2"></i> Simpan Penilaian
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
    <br>
</div>
<!-- /.container-fluid -->