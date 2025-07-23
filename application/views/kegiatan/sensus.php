<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">
            <?= form_error('sensus', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('message'); ?>

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newsensusModal">Add New Sensus</a>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
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
                        <?php foreach ($sensus as $s) : ?>
                            <tr align=center>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $s['nama']; ?></td>
                                <td><?= date('d F Y', $s['start']); ?></td>
                                <td><?= date('d F Y', $s['finish']); ?></td>
                                <td><?= $s['k_pengawas']; ?></td>
                                <td><?= $s['k_pencacah']; ?></td>
                                <?php $now = (time()); ?>
                                <td>

                                    <a href="<?= base_url('kegiatan/tambah_pengawas/') . $s['id']; ?>" class="badge badge-success">tambah pengawas</a>
                                    <a href="<?= base_url('kegiatan/tambah_pencacah/') . $s['id']; ?>" class="badge badge-info">tambah pencacah</a>

                                    <a href="<?= base_url('kegiatan/editsensus/') . $s['id']; ?>" class="badge badge-primary">edit</a>
                                    <a href="<?= base_url('kegiatan/deletesensus/') . $s['id']; ?>" class="badge badge-danger">delete</a>
                                </td>


                                <?php if ($now < $s['start']) : ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $s['finish']) : ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else : ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
                                <?php endif; ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach ?>
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


<!-- Modal -->
<div class="modal fade" id="newsensusModal" tabindex="-1" role="dialog" aria-labelledby="newsensusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newsensusModalLabel">Add New Sensus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('kegiatan/sensus') ?>" method="post">
        <div class="modal-body">

          <!-- Nama Kegiatan -->
          <div class="form-group">
            <input type="text" class="form-control" name="nama" placeholder="Nama Kegiatan" required>
          </div>

          <!-- Tanggal Mulai -->
          <div class="form-group">
            <input type="text" class="form-control datepicker" name="start" placeholder="Tanggal Mulai" required>
          </div>

          <!-- Tanggal Selesai -->
          <div class="form-group">
            <input type="text" class="form-control datepicker" name="finish" placeholder="Tanggal Selesai" required>
          </div>

          <!-- Kuota Pengawas -->
          <div class="form-group">
            <input type="number" class="form-control" name="k_pengawas" placeholder="Jumlah Pengawas" required>
          </div>

          <!-- Kuota Pencacah -->
          <div class="form-group">
            <input type="number" class="form-control" name="k_pencacah" placeholder="Jumlah Pencacah" required>
          </div>

          <!-- Seksi -->
          <div class="form-group">
            <?php
  // Mapping Email ke Seksi ID
  $mapping = [
    'produksi1504@gmail.com' => 1,
    'sosial1504@gmail.com' => 2,
    'distribusi1504@gmail.com' => 3,
    'nerwilis1504@gmail.com' => 4,
    'ipds1504@gmail.com' => 5
  ];

  $user_email = $user['email'];
  $user_role = $user['role_id'];
?>

<select name="seksi_id" class="form-control" required>
  <option value="">Penanggung Jawab (Seksi)</option>

  <?php if ($user_role == 1) : ?>
    <!-- Admin bisa pilih semua -->
    <?php foreach ($seksi as $s) : ?>
      <option value="<?= $s['id']; ?>"><?= $s['nama']; ?></option>
    <?php endforeach; ?>

  <?php elseif ($user_role == 3 && isset($mapping[$user_email])) : ?>
    <!-- Operator hanya bisa pilih sesuai mapping -->
    <?php foreach ($seksi as $s) : ?>
      <?php if ($s['id'] == $mapping[$user_email]) : ?>
        <option value="<?= $s['id']; ?>" selected><?= $s['nama']; ?></option>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</select>

          </div>

          <!-- Posisi -->
          <div class="form-group">
            <select name="posisi" class="form-control" required>
              <option value="">Jenis Kegiatan</option>
              <?php foreach ($posisi as $p) : ?>
                <option value="<?= $p['id']; ?>"><?= $p['posisi']; ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Satuan -->
          <div class="form-group">
            <select name="satuan" class="form-control" required>
              <option value="">Satuan</option>
              <?php foreach ($satuan as $satuan) : ?>
                <option value="<?= $satuan['id']; ?>"><?= $satuan['satuan']; ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Honor -->
          <div class="form-group">
            <input type="number" step="0.01" class="form-control" name="honor" placeholder="Honor Satuan(Rp)" required>
          </div>

          <!-- OB (Pulsa / Paket Data) -->
          <div class="form-group">
            <select name="ob" class="form-control" required>
              <option value="">Apakah ada pulsa/paket data bulanan</option>
              <?php foreach ($sistempembayaran_list as $ob) : ?>
                <option value="<?= $ob['kode']; ?>"><?= $ob['nama']; ?></option>
              <?php endforeach ?>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>