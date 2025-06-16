@if($tasks->isEmpty())
    @include('tasks.partials.empty-state')
@else
    <div class="task-grid">
        @foreach($tasks as $task)
            @php
                $statusClass = $task->status == 'pending' ? 'status-pending' : 
                              ($task->status == 'in_progress' ? 'status-in-progress' : 'status-completed');
                $dueDate = $task->due_date ? $task->due_date->format('M d, Y') : 'No due date';
                $createdTime = $task->created_at->diffForHumans();
            @endphp
            
            <div class="task-card" data-status="{{ $task->status }}">
                <div class="task-card-header">
                    <div class="task-card-title-row">
                        <h3 class="task-title">{{ $task->title }}</h3>
                        <span class="status-badge {{ $statusClass }}">
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
                        <span>{{ $dueDate }}</span>
                    </div>
                </div>
                
                <div class="task-card-footer">
                    <div class="task-footer-content">
                        <div class="task-created-time">Created {{ $createdTime }}</div>
                        <div class="task-actions">
                            <a href="{{ route('tasks.show', $task) }}" class="btn-action">View</a>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn-action">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif