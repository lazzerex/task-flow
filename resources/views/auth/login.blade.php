@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h1>Login to TaskFlow</h1>
    </div>
    
    <form method="POST" action="{{ route('login.submit') }}" class="auth-form">
        @csrf
        
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
                autofocus
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
                autocomplete="current-password"
            >
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="checkbox-group">
            <input 
                class="checkbox-input" 
                type="checkbox" 
                name="remember" 
                id="remember" 
                {{ old('remember') ? 'checked' : '' }}
            >
            <label class="checkbox-label" for="remember">
                Remember Me
            </label>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-primary">
                Login
            </button>
        </div>
        
        <div class="auth-link-container">
            Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register here</a>
        </div>
    </form>
</div>
@endsection