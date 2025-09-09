@extends('layouts.ballot')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 py-10">
    <div class="max-w-md w-full text-center mx-auto">
        <!-- Error Icon -->
        <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 mb-4">Unable to Access Voting</h1>

        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6 mb-6">
            <p class="text-gray-700">{{ $message }}</p>
        </div>

        @if($canRetry)
            <div class="space-y-3">
                <button onclick="window.location.reload()"
                        class="w-full py-3 px-6 rounded-full font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-lg transform hover:scale-105 active:scale-95">
                    Try Again
                </button>
                <p class="text-sm text-gray-500 mt-4">
                    If the problem persists, please contact the election administrator.
                </p>
            </div>
        @else
            <div class="space-y-3">
                <p class="text-sm text-gray-500">
                    Please contact the election administrator for assistance.
                </p>
                <a href="{{ route('home') }}"
                   class="w-full block text-center py-3 px-6 rounded-full font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors shadow-lg">
                    Return Home
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
