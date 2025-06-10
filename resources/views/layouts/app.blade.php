<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Manage Your Tasks Efficiently</title>
    <!-- Custom Styles -->
    <link rel ="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-container">
        <header class="app-header">
            <div class="header-container">
                <div class="header-content">
                    <div class="header-left">
                        <div class="logo">
                            <a href="{{ route('tasks.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                TaskFlow
                            </a>
                        </div>
                        <nav class="main-nav">
                            <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.create') }}" class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                New Task
                            </a>
                        </nav>
                    </div>
                    
                    <div class="header-right">
                        @guest
                            <div class="guest-nav">
                                <a href="{{ route('login') }}" class="login-link">Login</a>
                                <a href="{{ route('register') }}" class="register-link">Register</a>
                            </div>
                        @else
                            <div class="user-menu">
                                <button type="button" id="user-menu-button" class="user-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <div id="user-dropdown" class="user-dropdown hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" role="menuitem">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="flash-message">
                <div class="message-content success">
                    <div class="message-inner">
                        <svg class="success-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="close-button" onclick="this.closest('.flash-message').style.display='none'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-message">
                <div class="message-content error">
                    <div class="message-inner">
                        <svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="close-button" onclick="this.closest('.flash-message').style.display='none'">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="app-footer">
            <div class="footer-container">
                <div class="footer-content">
                    <div class="footer-text">
                        &copy; {{ date('Y') }} TaskFlow. All rights reserved.
                    </div>
                    <div class="footer-text">
                        Built with Laravel
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Dark Mode Toggle -->
    <button id="darkModeToggle" class="dark-mode-toggle light-theme">
        <svg xmlns="http://www.w3.org/2000/svg" class="moon-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="sun-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    
    <!-- Application Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                window.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            // Confetti animation for task completion
            const successMessage = document.querySelector('.message-content.success span');
            if (successMessage && successMessage.textContent.includes('completed')) {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }

            // Status change animation
            const statusDropdowns = document.querySelectorAll('select[name="status"]');
            statusDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', function() {
                    if (this.value === 'completed') {
                        this.classList.add('status-change');
                        setTimeout(() => {
                            this.classList.remove('status-change');
                        }, 500);
                    }
                });
            });

            // Search functionality with debounce
            const searchInput = document.querySelector('input[type="search"]');
            const searchForm = document.querySelector('.search-form');
            
            if (searchInput && searchForm) {
                let searchTimeout;
                
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }
        });

        // Dark mode functionality
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            
            const moonIcon = document.querySelector('.moon-icon');
            const sunIcon = document.querySelector('.sun-icon');
            
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
                this.classList.remove('light-theme');
                this.classList.add('dark-theme');
            } else {
                localStorage.theme = 'light';
                moonIcon.classList.remove('hidden');
                sunIcon.classList.add('hidden');
                this.classList.remove('dark-theme');
                this.classList.add('light-theme');
            }
        });
        
        // Initialize dark mode on page load
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.querySelector('.moon-icon').classList.add('hidden');
            document.querySelector('.sun-icon').classList.remove('hidden');
            document.getElementById('darkModeToggle').classList.remove('light-theme');
            document.getElementById('darkModeToggle').classList.add('dark-theme');
        }
    </script>
</body>
</html>