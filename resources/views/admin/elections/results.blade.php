@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Include the dynamic breadcrumb component --}}
        @include('admin.components._breadcrumb', ['election' => $election])

        <div class="mb-6 sticky top-0 z-20 bg-white pt-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Election Results</h1>
            <p class="text-gray-600">Final results for: <span class="font-medium">{{ $election->title }}</span></p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Results by Position</h3>
                <a href="{{ route('admin.elections.export-results', $election) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L6.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    Export
                </a>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($results['positions'] as $result)
                    @php

                        $position = $result['position'];
                        $candidates = $result['candidates'];
                        $totalVotes = $result['total_votes'];
                        $maxVotes = count($candidates) > 0 ? max(array_column($candidates->toArray(), 'votes')) : 0;
                    @endphp

                    <div class="py-6 first:pt-0 last:pb-0">
                        <h4 class="text-xl font-bold text-gray-900 mb-4">{{ $position['title'] }}</h4>
                        @if (count($candidates) > 0)
                            <div class="space-y-4">
                                @foreach ($candidates as $candidate)
                                    @php
                                        $percentage = $totalVotes > 0 ? ($candidate['votes'] / $totalVotes) * 100 : 0;
                                        $isWinner = $candidate['votes'] > 0 && $candidate['votes'] === $maxVotes;
                                        $isTie = $candidate['votes'] > 0 && $candidate['votes'] === $maxVotes && collect($candidates)->where('votes', $maxVotes)->count() > 1;
                                    @endphp
                                    <div class="p-4 rounded-lg flex items-center justify-between
                                        @if($isWinner) bg-green-50 border-2 border-green-200 @else bg-gray-50 border border-gray-100 @endif">
                                        <div class="flex-1 min-w-0 pr-4">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="font-bold text-lg text-gray-800">{{ $candidate['candidate']->name }}</span>
                                                @if($isWinner)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm font-semibold text-green-500">
                                                        @if($isTie)
                                                            Tied Winner
                                                        @else
                                                            Winner
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full transition-all duration-500 ease-out
                                                    @if($isWinner) bg-green-500 @else bg-blue-500 @endif"
                                                     style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <div class="flex justify-between items-center text-sm text-gray-600 mt-1">
                                                <span>{{ number_format($percentage, 1) }}%</span>
                                                <span>{{ $candidate['votes'] }} vote{{ $candidate['votes'] != 1 ? 's' : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No votes cast for this position.</p>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-6">No positions or results found.</p>
                @endforelse
            </div>


        </div>
    </div>
@endsection
