@extends('layouts.ballot')

@section('content')

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h1 class="text-xl font-bold text-gray-900">{{ $election->title }}</h1>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-gray-700">Vote Receipt</p>
        </div>
    </div>
</header>

<div class="min-h-screen flex items-center justify-center p-4 py-10">
    <div class="max-w-md w-full mx-auto">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900">Vote Submitted!</h1>
            <p class="text-gray-600 mt-2">Your vote has been recorded successfully</p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-3">
                <h2 class="text-lg font-bold text-gray-900">Vote Receipt</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-sm font-medium text-gray-700 shadow-sm">
                    {{ $ballot->voter->full_name ?? 'Anonymous' }}
                </span>
            </div>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Election:</span>
                    <span class="font-medium text-gray-900 text-right">{{ $election->title }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Receipt ID:</span>
                    <span class="font-mono text-gray-900 text-right break-all">{{ $ballot->ballot_uid }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Submitted:</span>
                    <span class="text-gray-900 text-right">{{ $ballot->submitted_at->format('M j, Y g:i A') }}</span>
                </div>

                @if($ballot->revision > 1)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Revision:</span>
                        <span class="text-gray-900 text-right">#{{ $ballot->revision }}</span>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-200 pt-4 mt-4">
                <p class="text-xs text-gray-500">
                    This receipt confirms your vote was recorded. Keep this for your records.
                    Your individual selections remain private and anonymous.
                </p>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-3 space-y-3 no-print">
            <button onclick="window.print()"
                class="inline-flex items-center px-3 py-1 rounded-full text-xs text-gray-500 bg-gray-100">
                Print Receipt
            </button>

            @auth
                <a href="{{ route('voter.dashboard') }}"
                   class="w-full block text-center py-3 px-6 rounded-full font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors shadow-lg">
                    Back to Dashboard
                </a>
            @endauth
        </div>

        <!-- Election Info -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Election ends: {{ $election->ends_at->format('M j, Y g:i A') }}
            </p>
            @if($election->isClosed())
                <p class="text-sm text-green-600 font-medium mt-1">
                    Election has ended. Results will be available soon.
                </p>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white; }
        .no-print { display: none; }
    }
</style>
@endsection
