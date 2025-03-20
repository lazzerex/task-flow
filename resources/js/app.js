/**
 * Task Manager Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Status filter with animation
    const statusFilter = document.querySelector('.status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const taskCards = document.querySelectorAll('.task-card');
            
            taskCards.forEach(card => {
                if (status === '' || card.dataset.status === status) {
                    card.style.display = 'block';
                    // Add a small fade-in animation
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transition = 'opacity 0.3s ease-in';
                    }, 10);
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update the counts in the progress section if it exists
            updateProgressCounts();
        });
    }
    
    // Task search with animation and highlighting
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const taskCards = document.querySelectorAll('.task-card');
                
                taskCards.forEach(card => {
                    const taskTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
                    const taskDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
                    
                    if (taskTitle.includes(searchTerm) || taskDescription.includes(searchTerm)) {
                        card.style.display = 'block';
                        // Add a small fade-in animation
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transition = 'opacity 0.3s ease-in';
                        }, 10);
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Update the counts in the progress section
                updateProgressCounts();
            }, 300); // Debounce for 300ms
        });
    }
    
    // Function to update progress counts when filtering
    function updateProgressCounts() {
        const visibleTasks = document.querySelectorAll('.task-card[style*="display: block"], .task-card:not([style*="display"])');
        const totalVisible = visibleTasks.length;
        
        const pendingCount = document.querySelectorAll('.task-card[data-status="pending"][style*="display: block"], .task-card[data-status="pending"]:not([style*="display"])').length;
        const inProgressCount = document.querySelectorAll('.task-card[data-status="in_progress"][style*="display: block"], .task-card[data-status="in_progress"]:not([style*="display"])').length;
        const completedCount = document.querySelectorAll('.task-card[data-status="completed"][style*="display: block"], .task-card[data-status="completed"]:not([style*="display"])').length;
        
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
    }
    
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
    const moonIcon = darkModeToggle.querySelector('.moon-icon');
    const sunIcon = darkModeToggle.querySelector('.sun-icon');
    
    // Function to set theme
    const setTheme = (isDark) => {
        if (isDark) {
            document.documentElement.classList.add('dark');
            darkModeToggle.classList.remove('light-theme');
            darkModeToggle.classList.add('dark-theme');
            moonIcon.classList.add('hidden');
            sunIcon.classList.remove('hidden');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            darkModeToggle.classList.add('light-theme');
            darkModeToggle.classList.remove('dark-theme');
            moonIcon.classList.remove('hidden');
            sunIcon.classList.add('hidden');
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
});