@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Elections</h1>
                <p class="text-gray-600">Manage your elections and voting processes</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.elections.create') }}" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                    Create Election
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <select name="status" class="input-mobile">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="btn-mobile bg-gray-50 text-gray-700 hover:bg-gray-100">
                    Filter
                </button>
            </form>
        </div>

        <!-- Elections List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
            @forelse($elections as $election)
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $election->title }}</h2>
                        <p class="text-gray-600 text-sm">{{ $election->organization->name }}</p>
                        <p class="text-gray-500 text-xs mt-1">
                            {{ $election->starts_at->format('M j, Y') }} - {{ $election->ends_at->format('M j, Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 mt-4 sm:mt-0">
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full
                    @if ($election->status === 'draft') bg-gray-100 text-gray-800
                    @elseif($election->status === 'scheduled') bg-blue-100 text-blue-800
                    @elseif($election->status === 'open') bg-green-100 text-green-800
                    @elseif($election->status === 'closed') bg-red-100 text-red-800 @endif">
                            {{ ucfirst($election->status) }}
                        </span>
                        <a href="{{ route('admin.elections.show', $election) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No elections found.</p>
                    <a href="{{ route('admin.elections.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Create your first election
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($elections->hasPages())
            <div class="mt-6">
                {{ $elections->links() }}
            </div>
        @endif
    </div>
@endsection
