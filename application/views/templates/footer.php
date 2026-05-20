    <!-- Footer -->
    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center text-muted small">
                    &copy; <?= date('Y'); ?> <strong>Sistem Presensi SMP - SMPTK Galang Kasih Ubung</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery 3.7 -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/custom.js'); ?>"></script>
    
    <script>
        // Sidebar toggle for mobile
        $(document).ready(function() {
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 768) {
                    if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
                        $('#sidebar').removeClass('active');
                    }
                }
            });
            
            // Auto-hide alerts after 3 seconds
            $('.alert-dismissible').not('.alert-permanent').each(function() {
                var $alert = $(this);
                setTimeout(function() {
                    $alert.fadeOut('slow', function() {
                        $alert.alert('close');
                    });
                }, 3000);
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
        
        // CSRF token setup for AJAX
        var csrf_name = '<?= $csrf_name; ?>';
        var csrf_hash = '<?= $csrf_hash; ?>';
        
        $.ajaxSetup({
            data: function() {
                var data = {};
                data[csrf_name] = csrf_hash;
                return data;
            }(),
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", csrf_hash);
                }
            }
        });
        
        // Update CSRF hash after each AJAX request
        $(document).ajaxComplete(function(event, xhr, settings) {
            var responseHash = xhr.getResponseHeader('X-CSRF-HASH');
            if (responseHash) {
                csrf_hash = responseHash;
            }
        });
    </script>
</body>
</html>
