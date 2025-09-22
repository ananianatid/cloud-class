// Alpine.js
document.addEventListener('alpine:init', () => {
    // Alpine is already initialized
});

// Axios will be loaded via CDN in the layout

// Basic Alpine.js functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any Alpine components
    if (window.Alpine) {
        window.Alpine.start();
    }
});

// Form handling
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
    const forms = document.querySelectorAll('form[data-ajax]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = this.action;
            const method = this.method || 'POST';

            axios({
                method: method,
                url: url,
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            })
            .then(response => {
                // Handle success
                if (response.data.redirect) {
                    window.location.href = response.data.redirect;
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                // Handle error
                console.error('Error:', error);
                if (error.response && error.response.data.message) {
                    alert(error.response.data.message);
                }
            });
        });
    });
});

// Utility functions
window.showAlert = function(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} p-4 rounded mb-4`;
    alertDiv.textContent = message;

    const container = document.querySelector('.container, .max-w-7xl, body');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
};

// CSRF token setup
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
