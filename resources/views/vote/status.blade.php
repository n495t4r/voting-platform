@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Voting Status</h1>
        <p class="text-gray-600">Track your participation in elections</p>
    </div>

    @forelse($voters as $voter)
        <div class="card-mobile mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $voter->election->title }}</h3>
                    <p class="text-gray-600 text-sm">{{ $voter->election->organization->name }}</p>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>{{ $voter->election->starts_at->format('M j, Y') }}</span>
                        <span>→</span>
                        <span>{{ $voter->election->ends_at->format('M j, Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($voter->status === 'invited') bg-gray-100 text-gray-800
                        @elseif($voter->status === 'verified') bg-blue-100 text-blue-800
                        @elseif($voter->status === 'voted') bg-green-100 text-green-800
                        @endif">
                        @if($voter->status === 'voted')
                            Voted
                        @elseif($voter->status === 'verified')
                            Verified
                        @else
                            Invited
                        @endif
                    </span>
                    
                    @if($voter->status === 'voted' && $voter->ballots->isNotEmpty())
                        <a href="{{ route('vote.receipt', $voter->ballots->first()->ballot_uid) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Receipt
                        </a>
                    @endif
                </div>
            </div>
            
            @if($voter->voted_at)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Voted on {{ $voter->voted_at->format('M j, Y g:i A') }}
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-12">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Elections Found</h3>
            <p class="text-gray-600">You haven't been invited to any elections yet.</p>
        </div>
    @endforelse
</div>
@endsection
