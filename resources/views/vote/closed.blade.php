@extends('layouts.ballot')

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 py-10">
        <div class="max-w-md w-full text-center">

            <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">Voting is Closed</h1>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <!-- With Icon (if needed) -->
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    {{ $voter->full_name }}
                </div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $election->title }}</h2>

                <p class="text-gray-600 mb-4">
                    @if ($election->status === 'closed')
                        This election has ended and voting is no longer available.
                    @elseif(now()->isBefore($election->starts_at))
                        Voting has not yet started for this election.
                    @else
                        Voting is currently not available.
                    @endif
                </p>

                <div class="text-sm text-gray-500 space-y-1">
                    <div>Starts: {{ $election->starts_at->format('M j, Y g:i A') }}</div>
                    <div>Ends: {{ $election->ends_at->format('M j, Y g:i A') }}</div>
                </div>
            </div>

            @if ($election->isClosed())
                <p class="text-sm text-gray-600 mb-4">
                    Results will be published by the election administrators.
                </p>
            @endif

            <a href="{{ route('home') }}" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 w-full">
                Return Home
            </a>
        </div>
    </div>
    @endsection
