<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all tasks first
        $allTasks = Task::latest();

        // Apply search filter if provided
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $allTasks->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $allTasks = $allTasks->get();

        // Get current page
        $currentPage = request()->input('page', 1);

        // Apply status filter from request or session
        $statusFilter = $request->input('status') ?? session('status_filter');
        if ($statusFilter) {
            $filteredTasks = $allTasks->filter(function ($task) use ($statusFilter) {
                return $task->status === $statusFilter;
            });
        } else {
            $filteredTasks = $allTasks;
        }

        // Manually paginate the filtered results
        $perPage = 9;
        $tasks = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredTasks->forPage($currentPage, $perPage),
            $filteredTasks->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // If it's an AJAX request, return JSON response
        if ($request->ajax()) {
            $taskGridHtml = $this->renderTaskGrid($tasks);
            $paginationHtml = $this->renderPagination($tasks, $request);
            
            return response()->json([
                'success' => true,
                'html' => $taskGridHtml,
                'pagination' => $paginationHtml,
                'isEmpty' => $tasks->isEmpty()
            ]);
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Filter tasks via AJAX
     */
    public function filter(Request $request)
    {
        // Store filter in session
        session(['status_filter' => $request->status]);
        
        // If it's an AJAX request, return filtered results
        if ($request->ajax()) {
            return $this->index($request);
        }
        
        // Fallback for non-AJAX requests
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        // Create a new task with the validated data
        Task::create($validated);

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        // Update the task with the validated data
        $task->update($validated);

        // Redirect to the task's detail page with a success message
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Delete the task
        $task->delete();

        // Redirect to the tasks index page with a success message
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /*----------------looks cool so keep----------*/ 
    /**
     * Render task grid HTML for AJAX responses
     */
    private function renderTaskGrid($tasks)
    {
        if ($tasks->isEmpty()) {
            return '<div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3>No tasks found</h3>
                <p>Try adjusting your search or filter criteria</p>
                <a href="' . route('tasks.create') . '" class="btn-new-task">Create Task</a>
            </div>';
        }

        $html = '<div class="task-grid">';
        foreach ($tasks as $task) {
            $statusClass = $task->status == 'pending' ? 'status-pending' : 
                          ($task->status == 'in_progress' ? 'status-in-progress' : 'status-completed');
            
            $dueDate = $task->due_date ? $task->due_date->format('M d, Y') : 'No due date';
            $createdTime = $task->created_at->diffForHumans();
            
            $html .= '<div class="task-card" data-status="' . $task->status . '">
                <div class="task-card-header">
                    <div class="task-card-title-row">
                        <h3 class="task-title">' . e($task->title) . '</h3>
                        <span class="status-badge ' . $statusClass . '">
                            ' . ucfirst(str_replace('_', ' ', $task->status)) . '
                        </span>
                    </div>';
            
            if ($task->description) {
                $html .= '<p class="task-description">' . e($task->description) . '</p>';
            }
            
            $html .= '<div class="task-meta">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>' . $dueDate . '</span>
                    </div>
                </div>
                <div class="task-card-footer">
                    <div class="task-footer-content">
                        <div class="task-created-time">Created ' . $createdTime . '</div>
                        <div class="task-actions">
                            <a href="' . route('tasks.show', $task) . '" class="btn-action">View</a>
                            <a href="' . route('tasks.edit', $task) . '" class="btn-action">Edit</a>
                        </div>
                    </div>
                </div>
            </div>';
        }
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render pagination HTML for AJAX responses
     */
    private function renderPagination($tasks, $request = null)
    {
        if ($tasks->isEmpty()) {
            return '';
        }

        // Get current filter parameters
        $currentParams = [];
        if ($request) {
            if ($request->has('status') && $request->input('status')) {
                $currentParams['status'] = $request->input('status');
            }
            if ($request->has('search') && $request->input('search')) {
                $currentParams['search'] = $request->input('search');
            }
        }

        $html = '<div class="pagination">';
        
        if ($tasks->previousPageUrl()) {
            $prevParams = array_merge($currentParams, ['page' => $tasks->currentPage() - 1]);
            $prevUrl = route('tasks.index', $prevParams);
            $html .= '<a href="' . $prevUrl . '" class="pagination-btn pagination-link">« Previous</a>';
        } else {
            $html .= '<span class="pagination-btn disabled">« Previous</span>';
        }
        
        $html .= '<span class="pagination-info">Page ' . $tasks->currentPage() . ' of ' . $tasks->lastPage() . '</span>';
        
        if ($tasks->nextPageUrl()) {
            $nextParams = array_merge($currentParams, ['page' => $tasks->currentPage() + 1]);
            $nextUrl = route('tasks.index', $nextParams);
            $html .= '<a href="' . $nextUrl . '" class="pagination-btn pagination-link">Next »</a>';
        } else {
            $html .= '<span class="pagination-btn disabled">Next »</span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}