<!-- SweetAlert Script for Delete Survei -->
<script>
    // SweetAlert for Delete Survei
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-survei');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const url = this.getAttribute('href');
                const nama = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Hapus Survei?',
                    html: `Yakin ingin menghapus survei:<br><strong>${nama}</strong>?<br><br><small class="text-danger">Data pencacah dan pengawas juga akan terhapus!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    });
</script>