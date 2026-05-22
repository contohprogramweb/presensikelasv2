<!-- Footer -->
    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center text-muted small">
                    &copy; <?= date('Y'); ?> <strong>Sistem Presensi Kelas - SMPTK Galang Kasih Ubung</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery 3.7 -->
  
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    
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
            },
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", csrf_hash);
                }
            }
        });
        
        // Update CSRF hash after each AJAX request
        $(document).ajaxComplete(function(event, xhr, settings) {
            try {
                var json = JSON.parse(xhr.responseText);
                if (json && json.csrf_hash) {
                    csrf_hash = json.csrf_hash;
                }
            } catch(e) {}
            var responseHash = xhr.getResponseHeader('X-CSRF-HASH');
            if (responseHash) {
                csrf_hash = responseHash;
            }
        });
    </script>
	
	
	<script>
	// Custom JavaScript for Sistem Presensi Kelas

$(document).ready(function() {
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

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

    // Confirm delete actions
    $(document).on('click', '.btn-delete', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
            return false;
        }
    });

    // Format currency input
    $('.format-currency').on('keyup', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(new Intl.NumberFormat('id-ID').format(value));
    });

    // Auto-format phone number
    $('.format-phone').on('keyup', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        }
        $(this).val(value);
    });

    // Date picker initialization (if using any datepicker library)
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    // Character counter for textarea
    $('textarea[maxlength]').each(function() {
        var maxLength = $(this).attr('maxlength');
        var currentLength = $(this).val().length;
        var counter = $('<div class="text-muted small mt-1">Karakter: ' + currentLength + '/' + maxLength + '</div>');
        $(this).after(counter);
        
        $(this).on('input', function() {
            currentLength = $(this).val().length;
            counter.text('Karakter: ' + currentLength + '/' + maxLength);
        });
    });

    // Handle form validation display
    $('form.needs-validation').on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Refresh CSRF token after each AJAX request
    // csrf_name dan csrf_hash di-set sebagai variabel global di footer.php
    if (typeof csrf_name !== 'undefined' && typeof csrf_hash !== 'undefined') {
        var csrfData = {};
        csrfData[csrf_name] = csrf_hash;
        $.ajaxSetup({ data: csrfData });
    }
});

// Utility functions
function showLoading(element) {
    $(element).prop('disabled', true);
    $(element).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
}

function hideLoading(element, originalText) {
    $(element).prop('disabled', false);
    $(element).html(originalText);
}

function showToast(message, type = 'success') {
    var toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> ` + message + `</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    $('#toastContainer').append(toastHtml);
    var toastElement = document.querySelector('.toast:last-child');
    var toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remove toast after hidden
    toastElement.addEventListener('hidden.bs.toast', function () {
        toastElement.remove();
    });
}

// Export to Excel function (client-side)
function exportTableToExcel(tableId, filename = '') {
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableId);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename ? filename + '.xls' : 'excel_data.xls';
    
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if (navigator.msSaveOrOpenBlob) {
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}

// Print specific element
function printElement(elementId) {
    var printContents = document.getElementById(elementId).innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    
    location.reload(); // Reload to restore event handlers
}

	
	</script>
</body>
</html>