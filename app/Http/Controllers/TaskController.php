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
        // Get current page
        $currentPage = $request->input('page', 1);
        
        // Get status filter from request or session
        $statusFilter = $request->input('status') ?? session('status_filter');
        
        // Build query with all filters
        $allTasks = Task::latest()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($statusFilter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->select('id', 'title', 'description', 'status', 'due_date', 'created_at')
            ->orderBy('created_at', 'desc');

            
        $perPage = 9;
        $tasks = $allTasks->paginate($perPage, ['*'], 'page', $currentPage)
                          ->withQueryString();

        // ajax request , return json response
        if ($request->ajax()) {
            $taskGridHtml = view('tasks.partials.task-grid', compact('tasks'))->render();
            $paginationHtml = view('tasks.partials.pagination', compact('tasks'))->render();
            
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
        $validated = $this->validateTask($request);

        Task::create($validated);

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
        $validated = $this->validateTask($request);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }



    /**
     * Validate task data
     */
    private function validateTask(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);
    }
}