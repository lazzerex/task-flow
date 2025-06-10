@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h1>Create a TaskFlow Account</h1>
    </div>
    
    <form method="POST" action="{{ route('register.submit') }}" class="auth-form">
        @csrf
        
        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input 
                id="name" 
                type="text" 
                class="form-input @error('name') error @enderror" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autocomplete="name" 
                autofocus
            >
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input 
                id="email" 
                type="email" 
                class="form-input @error('email') error @enderror" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email"
            >
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input 
                id="password" 
                type="password" 
                class="form-input @error('password') error @enderror" 
                name="password" 
                required 
                autocomplete="new-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password-confirm" class="form-label">Confirm Password</label>
            <input 
                id="password-confirm" 
                type="password" 
                class="form-input" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
            >
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-primary">
                Register
            </button>
        </div>
        
        <div class="auth-link-container">
            Already have an account? <a href="{{ route('login') }}" class="auth-link">Login here</a>
        </div>
    </form>
</div>
@endsection