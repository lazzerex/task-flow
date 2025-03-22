@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-6 py-4">
        <h1 class="text-xl font-bold text-white">Login to TaskFlow</h1>
    </div>
    
    <form method="POST" action="{{ route('login.submit') }}" class="px-6 py-4 space-y-4">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center">
            <input class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="ml-2 block text-sm text-gray-700" for="remember">
                Remember Me
            </label>
        </div>
        
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Login
            </button>
        </div>
        
        <div class="text-center text-sm text-gray-500">
            Don't have an account? <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">Register here</a>
        </div>
    </form>
</div>
@endsection