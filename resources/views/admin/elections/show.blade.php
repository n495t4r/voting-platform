@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
     Header 
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $election->title }}</h1>
            <p class="text-gray-600">{{ $election->organization->name }}</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($election->status === 'draft') bg-gray-100 text-gray-800
                    @elseif($election->status === 'scheduled') bg-blue-100 text-blue-800
                    @elseif($election->status === 'open') bg-green-100 text-green-800
                    @elseif($election->status === 'closed') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($election->status) }}
                </span>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            @if($election->isDraft())
                <form method="POST" action="{{ route('admin.elections.open', $election) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-mobile bg-green-600 text-white hover:bg-green-700">
                        Open Election
                    </button>
                </form>
            @elseif($election->isOpen())
                <form method="POST" action="{{ route('admin.elections.close', $election) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-mobile bg-red-600 text-white hover:bg-red-700"
                            onclick="return confirm('Are you sure you want to close this election?')">
                        Close Election
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.elections.edit', $election) }}" 
               class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 text-center">
                Edit
            </a>
        </div>
    </div>

     Stats Cards 
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card-mobile text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $turnoutStats['total_voters'] }}</div>
            <div class="text-sm text-gray-600">Total Voters</div>
        </div>
        <div class="card-mobile text-center">
            <div class="text-2xl font-bold text-green-600">{{ $turnoutStats['voted_count'] }}</div>
            <div class="text-sm text-gray-600">Votes Cast</div>
        </div>
        <div class="card-mobile text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $turnoutStats['turnout_percentage'] }}%</div>
            <div class="text-sm text-gray-600">Turnout</div>
        </div>
        <div class="card-mobile text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $election->positions->count() }}</div>
            <div class="text-sm text-gray-600">Positions</div>
        </div>
    </div>

     Quick Actions 
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card-mobile">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Positions & Candidates</h3>
            <p class="text-gray-600 text-sm mb-4">Manage election positions and candidates</p>
            <a href="{{ route('admin.positions.index', $election) }}" 
               class="btn-mobile bg-blue-50 text-blue-700 hover:bg-blue-100 w-full text-center">
                Manage Positions
            </a>
        </div>

        <div class="card-mobile">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Voters</h3>
            <p class="text-gray-600 text-sm mb-4">Import voters and send invitations</p>
            <a href="{{ route('admin.voters.index', $election) }}" 
               class="btn-mobile bg-green-50 text-green-700 hover:bg-green-100 w-full text-center">
                Manage Voters
            </a>
        </div>

        <div class="card-mobile">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Results</h3>
            <p class="text-gray-600 text-sm mb-4">View results and export data</p>
            @if($election->isClosed())
                <a href="{{ route('admin.elections.results', $election) }}" 
                   class="btn-mobile bg-purple-50 text-purple-700 hover:bg-purple-100 w-full text-center">
                    View Results
                </a>
            @else
                <div class="btn-mobile bg-gray-100 text-gray-400 w-full text-center cursor-not-allowed">
                    Results Available After Close
                </div>
            @endif
        </div>
    </div>

     Election Details 
    <div class="card-mobile">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Election Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <p class="text-gray-900">{{ $election->starts_at->format('M j, Y g:i A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <p class="text-gray-900">{{ $election->ends_at->format('M j, Y g:i A') }}</p>
            </div>
            @if($election->description)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="text-gray-900">{{ $election->description }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
