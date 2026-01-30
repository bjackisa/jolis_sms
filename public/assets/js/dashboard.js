/**
 * Dashboard JavaScript
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

$(document).ready(function() {
    // Sidebar toggle for mobile
    $('#sidebarToggle').on('click', function() {
        $('#sidebar').addClass('active');
        $('#sidebarOverlay').addClass('active');
    });

    $('#sidebarClose, #sidebarOverlay').on('click', function() {
        $('#sidebar').removeClass('active');
        $('#sidebarOverlay').removeClass('active');
    });

    // Initialize DataTables with default options
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: '<i class="bi bi-chevron-double-left"></i>',
                    last: '<i class="bi bi-chevron-double-right"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                    previous: '<i class="bi bi-chevron-left"></i>'
                }
            },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            responsive: true
        });
    }

    // Initialize Select2
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Confirm delete actions
    $(document).on('click', '.btn-delete, [data-confirm]', function(e) {
        const message = $(this).data('confirm') || 'Are you sure you want to delete this item?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });

    // AJAX form submission
    $(document).on('submit', 'form.ajax-form', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Operation successful');
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    toastr.error(response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                toastr.error(response?.message || 'An error occurred');
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

    // File input preview
    $(document).on('change', 'input[type="file"][data-preview]', function() {
        const input = this;
        const previewId = $(this).data('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // Dynamic form fields
    $(document).on('click', '.add-field', function() {
        const template = $(this).data('template');
        const container = $(this).data('container');
        $(container).append($(template).html());
    });

    $(document).on('click', '.remove-field', function() {
        $(this).closest('.dynamic-field').remove();
    });

    // Print functionality
    $(document).on('click', '.btn-print', function() {
        window.print();
    });

    // Export to CSV
    $(document).on('click', '.btn-export-csv', function() {
        const tableId = $(this).data('table');
        exportTableToCSV(tableId);
    });
});

// Export table to CSV
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (const row of rows) {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        
        for (const col of cols) {
            let text = col.innerText.replace(/"/g, '""');
            rowData.push('"' + text + '"');
        }
        
        csv.push(rowData.join(','));
    }
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (navigator.msSaveBlob) {
        navigator.msSaveBlob(blob, filename);
    } else {
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
}

// Chart.js default configuration
if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
}

// Toastr default configuration
if (typeof toastr !== 'undefined') {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000,
        extendedTimeOut: 1000
    };
}
