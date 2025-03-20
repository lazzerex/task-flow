@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Created {{ $task->created_at->format('M d, Y') }} at {{ $task->created_at->format('h:i A') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-accent-300 rounded-md shadow-sm text-sm font-medium text-accent-700 bg-white hover:bg-accent-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-accent-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white shadow-soft overflow-hidden rounded-xl border-2 border-gray-200 hover:border-primary-300 transition-colors">
            <div class="px-6 py-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $task->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-green-100 text-green-800 border border-green-300') }} transform transition hover:scale-105">
                        <svg class="mr-1.5 h-3 w-3 {{ $task->status == 'pending' ? 'text-yellow-400' : ($task->status == 'in_progress' ? 'text-blue-400' : 'text-green-400') }}" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    
                    @if($task->due_date)
                        <div class="flex items-center text-sm {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 {{ $task->due_date->isPast() ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium">
                                Due {{ $task->due_date->format('M d, Y') }}
                                @if($task->due_date->isPast()) 
                                    <span class="font-bold">(Overdue!)</span>
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>No due date set</span>
                        </div>
                    @endif
                </div>

                <div class="prose max-w-none mb-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Description</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        @if($task->description)
                            <p class="text-gray-800 whitespace-pre-line">{{ $task->description }}</p>
                        @else
                            <p class="text-gray-500 italic">No description provided.</p>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Activity Timeline</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-5 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                                <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1 py-0">
                                            <div class="text-md text-gray-500">
                                                <span class="font-medium text-gray-900">Task created</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <time datetime="{{ $task->created_at->format('Y-m-d') }}">{{ $task->created_at->format('M d, Y') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex items-start space-x-3">
                                        <div>
                                            <div class="relative px-1">
                                                <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1 py-0">
                                            <div class="text-md text-gray-500">
                                                <span class="font-medium text-gray-900">Last updated</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <time datetime="{{ $task->updated_at->format('Y-m-d') }}">{{ $task->updated_at->format('M d, Y') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between">
                    <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Back to Tasks
                    </a>
                    <div class="flex space-x-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            Edit Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection