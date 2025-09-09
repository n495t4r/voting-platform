@extends('layouts.voting')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Voter Dashboard</h1>
            <p class="text-gray-600">View your voting opportunities</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Available Elections -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Elections</h3>
                @php
                    // $availableElections = $voters->filter(fn($v) => $v->status === 'invited' && $v->election->isOpen());
                    $availableElections;
                    // dd($availableElections);
                @endphp
                @forelse($availableElections as $availElection)

                    {{-- @dd($availElection) --}}

                    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $availElection->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $availElection->organization->name }}</p>
                        </div>

                        @if($availElection->voterTokens->isNotEmpty() && $availElection->voterTokens->first()->token_hash)
                            <a href="{{ route('vote.show', $availElection->voterTokens->first()->token_hash) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Vote Now
                            </a>
                        @else
                            <span class="text-gray-400 text-sm font-medium">No token available</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No elections available for voting.</p>
                @endforelse
            </div>

            <!-- Voting History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Voting History</h3>
                @php
                    // $votedElections = $voters->filter(fn($v) => $v->status === 'voted');
                    // dd($votedElections);
                @endphp
                @if(isset($votedElections))
                    @foreach($votedElections as $vElection)
                        <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ isset($vElection->title) ? $vElection->title : '' }}</h4>
                                <p class="text-sm text-gray-600">
                                    Voted on:
                                    @if(isset($vElection->ballots) && count($vElection->ballots) > 0 && isset($vElection->ballots[0]->created_at))
                                        {{ \Carbon\Carbon::parse($vElection->ballots[0]->created_at)->format('M j, Y') }}
                                    @else
                                        <span class="text-gray-400">No ballot found</span>
                                    @endif
                                </p>
                            </div>
                            @if(isset($vElection->ballots) && count($vElection->ballots) > 0 && isset($vElection->ballots[0]->ballot_uid))
                                @if(Route::has('vote.receipt'))
                                    <a href="{{ route('vote.receipt', $vElection->ballots[0]->ballot_uid) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Receipt
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm font-medium">Receipt route not found</span>
                                @endif
                            @else
                                <span class="text-gray-400 text-sm font-medium">No receipt available</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-sm">No voting history yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
