<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h3 class="h3 mb-0 text-gray-800">
    Edit Rincian Honor - <?= htmlspecialchars($kegiatan['nama'] ?? 'Kegiatan Tidak Ditemukan') ?>
    </h3>


    <?= $this->session->flashdata('message'); ?>

    <div class="row">
        <div class="col-lg-8">
            <form action="<?= base_url('rekap/updatedetailbkm'); ?>" method="post">
                <input type="hidden" name="id_kegiatan" value="<?= htmlspecialchars($id_kegiatan); ?>">
                <input type="hidden" name="id_mitra" value="<?= htmlspecialchars($id_mitra); ?>">
            
                <div class="form-group">
                    <label for="nama_mitra">Nama Mitra</label>
                    <input type="text" class="form-control" id="nama_mitra"
                           value="<?= htmlspecialchars($mitra['nama'] ?? 'Tidak diketahui'); ?>" disabled>
                </div>
            
                <div class="form-group">
                    <label for="sistem_pembayaran">Paket Data</label>
                    <select class="form-control" id="sistem_pembayaran_display" disabled>
                        <option value="">-- Pilih --</option>
                        <?php foreach ($sistem_pembayaran_list as $sp): ?>
                            <option value="<?= htmlspecialchars($sp['kode']); ?>"
                                <?= (isset($rinci['ob']) && $rinci['ob'] == $sp['kode']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($sp['kode']) . ' - ' . htmlspecialchars($sp['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="sistem_pembayaran" value="<?= htmlspecialchars($rinci['ob'] ?? ''); ?>">
                </div>
            
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <select class="form-control" id="satuan_display" disabled>
                        <option value="">-- Pilih Satuan --</option>
                        <?php foreach ($satuan_list as $s): ?>
                            <option value="<?= htmlspecialchars($s['id']); ?>"
                                <?= ($rinci['satuan'] ?? '') == $s['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['id']) . ' - ' . htmlspecialchars($s['satuan']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="satuan" value="<?= htmlspecialchars($rinci['satuan'] ?? ''); ?>">
                </div>
            
                <div class="form-group">
                    <label for="posisi">Jenis Kegiatan</label>
                    <select class="form-control" id="posisi_display" disabled>
                        <option value="">-- Pilih Posisi --</option>
                        <?php foreach ($posisi_list as $p): ?>
                            <option value="<?= htmlspecialchars($p['id']); ?>"
                                <?= ($rinci['posisi'] ?? '') == $p['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($p['id']) . ' - ' . htmlspecialchars($p['posisi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="posisi" value="<?= htmlspecialchars($rinci['posisi'] ?? ''); ?>">
                </div>
            
                <div class="form-group">
                    <label for="beban">Beban (Jumlah Satuan)</label>
                    <input type="number" name="beban" id="beban" class="form-control"
                           value="<?= htmlspecialchars($rinci['beban'] ?? 0); ?>" required>
                </div>
            
                <div class="form-group">
                    <label for="honor">Honor per Satuan</label>
                    <!-- Tampilan untuk user -->
                    <input type="text" name="honor_display" id="honor_display" class="form-control"
                           value="<?= number_format($rinci['honor'] ?? 0, 0, ',', '.'); ?>" required>
                    <!-- Nilai real yang dikirim -->
                    <input type="hidden" name="honor" id="honor"
                           value="<?= htmlspecialchars($rinci['honor'] ?? 0); ?>">
                </div>
            
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url("rekap/details_mitra/{$id_mitra}/{$bulan}/{$tahun}"); ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const displayInput = document.getElementById('honor_display');
    const hiddenInput = document.getElementById('honor');

    // Fungsi untuk format Rupiah
    function formatRupiah(angka) {
        return angka.replace(/\D/g, '') // Hapus semua non-digit
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambah titik ribuan
    }

    // Update tampilan dan hidden input saat user mengetik
    displayInput.addEventListener('input', function () {
        const raw = displayInput.value.replace(/\./g, '').replace(/\D/g, '');
        displayInput.value = formatRupiah(raw);
        hiddenInput.value = raw;
    });
});
</script>
