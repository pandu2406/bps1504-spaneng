<!-- Begin Page Content -->
<div class="container-fluid">
    <?= $this->session->flashdata('message'); ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-circle mr-2 text-primary"></i>Detail Mitra Statistik
        </h1>
        <a href="<?= base_url('master/mitra') ?>" class="btn btn-outline-secondary btn-sm shadow-sm rounded-pill">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        
        <!-- Profile Column -->
        <div class="col-xl-4 col-lg-5 mb-4">
            
            <!-- Profile Card -->
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden mb-4">
                <div class="card-header bg-gradient-primary py-5 text-center position-relative">
                    <div class="position-absolute w-100 h-100" style="top:0; left:0; background: url('<?= base_url('assets/img/pattern-dots.svg') ?>'); opacity: 0.1;"></div>
                    <img src="<?= base_url('assets/img/profile/') . ($user['image'] ?? 'default.jpg'); ?>"
                        class="rounded-circle shadow-lg mb-2 bg-white p-1" alt="Profile" width="120" height="120"
                        style="object-fit: cover; border: 4px solid rgba(255,255,255,0.3);">
                    <h4 class="font-weight-bold text-white mb-1"><?= $mitra['nama']; ?></h4>
                    <span class="badge badge-light text-primary badge-pill px-3 shadow-sm">
                        <i class="fas fa-id-card mr-1"></i> <?= $mitra['nik'] ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                         <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-1">Posisi Saat Ini</h6>
                         <span class="badge badge-info px-3 py-2 rounded-pill shadow-sm">
                            <?= $mitra['posisi'] ?? 'Mitra Pendataan'; ?>
                        </span>
                        <div class="mt-2">
                             <?php if ($mitra['is_active'] == '1'): ?>
                                <span class="badge badge-success badge-pill"><i class="fas fa-circle text-xs mr-1"></i> Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger badge-pill"><i class="fas fa-circle text-xs mr-1"></i> Non-Aktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                            <span class="text-muted"><i class="fas fa-venus-mars mr-2 text-primary" style="width:20px"></i>Jenis Kelamin</span>
                            <span class="font-weight-bold text-dark">
                                <?= ($mitra['jk'] == '1' || strtoupper($mitra['jk']) == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                            <span class="text-muted"><i class="fas fa-id-badge mr-2 text-primary" style="width:20px"></i>Sobat ID</span>
                            <span class="font-weight-bold text-dark"><?= $mitra['sobat_id'] ?: '-'; ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-top mt-2 pt-3">
                            <span class="text-muted"><i class="fas fa-map-marker-alt mr-2 text-primary" style="width:20px"></i>Wilayah</span>
                            <div class="text-right">
                                <span class="d-block font-weight-bold"><?= $mitra['nama_kecamatan'] ?: $mitra['kecamatan'] ?: '-'; ?></span>
                                <small class="text-muted"><?= $mitra['nama_desa'] ?: $mitra['desa'] ?: '-'; ?></small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="bg-light rounded p-3 mt-3">
                         <div class="mb-2 d-flex align-items-center">
                            <i class="fas fa-envelope text-gray-500 mr-3"></i>
                            <div class="text-truncate"><?= $mitra['email'] ?: '-'; ?></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fab fa-whatsapp text-success mr-3 font-weight-bold" style="font-size:1.1em"></i>
                            <?php if ($mitra['no_hp']): ?>
                                <a href="https://wa.me/62<?= ltrim($mitra['no_hp'], '0') ?>" target="_blank" class="text-dark font-weight-bold text-decoration-none hover-primary">
                                    <?= $mitra['no_hp']; ?> <i class="fas fa-external-link-alt ml-1 text-xs text-muted"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                 <div class="card-footer bg-white text-center py-3">
                    <button class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" type="button" data-toggle="collapse" data-target="#collapseActions" aria-expanded="false" aria-controls="collapseActions">
                        <i class="fas fa-cog mr-1"></i> Pengaturan Mitra
                    </button>
                    <div class="collapse mt-3" id="collapseActions">
                        <div class="d-flex flex-column gap-2">
                             <a href="<?= base_url('kegiatan/details_mitra_kegiatan/') . $mitra['id_mitra']; ?>" class="btn btn-primary btn-sm btn-block mb-2">
                                <i class="fas fa-chart-line mr-1"></i> Detail Kegiatan
                            </a>
                            <a href="<?= base_url('master/editmitra/') . $mitra['id_mitra']; ?>" class="btn btn-success btn-sm btn-block mb-2">
                                <i class="fas fa-edit mr-1"></i> Edit Profil
                            </a>
                             <?php if ($mitra['is_active'] == '1'): ?>
                                <a href="<?= base_url('master/deactivated/') . $mitra['id_mitra']; ?>" class="btn btn-warning btn-sm btn-block" onclick="return confirm('Nonaktifkan mitra ini?')">
                                    <i class="fas fa-ban mr-1"></i> Nonaktifkan
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('master/activated/') . $mitra['id_mitra']; ?>" class="btn btn-info btn-sm btn-block" onclick="return confirm('Aktifkan kembali mitra ini?')">
                                    <i class="fas fa-check mr-1"></i> Aktifkan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Performance & History Column -->
        <div class="col-xl-8 col-lg-7">
            
            <!-- Statistics Row -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Rata-rata Nilai (Overall)</div>
                                    <div class="h3 mb-0 font-weight-bold text-gray-800"><?= $average_all > 0 ? number_format($average_all, 2) : '-'; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Kegiatan (<?= (!empty($filter_bulan) || !empty($filter_tahun)) ? 'Filtered' : 'All Time' ?>)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= count($kegiatan_pencacah) + count($kegiatan_pengawas) ?> Kegiatan
                                    </div>
                                    <small class="text-muted">Pencacah: <?= count($kegiatan_pencacah) ?> | Pengawas: <?= count($kegiatan_pengawas) ?></small>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main History Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Riwayat Penilaian</h6>
                    
                    <!-- Role Filter Tabs -->
                    <ul class="nav nav-pills nav-sm" id="roleTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active rounded-pill px-3" id="pencacah-tab" data-toggle="tab" href="#pencacah" role="tab" aria-controls="pencacah" aria-selected="true">Pendataan</a>
                        </li>
                        <li class="nav-item ml-2">
                            <a class="nav-link rounded-pill px-3" id="pengawas-tab" data-toggle="tab" href="#pengawas" role="tab" aria-controls="pengawas" aria-selected="false">Pengawasan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="tab-content" id="myTabContent">
                        
                        <?php
                        // Role check logic
                        $role_id = $this->session->userdata('role_id');
                        $is_mitra = ($role_id == 5);
                        ?>

                        <!-- Pencacah Content -->
                        <div class="tab-pane fade show active" id="pencacah" role="tabpanel" aria-labelledby="pencacah-tab">
                            <?php if (empty($kegiatan_pencacah)): ?>
                                <div class="text-center py-5">
                                    <img src="<?= base_url('assets/img/undraw_empty.svg') ?>" class="img-fluid mb-3" style="width: 150px; opacity: 0.5;">
                                    <p class="text-muted mb-0">Belum ada riwayat kegiatan pendataan.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center table-flush mb-0" id="tablePencacah">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-top-0 pl-4" style="min-width: 200px;">Kegiatan / Periode</th>
                                                <?php for($i=0; $i<5; $i++): ?>
                                                    <th class="text-center border-top-0" style="font-size: 0.8rem; width: 10%;">
                                                        <?= isset($kriteria_list[$i]) ? $kriteria_list[$i]['nama'] : 'I-' . ($i+1) ?>
                                                    </th>
                                                <?php endfor; ?>
                                                <th class="text-center border-top-0 pr-4 text-primary font-weight-bold" style="width: 15%;">Nilai Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kegiatan_pencacah as $k): ?>
                                                <tr>
                                                    <td class="pl-4 py-3">
                                                        <div class="font-weight-bold text-dark"><?= $k['nama_kegiatan'] ?></div>
                                                        <div class="small text-muted">
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            <?= date('d M Y', $k['start']) ?> - <?= date('d M Y', $k['finish']) ?>
                                                        </div>
                                                    </td>
                                                    
                                                    <?php 
                                                    $detail_map = [];
                                                    if(!empty($k['details'])){
                                                        foreach($k['details'] as $d) $detail_map[$d['kriteria']] =    $d['nilai'];
                                                    }
                                                    ?>

                                                    <?php for($i=0; $i<5; $i++): ?>
                                                        <td class="text-center align-middle">
                                                            <?php if (!$is_mitra): ?>
                                                                <?php 
                                                                    $kriteria_name = isset($kriteria_list[$i]) ? $kriteria_list[$i]['nama'] : '';
                                                                    $score = isset($detail_map[$kriteria_name]) ? $detail_map[$kriteria_name] : false;
                                                                    
                                                                    if($score !== false):
                                                                        $bg_class = 'bg-light';
                                                                        if($score >= 4) $bg_class = 'bg-success text-white'; // Assuming scale 1-5 or similar, adjust if 1-100 logic needed
                                                                        // Usually detailed scores are 1-5 or 1-100. Assuming raw values.
                                                                        // If raw values are 1-100:
                                                                        $text_class = 'text-gray-700';
                                                                        if($score >= 90) $text_class = 'text-success font-weight-bold';
                                                                        elseif($score < 70) $text_class = 'text-warning';
                                                                ?>
                                                                    <span class="<?= $text_class ?>"><?= $score ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <i class="fas fa-lock text-gray-300" data-toggle="tooltip" title="Restricted for Mitra"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endfor; ?>
                                                    
                                                    <td class="text-center pr-4 align-middle">
                                                        <?php if ($k['nilai_rata_rata']): ?>
                                                            <div class="btn btn-sm btn-circle cursor-default <?= $k['nilai_rata_rata'] >= 90 ? 'btn-success' : ($k['nilai_rata_rata'] >= 75 ? 'btn-info' : 'btn-warning') ?> shadow-sm font-weight-bold" style="width: 40px; height: 40px; line-height: 28px; font-size: 0.9rem;">
                                                                <?= number_format($k['nilai_rata_rata'], 1) ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="badge badge-light">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pengawas Content -->
                        <div class="tab-pane fade" id="pengawas" role="tabpanel" aria-labelledby="pengawas-tab">
                             <?php if (empty($kegiatan_pengawas)): ?>
                                <div class="text-center py-5">
                                    <img src="<?= base_url('assets/img/undraw_empty.svg') ?>" class="img-fluid mb-3" style="width: 150px; opacity: 0.5;">
                                    <p class="text-muted mb-0">Belum ada riwayat kegiatan pengawasan.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center table-flush mb-0" id="tablePengawas">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-top-0 pl-4" style="min-width: 200px;">Kegiatan / Periode</th>
                                                <?php for($i=0; $i<5; $i++): ?>
                                                    <th class="text-center border-top-0" style="font-size: 0.8rem; width: 10%;">
                                                        <?= isset($kriteria_list[$i]) ? $kriteria_list[$i]['nama'] : 'I-' . ($i+1) ?>
                                                    </th>
                                                <?php endfor; ?>
                                                <th class="text-center border-top-0 pr-4 text-success font-weight-bold" style="width: 15%;">Nilai Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kegiatan_pengawas as $k): ?>
                                                <tr>
                                                    <td class="pl-4 py-3">
                                                        <div class="font-weight-bold text-dark"><?= $k['nama_kegiatan'] ?></div>
                                                        <div class="small text-muted">
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            <?= date('d M Y', $k['start']) ?> - <?= date('d M Y', $k['finish']) ?>
                                                        </div>
                                                    </td>
                                                    
                                                    <?php 
                                                    $detail_map = [];
                                                    if(!empty($k['details'])){
                                                        foreach($k['details'] as $d) $detail_map[$d['kriteria']] =    $d['nilai'];
                                                    }
                                                    ?>

                                                    <?php for($i=0; $i<5; $i++): ?>
                                                        <td class="text-center align-middle">
                                                            <?php if (!$is_mitra): ?>
                                                                <?php 
                                                                    $kriteria_name = isset($kriteria_list[$i]) ? $kriteria_list[$i]['nama'] : '';
                                                                    $score = isset($detail_map[$kriteria_name]) ? $detail_map[$kriteria_name] : false;
                                                                    
                                                                    if($score !== false):
                                                                        $text_class = 'text-gray-700';
                                                                        if($score >= 90) $text_class = 'text-success font-weight-bold';
                                                                        elseif($score < 70) $text_class = 'text-warning';
                                                                ?>
                                                                    <span class="<?= $text_class ?>"><?= $score ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <i class="fas fa-lock text-gray-300" data-toggle="tooltip" title="Restricted for Mitra"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endfor; ?>
                                                    
                                                    <td class="text-center pr-4 align-middle">
                                                       <?php if ($k['nilai_rata_rata']): ?>
                                                            <div class="btn btn-sm btn-circle cursor-default <?= $k['nilai_rata_rata'] >= 90 ? 'btn-success' : ($k['nilai_rata_rata'] >= 75 ? 'btn-info' : 'btn-warning') ?> shadow-sm font-weight-bold" style="width: 40px; height: 40px; line-height: 28px; font-size: 0.9rem;">
                                                                <?= number_format($k['nilai_rata_rata'], 1) ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="badge badge-light">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Custom Style for Profile Pattern & Details -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
    }
    .hover-primary:hover {
        color: #4e73df !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        background-color: #4e73df;
    }
    .nav-pills .nav-link {
        color: #5a5c69;
        font-weight: 500;
    }
    /* Smooth transition for hover effects */
    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
</style>

<script>
    $(document).ready(function(){
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Ensure tabs work correctly if direct link hashes are used
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-pills a[href="#' + url.split('#')[1] + '"]').tab('show');
        } 
    });
</script>