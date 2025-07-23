<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6">

            <?= $this->session->flashdata('message'); ?>
            <div class="row" style="color:#00264d;">
                <div class="col-lg">
                    <h4>Kegiatan: <?= $kegiatan['nama']; ?></h4>
                    <h4>Mitra: <?= $id_mitra['nama']; ?></h4>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align="center">
                            <th scope="col">Kriteria</th>
                            <th scope="col">Nilai</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">
                        <?php foreach ($nilai as $n) : ?>
                            <tr align="center">
                                <td align="left"><?= htmlspecialchars($n['nama']); ?></td>
                                <td><?= htmlspecialchars($n['nilai']); ?></td>
                            </tr>
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

<!-- Tambahkan Script DataTables di sini -->
<script>
  $(document).ready(function() {
    $('#mydata').DataTable({
    "order": false // jangan urutkan apa pun saat load
    });
  });
</script>
