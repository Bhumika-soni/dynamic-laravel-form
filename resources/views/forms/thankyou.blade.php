@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto py-20 px-4 sm:px-6 lg:px-8 text-center">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden p-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Thank You!</h2>
        <p class="text-lg text-gray-600 mb-6">Your form has been submitted successfully.</p>
        
    </div>
</div>
@endsection
