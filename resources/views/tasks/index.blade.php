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
        <div class="filter-group">
            <span class="filter-label">Filter by Status:</span>
            <select name="status" id="statusFilter" class="filter-select status-filter">
                <option value="">All Tasks</option>
                <option value="pending" {{ session('status_filter') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ session('status_filter') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ session('status_filter') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        
        <div class="search-container">
            <div class="search-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="search" name="search" id="searchInput" value="{{ request('search') }}" class="search-input" placeholder="Search tasks...">
        </div>
    </div>
</div>

<!-- loading thing -->
<div id="loadingIndicator" class="loading-indicator" style="display: none;">
    <div class="spinner"></div>
    <span>Loading tasks...</span>
</div>

<!-- task content container -->
<div id="taskContent">
    @include('tasks.partials.task-grid', ['tasks' => $tasks])
</div>

<!-- pagination container -->
<div id="paginationContent">
    @include('tasks.partials.pagination', ['tasks' => $tasks])
</div>

<script>
    window.taskFilterRoute = '{{ route("tasks.filter") }}';
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endsection