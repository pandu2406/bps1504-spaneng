<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="row">
    <div class="col-lg">
      <?= form_error('sensus', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

      <?= $this->session->flashdata('message'); ?>

      <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
          <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-filter mr-2"></i>Filter Kegiatan Sensus</h6>
        </div>
        <div class="card-body">
          <form action="<?= base_url('kegiatan/sensus'); ?>" method="get">
            <div class="row align-items-end">
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-dark">Tahun</label>
                  <select name="year" class="form-control form-control-sm">
                    <option value="">Semua</option>
                    <option value="2024" <?= $filter_year == '2024' ? 'selected' : ''; ?>>2024</option>
                    <option value="2025" <?= $filter_year == '2025' ? 'selected' : ''; ?>>2025</option>
                    <option value="2026" <?= $filter_year == '2026' ? 'selected' : ''; ?>>2026</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-dark">Penanggung Jawab (Seksi)</label>
                  <select name="seksi_id" class="form-control form-control-sm">
                    <option value="">Semua Seksi</option>
                    <?php foreach ($seksi as $s): ?>
                      <option value="<?= $s['id']; ?>" <?= $filter_seksi == $s['id'] ? 'selected' : ''; ?>>
                        <?= $s['nama']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-dark">Dari Tanggal</label>
                  <input type="date" name="start_date" class="form-control form-control-sm"
                    value="<?= $filter_start; ?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-dark">Sampai Tanggal</label>
                  <input type="date" name="end_date" class="form-control form-control-sm" value="<?= $filter_end; ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm mr-1"><i
                      class="fas fa-search fa-sm mr-1"></i>Cari</button>
                  <a href="<?= base_url('kegiatan/sensus'); ?>"
                    class="btn btn-sm btn-outline-secondary px-3 shadow-sm"><i
                      class="fas fa-undo fa-sm mr-1"></i>Reset</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <a href="" class="btn btn-primary mb-3 shadow-sm" data-toggle="modal" data-target="#newsensusModal"><i
          class="fas fa-plus fa-sm mr-1"></i>Add New Sensus</a>

      <div class="table-responsive">
        <table class="table table-borderless table-hover" id="mydata">
          <thead style="background-color: #00264d; color:#e6e6e6;">
            <tr align=center>
              <th scope="col">#</th>
              <th scope="col">Nama</th>
              <th scope="col">Periodisitas</th>
              <th scope="col">Penanggung Jawab</th>
              <th scope="col">Start</th>
              <th scope="col">Finish</th>
              <th scope="col">Jumlah Pengawas</th>
              <th scope="col">Jumlah Pencacah</th>
              <th scope="col">Action</th>
              <th scope="col">Status</th>

            </tr>
          </thead>
          <tbody style="background-color: #ffffff; color: #00264d;">
            <?php $i = 1; ?>
            <?php foreach ($sensus as $s): ?>
              <tr align=center>
                <th scope="row">
                  <?= $i; ?>
                </th>
                <td>
                  <?= $s['nama']; ?>
                </td>
                <td>
                  <?= $s['periodisitas']; ?>
                </td>
                <td>
                  <?= $s['nama_seksi'] ?? '<i class="text-muted">Belum ada</i>'; ?>
                </td>
                <td>
                  <?= date('d F Y', $s['start']); ?>
                </td>
                <td>
                  <?= date('d F Y', $s['finish']); ?>
                </td>
                <td>
                  <?= $s['k_pengawas']; ?>
                </td>
                <td>
                  <?= $s['k_pencacah']; ?>
                </td>
                <?php $now = (time()); ?>
                <td>

                  <a href="<?= base_url('kegiatan/tambah_pengawas/') . $s['id']; ?>" class="badge badge-success">tambah
                    pengawas</a>
                  <a href="<?= base_url('kegiatan/tambah_pencacah/') . $s['id']; ?>" class="badge badge-info">tambah
                    pencacah</a>

                  <a href="<?= base_url('kegiatan/editsensus/') . $s['id']; ?>" class="badge badge-primary">edit</a>
                  <a href="javascript:void(0);" class="badge badge-danger btn-delete-sensus"
                    data-url="<?= base_url('kegiatan/deletesensus/' . $s['id']); ?>"
                    data-nama="<?= htmlspecialchars($s['nama'], ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="fas fa-trash"></i> Hapus
                  </a>
                </td>
                <td>
                  <?php $now = time(); ?>
                  <?php if ($now < $s['start']): ?>
                    <span class="badge badge-warning">belum mulai</span>
                  <?php elseif ($now > $s['finish']): ?>
                    <span class="badge badge-danger">selesai</span>
                  <?php else: ?>
                    <span class="badge badge-primary">sedang berjalan</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
  <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<!-- Modal Add New Sensus -->
<div class="modal fade" id="newsensusModal" tabindex="-1" role="dialog" aria-labelledby="newsensusModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="newsensusModalLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Sensus Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('kegiatan/sensus') ?>" method="post" autocomplete="off">
        <div class="modal-body">
          <div class="row">
            <!-- Left Column: Activity Info -->
            <div class="col-md-6 border-right">
              <h6 class="font-weight-bold text-primary mb-3">Detail Kegiatan</h6>

              <!-- Nama Kegiatan -->
              <div class="form-group">
                <label class="small font-weight-bold">Nama Kegiatan</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                  </div>
                  <input type="text" class="form-control" name="nama" placeholder="Contoh: Sensus Penduduk 2030"
                    required>
                </div>
              </div>

              <!-- Periodisitas -->
              <div class="form-group">
                <label class="small font-weight-bold">Periode</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
                  <select name="periodisitas" class="form-control" required>
                    <option value="">-- Pilih Periode --</option>
                    <option value="Mingguan">Mingguan</option>
                    <option value="Bulanan">Bulanan</option>
                    <option value="Triwulanan">Triwulanan</option>
                    <option value="Subround (4 Bulanan)">Subround (4 Bulanan)</option>
                    <option value="Semesteran">Semesteran</option>
                    <option value="Tahunan">Tahunan</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <!-- Tanggal Mulai -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Tanggal Mulai</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                      </div>
                      <input type="text" class="form-control datepicker" name="start" placeholder="YYYY-MM-DD" required>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Tanggal Selesai -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Tanggal Selesai</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-times"></i></span>
                      </div>
                      <input type="text" class="form-control datepicker" name="finish" placeholder="YYYY-MM-DD"
                        required>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Penanggung Jawab -->
              <div class="form-group">
                <label class="small font-weight-bold">Penanggung Jawab (Seksi)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                  </div>
                  <select name="seksi_id" class="form-control" required>
                    <option value="">-- Pilih Seksi --</option>
                    <?php foreach ($seksi as $s): ?>
                      <option value="<?= $s['id']; ?>"><?= $s['nama']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

            </div>

            <!-- Right Column: Resources -->
            <div class="col-md-6">
              <h6 class="font-weight-bold text-primary mb-3">Sumber Daya & Anggaran</h6>

              <div class="row">
                <div class="col-md-6">
                  <!-- Kuota Pengawas -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Jml Pengawas</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                      </div>
                      <input type="number" class="form-control" name="k_pengawas" placeholder="0" required>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Kuota Pencacah -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Jml Pencacah</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                      </div>
                      <input type="number" class="form-control" name="k_pencacah" placeholder="0" required>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Jenis Kegiatan -->
              <div class="form-group">
                <label class="small font-weight-bold">Jenis Posisi</label>
                <select name="posisi" class="form-control" required>
                  <option value="">-- Pilih Posisi --</option>
                  <?php foreach ($posisi as $p): ?>
                    <option value="<?= $p['id']; ?>"><?= $p['posisi']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <!-- Satuan -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Satuan</label>
                    <select name="satuan" class="form-control" required>
                      <option value="">-- Pilih --</option>
                      <?php foreach ($satuan as $satuan): ?>
                        <option value="<?= $satuan['id']; ?>"><?= $satuan['satuan']; ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Honor -->
                  <div class="form-group">
                    <label class="small font-weight-bold">Honor (Rp)</label>
                    <input type="number" class="form-control" name="honor" placeholder="Rp 0" required>
                  </div>
                </div>
              </div>

              <!-- OB -->
              <div class="form-group">
                <label class="small font-weight-bold">Pulsa / Paket Data</label>
                <select name="ob" class="form-control" required>
                  <option value="">-- Pilih Opsi --</option>
                  <?php foreach ($sistempembayaran_list as $ob): ?>
                    <option value="<?= $ob['kode']; ?>"><?= $ob['nama']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
              class="fas fa-times mr-1"></i>Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Robust SweetAlert Script for Delete Sensus (Vanilla JS) -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-delete-sensus');

      if (btn) {
        e.preventDefault();

        const url = btn.getAttribute('data-url');
        const nama = btn.getAttribute('data-nama');

        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Konfirmasi Hapus', // Adjusted Title
            html: `
              <p>Anda akan menghapus sensus:</p>
              <h4 style="color: #1cc88a; font-weight: bold; margin: 10px 0;">${nama}</h4>
              <div class="alert alert-warning" style="font-size: 0.9em; margin-top: 15px; color: #856404; background-color: #fff3cd; border-color: #ffeeba;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan!</strong><br>
                Data pencacah dan pengawas terkait juga akan dihapus secara permanen.
              </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed || result.value) {
              Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading()
                }
              });
              window.location.href = url;
            }
          });
        } else {
          // Fallback
          if (confirm("Yakin ingin menghapus Sensus: " + nama + "?")) {
            window.location.href = url;
          }
        }
      }
    });
  });
</script>