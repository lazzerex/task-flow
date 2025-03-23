@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">My Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        New Task
    </a>
</div>

<!-- Task Filter Controls (Optional) -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
    <form action="{{ route('tasks.filter') }}" method="POST" class="flex items-center">
    @csrf
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Filter by Status:</span>
    <select name="status" class="status-filter rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onchange="this.form.submit()">
        <option value="">All Tasks</option>
        <option value="pending" {{ session('status_filter') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="in_progress" {{ session('status_filter') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="completed" {{ session('status_filter') === 'completed' ? 'selected' : '' }}>Completed</option>
    </select>
</form>
<div class="relative flex-grow max-w-sm">
    <form action="{{ route('tasks.index') }}" method="GET" class="search-form">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="search" name="search" value="{{ request('search') }}" class="pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full" placeholder="Search tasks...">
    </form>
</div>
    </div>
</div>

@if($tasks->isEmpty())
<div class="bg-white rounded-lg shadow-sm p-6 text-center">
    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks yet</h3>
    <p class="text-gray-500 mb-4">Get started by creating your first task</p>
    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
        Create Task
    </a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($tasks as $task)

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-lg transition-all duration-300 task-card" data-status="{{ $task->status }}">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-medium text-gray-900 truncate mb-1">{{ $task->title }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $task->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>

            @if($task->description)
            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $task->description }}</p>
            @endif

            <div class="mt-4 flex items-center text-sm text-gray-500">
                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</span>
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-xs text-gray-500">
                    Created {{ $task->created_at->diffForHumans() }}
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        View
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<!---------------------------------------Pagination---------------------------------------------------------------->

<div class="mt-8 flex items-center justify-between">
    @if ($tasks->previousPageUrl())
        <a href="{{ $tasks->appends(request()->except('page'))->previousPageUrl() }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            « Previous
        </a>
    @else
        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-400 dark:text-gray-500 cursor-not-allowed">
            « Previous
        </span>
    @endif

    <span class="text-sm text-gray-700 dark:text-gray-300">
        Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
    </span>

    @if ($tasks->nextPageUrl())
        <a href="{{ $tasks->appends(request()->except('page'))->nextPageUrl() }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Next »
        </a>
    @else
        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-400 dark:text-gray-500 cursor-not-allowed">
            Next »
        </span>
    @endif
</div>


<!---------------------------------------Pagination---------------------------------------------------------------->



</div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.querySelector('.status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const status = this.value;
                const taskCards = document.querySelectorAll('.task-card');

                taskCards.forEach(card => {
                    if (status === '' || card.dataset.status === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection