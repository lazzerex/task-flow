/**
 * Task Filtering and Pagination Module
 */

export class TaskFiltering {
    constructor() {
        this.statusFilter = document.getElementById('statusFilter');
        this.searchInput = document.getElementById('searchInput');
        this.taskContent = document.getElementById('taskContent');
        this.paginationContent = document.getElementById('paginationContent');
        this.loadingIndicator = document.getElementById('loadingIndicator');
        this.searchTimeout = null;
        
        this.init();
    }

    init() {
        if (!this.statusFilter || !this.searchInput) {
            return; // Not on tasks index page
        }

        this.bindEvents();
    }

    bindEvents() {
        // Status filter change handler
        this.statusFilter.addEventListener('change', () => {
            this.performFilter(1); // Reset to page 1 when filtering
        });

        // Search input handler with debouncing
        this.searchInput.addEventListener('input', () => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.performFilter(1); // Reset to page 1 when searching
            }, 300);
        });

        // Handle pagination clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('pagination-link')) {
                e.preventDefault();
                
                // Extract page number from URL
                const url = new URL(e.target.href);
                const page = url.searchParams.get('page') || 1;
                
                this.performFilter(page);
                
                // Scroll to top of task content
                this.taskContent.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    showLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'flex';
        }
        if (this.taskContent) {
            this.taskContent.style.opacity = '0.5';
        }
    }

    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'none';
        }
        if (this.taskContent) {
            this.taskContent.style.opacity = '1';
        }
    }

    async performFilter(page = 1) {
        this.showLoading();
        
        try {
            const data = {
                status: this.statusFilter.value,
                search: this.searchInput.value,
                page: page
            };

            const response = await window.axios.post(window.taskFilterRoute, data, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.data.success) {
                this.updateContent(response.data);
            }
        } catch (error) {
            console.error('Error filtering tasks:', error);
            this.showError('Failed to filter tasks. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    updateContent(data) {
        if (this.taskContent) {
            this.taskContent.innerHTML = data.html;
        }
        
        if (this.paginationContent) {
            this.paginationContent.innerHTML = data.pagination;
        }
        
        // Smooth content transition
        if (this.taskContent) {
            this.taskContent.style.opacity = '0';
            setTimeout(() => {
                this.taskContent.style.opacity = '1';
                this.taskContent.style.transition = 'opacity 0.3s ease-in';
            }, 10);
        }
    }

    showError(message) {
        // You can implement a toast notification here
        // For now, just use console.error
        console.error(message);
        
        // Optional: Show a user-friendly error message
        if (window.TaskManager && window.TaskManager.showToast) {
            window.TaskManager.showToast(message, 'error');
        }
    }

    // Public method to trigger filtering programmatically
    filter(options = {}) {
        if (options.status !== undefined) {
            this.statusFilter.value = options.status;
        }
        if (options.search !== undefined) {
            this.searchInput.value = options.search;
        }
        this.performFilter(options.page || 1);
    }

    // Public method to reset filters
    resetFilters() {
        this.statusFilter.value = '';
        this.searchInput.value = '';
        this.performFilter(1);
    }
}