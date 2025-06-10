@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/task-show.css') }}">
@endpush

@section('content')
    <div class="task-container">
        <div class="task-header">
            <div>
                <h1 class="task-title">{{ $task->title }}</h1>
                <p class="task-meta">
                    Created {{ $task->created_at->format('M d, Y') }} at {{ $task->created_at->format('h:i A') }}
                </p>
            </div>
            <div class="button-group">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-accent">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="task-card">
            <div class="task-content">
                <div class="task-status-row">
                    <span class="status-badge {{ $task->status == 'pending' ? 'status-pending' : ($task->status == 'in_progress' ? 'status-in-progress' : 'status-completed') }}">
                        <svg class="status-icon" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    
                    @if($task->due_date)
                        <div class="due-date {{ $task->due_date->isPast() ? 'due-date-overdue' : 'due-date-normal' }}">
                            <svg class="due-date-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="due-date-text">
                                Due {{ $task->due_date->format('M d, Y') }}
                                @if($task->due_date->isPast()) 
                                    <span class="overdue-text">(Overdue!)</span>
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="due-date due-date-normal">
                            <svg class="due-date-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>No due date set</span>
                        </div>
                    @endif
                </div>

                <div class="description-section">
                    <h3 class="section-title">Description</h3>
                    <div class="description-box">
                        @if($task->description)
                            <p class="description-text">{{ $task->description }}</p>
                        @else
                            <p class="description-empty">No description provided.</p>
                        @endif
                    </div>
                </div>

                <div class="timeline-section">
                    <h3 class="timeline-title">Activity Timeline</h3>
                    <div class="timeline-flow">
                        <ul class="timeline-list" role="list">
                            <li class="timeline-item">
                                <div class="timeline-content">
                                    <span class="timeline-connector" aria-hidden="true"></span>
                                    <div class="timeline-icon-wrapper">
                                        <div class="timeline-icon timeline-icon-created">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="timeline-text">
                                        <div class="timeline-action">
                                            <span class="timeline-action-name">Task created</span>
                                        </div>
                                        <div class="timeline-date">
                                            <time datetime="{{ $task->created_at->format('Y-m-d') }}">{{ $task->created_at->format('M d, Y') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-icon-wrapper">
                                        <div class="timeline-icon timeline-icon-updated">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="timeline-text">
                                        <div class="timeline-action">
                                            <span class="timeline-action-name">Last updated</span>
                                        </div>
                                        <div class="timeline-date">
                                            <time datetime="{{ $task->updated_at->format('Y-m-d') }}">{{ $task->updated_at->format('M d, Y') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="task-footer">
                <div class="footer-actions">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        Back to Tasks
                    </a>
                    <div class="footer-button-group">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                            Edit Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection