@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
     Header 
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Elections</h1>
            <p class="text-gray-600">Manage your elections and voting processes</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.elections.create') }}" 
               class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                Create Election
            </a>
        </div>
    </div>

     Filters 
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <select name="status" class="input-mobile">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit" class="btn-mobile bg-gray-600 text-white hover:bg-gray-700">
                Filter
            </button>
        </form>
    </div>

     Elections List 
    <div class="space-y-4">
        @forelse($elections as $election)
            <div class="card-mobile">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('admin.elections.show', $election) }}" 
                               class="hover:text-blue-600">
                                {{ $election->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">{{ $election->organization->name }}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span>{{ $election->starts_at->format('M j, Y g:i A') }}</span>
                            <span>→</span>
                            <span>{{ $election->ends_at->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4 sm:mt-0">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($election->status === 'draft') bg-gray-100 text-gray-800
                            @elseif($election->status === 'scheduled') bg-blue-100 text-blue-800
                            @elseif($election->status === 'open') bg-green-100 text-green-800
                            @elseif($election->status === 'closed') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($election->status) }}
                        </span>
                        <a href="{{ route('admin.elections.show', $election) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No elections found.</p>
                <a href="{{ route('admin.elections.create') }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    Create your first election
                </a>
            </div>
        @endforelse
    </div>

     Pagination 
    @if($elections->hasPages())
        <div class="mt-6">
            {{ $elections->links() }}
        </div>
    @endif
</div>
@endsection
