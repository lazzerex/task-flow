@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-6 py-4">
        <h1 class="text-xl font-bold text-white">Create a TaskFlow Account</h1>
    </div>
    
    <form method="POST" action="{{ route('register.submit') }}" class="px-6 py-4 space-y-4">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="password-confirm" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" name="password_confirmation" required autocomplete="new-password">
        </div>
        
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Register
            </button>
        </div>
        
        <div class="text-center text-sm text-gray-500">
            Already have an account? <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Login here</a>
        </div>
    </form>
</div>
@endsection