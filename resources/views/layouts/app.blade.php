<!DOCTYPE html>
<html lang="en" class="">
<!-- The 'dark' class will be added via JavaScript -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Manage Your Tasks Efficiently</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">
    <div class="flex flex-col min-h-screen">
        <header class="bg-gradient-to-r from-primary-600 to-primary-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('tasks.index') }}" class="text-2xl font-bold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                TaskFlow
                            </a>
                        </div>
                        <nav class="ml-6 flex space-x-4 items-center">
                            <a href="{{ route('tasks.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tasks.index') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition-colors">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.create') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tasks.create') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-700 hover:text-white' }} transition-colors">
                                New Task
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 alert-animate">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-md shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="block sm:inline pr-8">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 right-0 p-3" onclick="this.parentElement.parentElement.style.display='none'">
                            <svg class="h-4 w-4 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 alert-animate">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-md shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="block sm:inline pr-8">{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 right-0 p-3" onclick="this.parentElement.parentElement.style.display='none'">
                            <svg class="h-4 w-4 text-red-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} TaskFlow. All rights reserved.
                    </div>
                    <div class="text-sm text-gray-500">
                        Built with Laravel
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    
    <!-- Confetti Animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a success message about task completion
            const successMessage = document.querySelector('[role="alert"] span');
            if (successMessage && successMessage.textContent.includes('completed')) {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }

            // For status changes to completed
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
        });
    </script>
    <!-- Add this just before the closing </body> tag -->
<button id="darkModeToggle" class="dark-mode-toggle light-theme">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 moon-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sun-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</button>
<script>
    // Simple inline dark mode toggle
    document.getElementById('darkModeToggle').addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        
        if (document.documentElement.classList.contains('dark')) {
            localStorage.theme = 'dark';
            document.querySelector('.moon-icon').classList.add('hidden');
            document.querySelector('.sun-icon').classList.remove('hidden');
            this.classList.remove('bg-blue-600');
            this.classList.add('bg-yellow-500', 'text-gray-900');
        } else {
            localStorage.theme = 'light';
            document.querySelector('.moon-icon').classList.remove('hidden');
            document.querySelector('.sun-icon').classList.add('hidden');
            this.classList.add('bg-blue-600', 'text-white');
            this.classList.remove('bg-yellow-500', 'text-gray-900');
        }
    });
    
    // On page load
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        document.querySelector('.moon-icon').classList.add('hidden');
        document.querySelector('.sun-icon').classList.remove('hidden');
        document.getElementById('darkModeToggle').classList.remove('bg-blue-600');
        document.getElementById('darkModeToggle').classList.add('bg-yellow-500', 'text-gray-900');
    }
</script>
<script>
    // Task search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            console.log('Search input found');
            
            searchInput.addEventListener('input', function() {
                console.log('Search input changed:', this.value);
                const searchTerm = this.value.toLowerCase();
                const taskCards = document.querySelectorAll('.task-card');
                
                taskCards.forEach(card => {
                    const taskTitle = card.querySelector('h3')?.textContent.toLowerCase() || '';
                    const taskDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
                    
                    if (taskTitle.includes(searchTerm) || taskDescription.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Update counter if progress bars exist
                updateProgressCounters();
            });
            
           
        }
        
        // Function to update progress counters
        function updateProgressCounters() {
            // Get only visible tasks
            const visibleTasks = document.querySelectorAll('.task-card[style*="display: block"], .task-card:not([style*="display: none"])');
            const totalVisible = visibleTasks.length;
            
            // Update the counters if they exist
            const pendingCounter = document.querySelector('.progress-pending .counter');
            const inProgressCounter = document.querySelector('.progress-in-progress .counter');
            const completedCounter = document.querySelector('.progress-completed .counter');
            
            if (pendingCounter) {
                const pendingCount = document.querySelectorAll('.task-card[data-status="pending"][style*="display: block"], .task-card[data-status="pending"]:not([style*="display: none"])').length;
                pendingCounter.textContent = pendingCount;
                
                // Update progress bar if it exists
                const pendingBar = document.querySelector('.progress-pending .bar-fill');
                if (pendingBar && totalVisible > 0) {
                    pendingBar.style.width = `${(pendingCount / totalVisible * 100)}%`;
                }
            }
            
            if (inProgressCounter) {
                const inProgressCount = document.querySelectorAll('.task-card[data-status="in_progress"][style*="display: block"], .task-card[data-status="in_progress"]:not([style*="display: none"])').length;
                inProgressCounter.textContent = inProgressCount;
                
                // Update progress bar if it exists
                const inProgressBar = document.querySelector('.progress-in-progress .bar-fill');
                if (inProgressBar && totalVisible > 0) {
                    inProgressBar.style.width = `${(inProgressCount / totalVisible * 100)}%`;
                }
            }
            
            if (completedCounter) {
                const completedCount = document.querySelectorAll('.task-card[data-status="completed"][style*="display: block"], .task-card[data-status="completed"]:not([style*="display: none"])').length;
                completedCounter.textContent = completedCount;
                
                // Update progress bar if it exists
                const completedBar = document.querySelector('.progress-completed .bar-fill');
                if (completedBar && totalVisible > 0) {
                    completedBar.style.width = `${(completedCount / totalVisible * 100)}%`;
                }
            }
        }
    });
</script>
</body>
</html>