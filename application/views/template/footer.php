 <!-- Footer -->
 <footer class="sticky-footer" style="background-color: #e0ecff;">
     <div class="container my-auto">
         <div class="copyright text-center my-auto" style="color:  #003366">
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
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

 <!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

     $('.custom-file-input').on('change', function() {
         let fileName = $(this).val().split('\\').pop();
         $(this).next('.custom-file-label').addClass("selected").html(fileName);
     });


     $('.form-access-input').on('click', function() {
         const menuId = $(this).data('menu');
         const roleId = $(this).data('role');

         $.ajax({
             url: "<?= base_url('admin/changeaccess') ?>",
             type: 'post',
             data: {
                 menuId: menuId,
                 roleId: roleId
             },
             success: function() {
                 document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
             }
         });

     });

     $('.form-pencacah-input').on('click', function() {
         const kegiatanId = $(this).data('kegiatan');
         const mitraId = $(this).data('pencacah');

         $.ajax({
             url: "<?= base_url('kegiatan/changepencacah') ?>",
             type: 'post',
             data: {
                 kegiatanId: kegiatanId,
                 mitraId: mitraId
             },
             success: function() {
                 document.location.href = "<?= base_url('kegiatan/tambah_pencacah/'); ?>" + kegiatanId;
             }
         });

     });

     $('.form-pengawas-input').on('click', function() {
         const kegiatanId = $(this).data('kegiatan');
         const id_peg = $(this).data('pengawas');

         $.ajax({
             url: "<?= base_url('kegiatan/changepengawas') ?>",
             type: 'post',
             data: {
                 kegiatanId: kegiatanId,
                 id_peg: id_peg
             },
             success: function() {
                 document.location.href = "<?= base_url('kegiatan/tambah_pengawas/'); ?>" + kegiatanId;
             }
         });

     });

     $('.form-pengawas-mitra-input').on('click', function() {
         const kegiatanId = $(this).data('kegiatan');
         const id_mitra = $(this).data('pengawas');

         $.ajax({
             url: "<?= base_url('kegiatan/changepengawas_mitra') ?>",
             type: 'post',
             data: {
                 kegiatanId: kegiatanId,
                 id_mitra: id_mitra
             },
             success: function() {
                 document.location.href = "<?= base_url('kegiatan/tambah_pengawas_mitra/'); ?>" + kegiatanId;
             }
         });

     });

     $('.form-pencacahpengawas-input').on('click', function() {
         const kegiatanId = $(this).data('kegiatan');
         const id_peg = $(this).data('pengawas');
         const id_mitra = $(this).data('pencacah');

         $.ajax({
             url: "<?= base_url('kegiatan/changepencacahpengawas') ?>",
             type: 'post',
             data: {
                 kegiatanId: kegiatanId,
                 id_peg: id_peg,
                 id_mitra: id_mitra,
             },
             success: function() {
                 document.location.href = "<?= base_url('kegiatan/tambah_pencacah_pengawas/'); ?>" + kegiatanId + '/' + id_peg;
             }
         });

     });

     $('.form-nilai-input').on('click', function() {
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
             success: function() {
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
     $(document).ready(function() {
         $('#mydata').DataTable({
             paging: true,
             searching: true,
             ordering: true,
             pagingType: "full_numbers"
         });
     });
 </script>

 <script type="text/javascript">
     $(function() {
             $(".datepicker").datepicker({
                     format: 'yyyy-mm-dd',
                     autoclose: true,
                     todayHighlight: true,
                 }

             );
         }

     );
 </script>


 </body>

 </html>