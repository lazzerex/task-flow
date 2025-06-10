@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="btn-new-task">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        New Task
    </a>
</div>

<!-- Task Filter Controls -->
<div class="filter-section">
    <div class="filter-controls">
        <form id="filterForm" class="filter-form">
            @csrf
            <span class="filter-label">Filter by Status:</span>
            <select name="status" id="statusFilter" class="filter-select status-filter">
                <option value="">All Tasks</option>
                <option value="pending" {{ session('status_filter') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ session('status_filter') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ session('status_filter') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </form>
        <div class="search-container">
            <form id="searchForm" class="search-form">
                <div class="search-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" name="search" id="searchInput" value="{{ request('search') }}" class="search-input" placeholder="Search tasks...">
            </form>
        </div>
    </div>
</div>

<!-- Loading indicator -->
<div id="loadingIndicator" class="loading-indicator" style="display: none;">
    <div class="spinner"></div>
    <span>Loading tasks...</span>
</div>

<!-- Task content container -->
<div id="taskContent">
    @if($tasks->isEmpty())
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        <h3>No tasks yet</h3>
        <p>Get started by creating your first task</p>
        <a href="{{ route('tasks.create') }}" class="btn-new-task">
            Create Task
        </a>
    </div>
    @else
    <div class="task-grid">
        @foreach($tasks as $task)
        <div class="task-card" data-status="{{ $task->status }}">
            <div class="task-card-header">
                <div class="task-card-title-row">
                    <h3 class="task-title">{{ $task->title }}</h3>
                    <span class="status-badge {{ $task->status == 'pending' ? 'status-pending' : ($task->status == 'in_progress' ? 'status-in-progress' : 'status-completed') }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>

                @if($task->description)
                <p class="task-description">{{ $task->description }}</p>
                @endif

                <div class="task-meta">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</span>
                </div>
            </div>

            <div class="task-card-footer">
                <div class="task-footer-content">
                    <div class="task-created-time">
                        Created {{ $task->created_at->diffForHumans() }}
                    </div>
                    <div class="task-actions">
                        <a href="{{ route('tasks.show', $task) }}" class="btn-action">
                            View
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-action">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Pagination container -->
<div id="paginationContent">
    @if(!$tasks->isEmpty())
    <div class="pagination">
        @if ($tasks->previousPageUrl())
            <a href="{{ $tasks->appends(request()->except('page'))->previousPageUrl() }}" class="pagination-btn pagination-link">
                « Previous
            </a>
        @else
            <span class="pagination-btn disabled">
                « Previous
            </span>
        @endif

        <span class="pagination-info">
            Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
        </span>

        @if ($tasks->nextPageUrl())
            <a href="{{ $tasks->appends(request()->except('page'))->nextPageUrl() }}" class="pagination-btn pagination-link">
                Next »
            </a>
        @else
            <span class="pagination-btn disabled">
                Next »
            </span>
        @endif
    </div>
    @endif
</div>

<style>
.loading-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    font-size: 1rem;
    color: #6b7280;
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const taskContent = document.getElementById('taskContent');
    const paginationContent = document.getElementById('paginationContent');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    let searchTimeout;

    // Function to show loading indicator
    function showLoading() {
        loadingIndicator.style.display = 'flex';
        taskContent.style.opacity = '0.5';
    }

    // Function to hide loading indicator
    function hideLoading() {
        loadingIndicator.style.display = 'none';
        taskContent.style.opacity = '1';
    }

    // Function to perform AJAX request
    function performFilter() {
        showLoading();
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('status', statusFilter.value);
        formData.append('search', searchInput.value);

        axios.post('{{ route("tasks.filter") }}', formData, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.data.success) {
                taskContent.innerHTML = response.data.html;
                paginationContent.innerHTML = response.data.pagination;
                
                taskContent.style.opacity = '0';
                setTimeout(() => {
                    taskContent.style.opacity = '1';
                    taskContent.style.transition = 'opacity 0.3s ease-in';
                }, 10);
            }
        })
        .catch(error => {
            console.error('Error filtering tasks:', error);
        })
        .finally(() => {
            hideLoading();
        });
    }

    // Status filter change handler
    statusFilter.addEventListener('change', performFilter);

    // Search input handler with debouncing
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performFilter, 300);
    });

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('pagination-link')) {
            e.preventDefault();
            showLoading();
            
            const url = e.target.href;
            const params = new URLSearchParams();
            params.append('status', statusFilter.value);
            params.append('search', searchInput.value);
            
            axios.get(url, {
                params: params,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.data.success) {
                    taskContent.innerHTML = response.data.html;
                    paginationContent.innerHTML = response.data.pagination;
                    
                    // Scroll to top of task content
                    taskContent.scrollIntoView({ behavior: 'smooth' });
                }
            })
            .catch(error => {
                console.error('Error loading page:', error);
            })
            .finally(() => {
                hideLoading();
            });
        }
    });
});
</script>
@endsection