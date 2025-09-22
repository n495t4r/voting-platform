@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
            <p class="text-gray-600">Manage the entire voting platform</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- System Overview -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Overview</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Elections:</span>
                        <span class="font-medium">{{ $metrics['totalElections'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Active Users:</span>
                        <span class="font-medium">{{ $metrics['activeUsers'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Votes Cast:</span>
                        <span class="font-medium">{{ $metrics['totalVotes'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('super.users.index') }}"
                        class="block w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition">
                        Manage Users
                    </a>

                    <a href="{{ route('super.features.index') }}"
                        class="block w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition">
                        Feature Flags
                    </a>
                    <a href="{{ route('super.settings.index') }}"
                        class="block w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-md hover:bg-purple-100 transition">
                        System Settings
                    </a>
                    <a href="{{ route('admin.elections.index') }}"
                        class="block w-full text-left px-4 py-2 bg-yellow-50 text-yellow-700 rounded-md hover:bg-yellow-100 transition">
                        Manage Elections
                    </a>
                    <a href="{{ route('admin.organizations.index') }}"
                        class="block w-full text-left px-4 py-2 bg-pink-50 text-pink-700 rounded-md hover:bg-pink-100 transition">
                        Manage Organizations
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                @if (count($recentActivity) > 0)
                    <div class="max-h-80 overflow-y-auto">
                        @foreach ($recentActivity as $activity)
                        {{-- @dd($activity) --}}
                            <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                                <div>
                                    <p class="text-gray-900 font-medium">{{ $activity->actor->name }}</p>
                                    <p class="text-gray-900 font-medium">{{ $activity->event }}</p>
                                    <p class="text-gray-500 text-sm">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No recent activity.</p>
                @endif
            </div>

            <!-- Recent Elections -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Elections</h3>
                @if (count($recentElections) > 0)
                    @foreach ($recentElections as $election)
                        <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                            <div>
                                <a href="{{ route('admin.elections.show', $election) }}"
                                    class="text-gray-900 hover:text-blue-600 font-medium">
                                    {{ $election->title }}
                                </a>
                                <p class="text-gray-500 text-sm">{{ $election->organization->name }}</p>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full
                                    @if ($election->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($election->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($election->status === 'open') bg-green-100 text-green-800
                                    @elseif($election->status === 'closed') bg-red-100 text-red-800 @endif">
                                {{ ucfirst($election->status) }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center">No recent elections.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
