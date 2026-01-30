/**
 * Main Application JavaScript
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

$(document).ready(function() {
    // Navbar scroll effect
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 76
            }, 500);
        }
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Form validation styling
    $('form').on('submit', function() {
        $(this).find('.form-control').each(function() {
            if (!this.checkValidity()) {
                $(this).addClass('is-invalid');
            }
        });
    });

    // Remove invalid class on input
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});

// Utility functions
const Utils = {
    formatDate: function(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    },

    formatNumber: function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },

    showLoading: function(element) {
        $(element).html('<span class="spinner-border spinner-border-sm me-2"></span>Loading...');
        $(element).prop('disabled', true);
    },

    hideLoading: function(element, text) {
        $(element).html(text);
        $(element).prop('disabled', false);
    },

    showToast: function(message, type = 'success') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };
        toastr[type](message);
    }
};
