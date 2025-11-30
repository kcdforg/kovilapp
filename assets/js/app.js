// Kovil App - Modern Version JavaScript

// Global variables
let app = {
    config: {
        apiUrl: '',
        csrfToken: ''
    },
    utils: {},
    components: {}
};

// Utility functions
app.utils = {
    // Show success message
    showSuccess: function(message, duration = 5000) {
        this.showAlert('success', message, duration);
    },
    
    // Show error message
    showError: function(message, duration = 5000) {
        this.showAlert('danger', message, duration);
    },
    
    // Show warning message
    showWarning: function(message, duration = 5000) {
        this.showAlert('warning', message, duration);
    },
    
    // Show info message
    showInfo: function(message, duration = 5000) {
        this.showAlert('info', message, duration);
    },
    
    // Generic alert function
    showAlert: function(type, message, duration) {
        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Add to page
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
        }
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, duration);
        }
    },
    
    // Format date
    formatDate: function(date, format = 'YYYY-MM-DD') {
        if (!date) return '';
        
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        
        return format
            .replace('YYYY', year)
            .replace('MM', month)
            .replace('DD', day);
    },
    
    // Format currency
    formatCurrency: function(amount, currency = '₹') {
        return currency + parseFloat(amount).toLocaleString('en-IN');
    },
    
    // Confirm dialog
    confirm: function(message, callback) {
        if (confirm(message)) {
            if (typeof callback === 'function') {
                callback();
            }
        }
    },
    
    // Load data via AJAX
    ajax: function(url, options = {}) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaults, ...options };
        
        return fetch(url, config)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                this.showError('An error occurred while processing your request.');
                throw error;
            });
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Component functions
app.components = {
    // Initialize DataTable with custom options
    initDataTable: function(selector, options = {}) {
        const defaults = {
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            pageLength: 25,
            order: [[0, 'asc']]
        };
        
        const config = { ...defaults, ...options };
        return $(selector).DataTable(config);
    },
    
    // Initialize Select2 with custom options
    initSelect2: function(selector, options = {}) {
        const defaults = {
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select an option...',
            allowClear: true
        };
        
        const config = { ...defaults, ...options };
        return $(selector).select2(config);
    },
    
    // Initialize form validation
    initFormValidation: function(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    },
    
    // Initialize tooltips
    initTooltips: function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Initialize popovers
    initPopovers: function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    },
    
    // Initialize modals
    initModals: function() {
        const modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
        modalTriggerList.map(function (modalTriggerEl) {
            return new bootstrap.Modal(modalTriggerEl);
        });
    },
    
    // Show loading spinner
    showLoading: function(container) {
        const spinner = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading...</p>
            </div>
        `;
        container.innerHTML = spinner;
    },
    
    // Hide loading spinner
    hideLoading: function(container) {
        const spinner = container.querySelector('.spinner-border');
        if (spinner) {
            spinner.remove();
        }
    }
};

// Form handling
app.forms = {
    // Submit form via AJAX
    submitAjax: function(formSelector, successCallback, errorCallback) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        
        const formData = new FormData(form);
        const url = form.action || window.location.href;
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                app.utils.showSuccess(data.message || 'Operation completed successfully!');
                if (typeof successCallback === 'function') {
                    successCallback(data);
                }
            } else {
                app.utils.showError(data.message || 'Operation failed!');
                if (typeof errorCallback === 'function') {
                    errorCallback(data);
                }
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            app.utils.showError('An error occurred while submitting the form.');
            if (typeof errorCallback === 'function') {
                errorCallback(error);
            }
        });
    },
    
    // Reset form
    reset: function(formSelector) {
        const form = document.querySelector(formSelector);
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            
            // Reset Select2
            $(form).find('.select2').val(null).trigger('change');
        }
    },
    
    // Validate form
    validate: function(formSelector) {
        const form = document.querySelector(formSelector);
        if (!form) return false;
        
        form.classList.add('was-validated');
        return form.checkValidity();
    }
};

// Table handling
app.tables = {
    // Delete row with confirmation
    deleteRow: function(url, rowId, tableSelector) {
        app.utils.confirm('Are you sure you want to delete this item?', function() {
            app.utils.ajax(url, { method: 'DELETE' })
                .then(data => {
                    if (data.success) {
                        app.utils.showSuccess('Item deleted successfully!');
                        if (tableSelector) {
                            const table = $(tableSelector).DataTable();
                            table.row(`#${rowId}`).remove().draw();
                        }
                    } else {
                        app.utils.showError(data.message || 'Failed to delete item!');
                    }
                })
                .catch(error => {
                    app.utils.showError('An error occurred while deleting the item.');
                });
        });
    },
    
    // Refresh table
    refresh: function(tableSelector) {
        const table = $(tableSelector).DataTable();
        table.ajax.reload();
    }
};

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    app.components.initTooltips();
    app.components.initPopovers();
    app.components.initModals();
    
    // Initialize form validation for all forms
    document.querySelectorAll('form').forEach(form => {
        app.components.initFormValidation('#' + form.id);
    });
    
    // Add fade-in animation to cards
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('fade-in');
    });
    
    // Initialize Select2 for all select elements with class 'select2'
    if (typeof $ !== 'undefined') {
        app.components.initSelect2('.select2');
    }
    
    console.log('Kovil App - Modern Version initialized successfully!');
});

// Export for global use
window.KovilApp = app; 