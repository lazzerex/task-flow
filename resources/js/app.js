/**
 * Task Manager Main JavaScript
 */
import './bootstrap.js';

document.addEventListener('DOMContentLoaded', function() {
    // Animate flash messages
    const flashMessages = document.querySelectorAll('[role="alert"]');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease-in-out';
            setTimeout(() => {
                message.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    // Handle task completion animation
    const statusDropdowns = document.querySelectorAll('select[name="status"]');
    statusDropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            if (this.value === 'completed') {
                // Add a small animation to the select element
                this.classList.add('status-change');
                setTimeout(() => {
                    this.classList.remove('status-change');
                }, 500);
            }
        });
    });
    
    // Quick action buttons animation
    const quickActionButtons = document.querySelectorAll('.task-card form button');
    quickActionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add a pulse animation to the parent card
            const card = this.closest('.task-card');
            if (card) {
                card.classList.add('animate-pulse-slow');
                setTimeout(() => {
                    card.classList.remove('animate-pulse-slow');
                }, 1000);
            }
        });
    });
    
    // Enhance the delete confirmation with a custom dialog
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            if (form) {
                if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
                    form.submit();
                }
            }
        });
    });
});

// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or use user's system preference
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if (darkModeToggle) {
        const moonIcon = darkModeToggle.querySelector('.moon-icon');
        const sunIcon = darkModeToggle.querySelector('.sun-icon');
        
        // Function to set theme
        const setTheme = (isDark) => {
            if (isDark) {
                document.documentElement.classList.add('dark');
                darkModeToggle.classList.remove('light-theme');
                darkModeToggle.classList.add('dark-theme');
                if (moonIcon) moonIcon.classList.add('hidden');
                if (sunIcon) sunIcon.classList.remove('hidden');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                darkModeToggle.classList.add('light-theme');
                darkModeToggle.classList.remove('dark-theme');
                if (moonIcon) moonIcon.classList.remove('hidden');
                if (sunIcon) sunIcon.classList.add('hidden');
                localStorage.theme = 'light';
            }
        };
        
        // Check for saved theme preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            setTheme(true);
        } else {
            setTheme(false);
        }
        
        // Toggle theme when button is clicked
        darkModeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            setTheme(!isDark);
        });
    }
});

// Global utility functions for task management
window.TaskManager = {
    // Function to update progress counts (if you have progress bars)
    updateProgressCounts: function() {
        const visibleTasks = document.querySelectorAll('.task-card');
        const totalVisible = visibleTasks.length;
        
        const pendingCount = document.querySelectorAll('.task-card[data-status="pending"]').length;
        const inProgressCount = document.querySelectorAll('.task-card[data-status="in_progress"]').length;
        const completedCount = document.querySelectorAll('.task-card[data-status="completed"]').length;
        
        // Update the progress bars if they exist
        const pendingCounter = document.querySelector('.progress-pending .counter');
        const inProgressCounter = document.querySelector('.progress-in-progress .counter');
        const completedCounter = document.querySelector('.progress-completed .counter');
        
        if (pendingCounter) pendingCounter.textContent = pendingCount;
        if (inProgressCounter) inProgressCounter.textContent = inProgressCount;
        if (completedCounter) completedCounter.textContent = completedCount;
        
        // Update progress bar widths
        const pendingBar = document.querySelector('.progress-pending .bar-fill');
        const inProgressBar = document.querySelector('.progress-in-progress .bar-fill');
        const completedBar = document.querySelector('.progress-completed .bar-fill');
        
        if (pendingBar && totalVisible > 0) pendingBar.style.width = `${(pendingCount / totalVisible * 100)}%`;
        if (inProgressBar && totalVisible > 0) inProgressBar.style.width = `${(inProgressCount / totalVisible * 100)}%`;
        if (completedBar && totalVisible > 0) completedBar.style.width = `${(completedCount / totalVisible * 100)}%`;
    },
    
    // Function to show toast notifications
    showToast: function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        // Add toast styles if not already present
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        
        document.querySelector('.toast-container').appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
};