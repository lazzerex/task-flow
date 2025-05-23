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

        // Apply status filter if set in session
        $statusFilter = session('status_filter');
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

        return view('tasks.index', compact('tasks'));
    }

    //new filter function
    public function filter(Request $request)
    {
        session(['status_filter' => $request->status]);
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Simply return the view containing the task creation form
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
        //
        // Pass the task to the view
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
        // Pass the task to the edit form view
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
}
