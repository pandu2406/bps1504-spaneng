<!-- Footer -->
<footer class="sticky-footer">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; IPDS1504 2025</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/theme-toggle.js"></script>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDeleteMitra(url, nama) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `
        <p>Anda akan menghapus data mitra:</p>
        <h4 style="color: #e74a3b; font-weight: bold; margin: 10px 0;">${nama}</h4>
        <div class="alert alert-warning" style="font-size: 0.9em; margin-top: 15px; color: #856404; background-color: #fff3cd; border-color: #ffeeba;">
          <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan!</strong><br>
          Semua riwayat kegiatan dan status keaktifan mitra ini akan dihapus permanen.
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
                if (result.isConfirmed) {
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
            if (confirm("Yakin ingin menghapus mitra: " + nama + "?")) {
                window.location.href = url;
            }
        }
    }
</script>

<!-- Auto-dismiss Flash Messages -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashMessages = document.querySelectorAll('.alert');

        flashMessages.forEach(function (message) {
            // Add fade-out animation after 5 seconds
            setTimeout(function () {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';

                // Remove from DOM after fade
                setTimeout(function () {
                    message.remove();
                }, 500);
            }, 5000);

            // Add close button if not exists
            if (!message.querySelector('.close')) {
                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'close';
                closeBtn.setAttribute('data-dismiss', 'alert');
                closeBtn.setAttribute('aria-label', 'Close');
                closeBtn.innerHTML = '<span aria-hidden="true">&times;</span>';
                message.insertBefore(closeBtn, message.firstChild);
            }
        });
    });
</script>

<!-- Page level plugins -->
<script src="<?= base_url('assets/'); ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/'); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url('assets/'); ?>js/demo/datatables-demo.js"></script>

<script src="<?= base_url('assets/'); ?>jquery-ui/jquery-ui.js"></script>
<script src="<?= base_url('assets/'); ?>jquery-ui/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.5.1/snap.svg-min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.5.0/frappe-gantt.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.5.0/frappe-gantt.min.js.map"></script> -->




<script>
    $('.alert').alert().delay(2000).slideUp('slow');

    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });


    $('.form-access-input').on('click', function () {
        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');

        $.ajax({
            url: "<?= base_url('admin/changeaccess') ?>",
            type: 'post',
            data: {
                menuId: menuId,
                roleId: roleId
            },
            success: function () {
                document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
            }
        });

    });

    // --- OPTIMIZED CHECKBOX HANDLERS (No Reload) ---
    
    // Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    function handleCheckboxAjax(url, data, element) {
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    
                    // Optional: Update Quota Counter dynamically if possible
                    updateQuotaCounter(response.type);

                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                    // Revert checkbox if error
                    $(element).prop('checked', !$(element).prop('checked'));
                }
            },
            error: function () {
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan sistem'
                });
                 $(element).prop('checked', !$(element).prop('checked'));
            }
        });
    }
    
    // Simple counter updater (Requires ID in view)
    function updateQuotaCounter(type) {
        let countElem = $('#quota-count');
        if (countElem.length) {
            let currentText = countElem.text().trim(); // Format: "5 / 10"
            let parts = currentText.split('/');
            if (parts.length === 2) {
                let current = parseInt(parts[0].trim());
                let max = parseInt(parts[1].trim());
                
                if (type === 'add') current++;
                if (type === 'remove') current--;
                
                countElem.text(current + ' / ' + max);
            }
        }
    }

    $('.form-pencacah-input').on('change', function () {
        const kegiatanId = $(this).data('kegiatan');
        const mitraId = $(this).data('pencacah');
        handleCheckboxAjax("<?= base_url('kegiatan/changepencacah') ?>", {kegiatanId: kegiatanId, mitraId: mitraId}, this);
    });

    $('.form-pengawas-input').on('change', function () {
        const kegiatanId = $(this).data('kegiatan');
        const id_peg = $(this).data('pengawas');
        handleCheckboxAjax("<?= base_url('kegiatan/changepengawas') ?>", {kegiatanId: kegiatanId, id_peg: id_peg}, this);
    });

    $('.form-pencacah-organik-input').on('change', function () {
        const kegiatanId = $(this).data('kegiatan');
        const id_peg = $(this).data('pegawai');
        handleCheckboxAjax("<?= base_url('kegiatan/changepencacahorganik') ?>", {kegiatanId: kegiatanId, id_peg: id_peg}, this);
    });

    $('.form-pengawas-mitra-input').on('change', function () {
        const kegiatanId = $(this).data('kegiatan');
        const id_mitra = $(this).data('pengawas');
        handleCheckboxAjax("<?= base_url('kegiatan/changepengawas_mitra') ?>", {kegiatanId: kegiatanId, id_mitra: id_mitra}, this);
    });

    $('.form-pencacahpengawas-input').on('click', function () {
        const kegiatanId = $(this).data('kegiatan');
        const id_pengawas = $(this).data('id_pengawas');
        const id_pencacah = $(this).data('id_pencacah');
        const type = $(this).data('type');

        $.ajax({
            url: "<?= base_url('kegiatan/changepencacahpengawas') ?>",
            type: 'post',
            data: {
                kegiatanId: kegiatanId,
                id_pengawas: id_pengawas,
                id_pencacah: id_pencacah,
                type: type
            },
            success: function () {
                document.location.href = "<?= base_url('kegiatan/tambah_pencacah_pengawas/'); ?>" + kegiatanId + '/' + id_pengawas;
            }
        });

    });

    $('.form-nilai-input').on('click', function () {
        const allkegiatanpencacahId = $(this).data('all_kegiatan_pencacah_id');
        const kegiatanId = $(this).data('kegiatan_id');
        const mitraId = $(this).data('id_mitra');
        const kriteriaId = $(this).data('kriteria');
        const nilaiId = $(this).data('nilai');
        const pegId = $(this).data('peg');

        $.ajax({
            url: "<?= base_url('penilaian/changenilai') ?>",
            type: 'post',
            data: {
                allkegiatanpencacahId: allkegiatanpencacahId,
                kriteriaId: kriteriaId,
                nilaiId: nilaiId

            },
            success: function () {
                document.location.href = "<?= base_url('penilaian/isi_nilai/'); ?>" + kegiatanId + "/" + pegId + "/" + mitraId;
            }
        });

    });

    //  $('.form-nilai-input').on('click', function() {
    //      const kegiatanId = $(this).data('kegiatan_id');
    //      const mitraId = $(this).data('id_mitra');
    //      const kriteriaId = $(this).data('kriteria');
    //      const nilaiId = $(this).data('nilai');

    //      $.ajax({
    //          url: "<?= base_url('penilaian/changenilai') ?>",
    //          type: 'post',
    //          data: {
    //              kegiatanId: kegiatanId,
    //              mitraId: mitraId,
    //              kriteriaId: kriteriaId,
    //              nilaiId: nilaiId

    //          },
    //          success: function() {
    //              document.location.href = "<?= base_url('penilaian/isi_nilai/'); ?>" + kegiatanId + "/" + mitraId;
    //          }
    //      });

    //  });
</script>


<script>
    $(document).ready(function () {
        $('#mydata').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            pagingType: "full_numbers"
        });
    });
</script>

<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        }

        );
    }

    );
</script>

<script>
    $(document).ready(function () {
        $('input[name="nama"]').on('input', function () {
            var val = $(this).val();
            var listId = $(this).attr('list');
            if (listId) {
                var option = $('#' + listId + ' option[value="' + val + '"]');
                if (option.length) {
                    var periodicity = option.data('periodisitas');
                    if (periodicity) {
                        $('select[name="periodisitas"]').val(periodicity);
                    }
                }
            }
        });

        // Delete Confirmation for Survei
        // Delete Confirmation for Survei
        $('.btn-delete-survei').on('click', function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            const nama = $(this).data('nama');

            Swal.fire({
                title: 'Hapus Kegiatan?',
                html: "Apakah Anda yakin ingin menghapus kegiatan<br><strong>" + nama + "</strong>?<br><br><small class='text-muted'>Tindakan ini tidak dapat dibatalkan.</small>",
                icon: 'warning',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b', // SB Admin 2 Danger Red
                cancelButtonColor: '#858796', // SB Admin 2 Secondary Gray
                confirmButtonText: '<i class="fas fa-trash"></i> Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.value || result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>



<?php
// FORCE CLEAR SESSION MESSAGE AFTER DISPLAY
if (isset($_SESSION['message']) || $this->session->flashdata('message')) {
    $this->session->unset_userdata('message');
    if (isset($_SESSION['message']))
        unset($_SESSION['message']);
}
?>
<style>
    /* Fix Datepicker z-index in Modals */
    .ui-datepicker {
        z-index: 9999 !important;
    }
</style>
</body>

</html>