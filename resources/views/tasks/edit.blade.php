@extends('layouts.app')

@section('content')
<div class="edit-container">
  <div class="header-section">
    <div class="header-content">
      <h1 class="page-title">Edit Task</h1>
      <p class="page-subtitle">Update task details</p>
    </div>
    <div class="header-actions">
      <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">
        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
        </svg>
        Back to Task
      </a>
    </div>
  </div>

  <div class="form-card">
    <form action="{{ route('tasks.update', $task) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-content">
        <div class="form-grid">
          <!-- Task Title -->
          <div class="form-field full-width">
            <label for="title" class="form-label">Task Title</label>
            <input type="text" name="title" id="title"
              class="form-input {{ $errors->has('title') ? 'error' : '' }}"
              value="{{ old('title', $task->title) }}" required>
            @error('title')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>

          <!-- Description -->
          <div class="form-field full-width">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4"
              class="form-textarea {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $task->description) }}</textarea>
            @error('description')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>

          <!-- Status -->
          <div class="form-field half-width">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
              <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
              <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>

          <!-- Due Date -->
          <div class="form-field half-width">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-input"
              value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
            @error('due_date')
              <p class="error-message">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="form-footer">
        <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary hover-scale">Update Task</button>
      </div>
    </form>
  </div>
</div>
@endsection