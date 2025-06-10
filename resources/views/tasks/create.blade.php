@extends('layouts.app')

@section('content')
<div class="app-container">
  <main class="main-content">
    <div class="form-container">
      <h2 class="form-title">Create New Task</h2>

      @if ($errors->any())
        <div class="error-container">
          <ul class="error-list">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('tasks.store') }}" class="task-form">
        @csrf
  
        <div class="form-group">
          <label for="title" class="form-label">Title</label>
          <input type="text" name="title" id="title" class="form-input" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
          <label for="status" class="form-label">Status</label>
          <select name="status" id="status" class="form-select">
            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          </select>
        </div>

        <div class="form-actions">
          <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </main>
</div>
@endsection