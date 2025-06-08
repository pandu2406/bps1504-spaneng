<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <h3>Edit Rincian Honor</h3>
            <?= $this->session->flashdata('message'); ?>

            <form action="<?= base_url('rekap/updatedetailbkm'); ?>" method="post">
                <input type="hidden" name="id_kegiatan" value="<?= $id_kegiatan; ?>">
                <input type="hidden" name="id_mitra" value="<?= $id_mitra; ?>">

                <div class="form-group">
                    <label>Nama Mitra</label>
                    <input type="text" class="form-control" value="<?= $mitra['nama'] ?? 'Tidak diketahui'; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Sistem Pembayaran</label>
                    <select name="sistem_pembayaran" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($sistem_pembayaran_list as $sp): ?>
                            <option value="<?= $sp['kode']; ?>" <?= ($rinci['sistem_pembayaran'] ?? $rinci['ob'] ?? '') == $sp['kode'] ? 'selected' : ''; ?>>
                                <?= $sp['kode'] . ' - ' . $sp['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Beban (Satuan)</label>
                    <input type="number" name="beban" class="form-control" value="<?= $rinci['beban'] ?? 0 ?>">
                </div>
                <div class="form-group">
                    <label>Honor per Beban</label>
                    <input type="number" name="honor" class="form-control" value="<?= $rinci['honor'] ?? 0 ?>">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('rekap/details_mitra/' . $rinci['id_mitra']); ?>" class="btn btn-secondary">Kembali</a>
            </form>

        </div>
    </div>

    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
