@extends('layouts.voting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
     Header
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Voters</h1>
            <p class="text-gray-600">{{ $election->title }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
            <a href="{{ route('admin.voters.import', $election) }}"
               class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 text-center">
                Import Voters
            </a>
            <form method="POST" action="{{ route('admin.voters.send-invitations', $election) }}" class="inline">
                @csrf
                <button type="submit" class="btn-mobile bg-green-600 text-white hover:bg-green-700"
                        onclick="return confirm('Send invitations to all eligible voters?')">
                    Send Invitations
                </button>
            </form>
        </div>
    </div>

     Filters
    <div class="card-mobile mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search voters..." class="input-mobile flex-1">
            <select name="status" class="input-mobile">
                <option value="">All Statuses</option>
                <option value="invited" {{ request('status') === 'invited' ? 'selected' : '' }}>Invited</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="voted" {{ request('status') === 'voted' ? 'selected' : '' }}>Voted</option>
            </select>
            <button type="submit" class="btn-mobile bg-gray-600 text-white hover:bg-gray-700">
                Filter
            </button>
        </form>
    </div>

     Voters List
    <div class="card-mobile">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Voted At
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($voters as $voter)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $voter->full_name }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $voter->email ?: $voter->phone }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($voter->status === 'invited') bg-gray-100 text-gray-800
                                    @elseif($voter->status === 'verified') bg-blue-100 text-blue-800
                                    @elseif($voter->status === 'voted') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($voter->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $voter->voted_at?->format('M j, Y g:i A') ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                No voters found.
                                <a href="{{ route('admin.voters.import', $election) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    Import voters
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($voters->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $voters->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
