<!-- Auto-dismiss Flash Messages Script -->
<script>
    // Auto-hide flash messages after 5 seconds
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
            }, 5000); // 5 seconds

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