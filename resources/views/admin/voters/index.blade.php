@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Include the dynamic breadcrumb component --}}
        @include('admin.components._breadcrumb', ['election' => $election])

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Voters</h1>
                <p class="mt-1 text-lg text-gray-600">Managing voters for the election: **{{ $election->title }}**</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 mt-4 sm:mt-0">
                <a href="{{ route('admin.voters.import', $election) }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-blue-600 hover:bg-blue-700 hover:shadow-xl transition-all duration-300">
                    <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Import Voters
                </a>
                <button type="button"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-green-600 hover:bg-green-700 hover:shadow-xl transition-all duration-300"
                    onclick="showSendInvitationsModal(false, false)">
                    <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 00.183.183l-3 3a1 1 0 00.183.183l7-14a1 1 0 00-.183-.183l-3-3a1 1 0 000-1.414zM16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        <path
                            d="M10.59 10.59l4.5 4.5a.5.5 0 01-.707.707L9.883 11.29a.5.5 0 01-.707 0L4.68 16.39a.5.5 0 01-.707-.707l5-5a.5.5 0 01.707 0z" />
                    </svg>
                    Send Invitations
                </button>
            </div>

        </div>

        <!-- Filters -->
        <div class="card-mobile mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8a4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search voters by name or email..." class="input-mobile w-full pl-10">
                </div>
                <select name="status" class="input-mobile">
                    <option value="">All Statuses</option>
                    <option value="invited" {{ request('status') === 'registered' ? 'selected' : '' }}>Registered</option>
                    <option value="invited" {{ request('status') === 'invited' ? 'selected' : '' }}>Invited</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="voted" {{ request('status') === 'voted' ? 'selected' : '' }}>Voted</option>
                </select>
                <button type="submit" class="btn-mobile bg-gray-600 text-white hover:bg-gray-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Voters List -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6">
                @if ($voters->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No voters found.</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by importing your voter list.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('admin.voters.import', $election) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Import Voters
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Voted At
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($voters as $voter)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $voter->full_name }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $voter->email ?: $voter->phone }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                                @if ($voter->status === 'invited') bg-yellow-100 text-yellow-800
                                                @elseif($voter->status === 'verified') bg-blue-100 text-blue-800
                                                @elseif($voter->status === 'voted') bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($voter->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $voter->voted_at?->format('M j, Y g:i A') ?: '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <!-- Send Invitation Button -->
                                            <button type="button" class="text-yellow-600 hover:text-yellow-800 transition"
                                                onclick="showSendInvitationsModal('{{ $voter->id }}', false)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 12v4m0 0v4m0-4h4m-4 0h-4m4-4V8m0 4H8m8 0h4m-4 0H4" />
                                                </svg>
                                            </button>
                                            <form id="send-single-invitation-form-{{ $voter->id }}" method="POST"
                                                action="{{ route('admin.voters.send-invitations', $election) }}"
                                                class="hidden">
                                                <input type="hidden" name="voter_id" value="{{ $voter->id }}">
                                                @csrf
                                            </form>

                                            <!-- Revoke Invitation Button -->
                                            <button type="button" class="text-red-600 hover:text-red-800 transition ml-2"
                                                onclick="showSendInvitationsModal('{{ $voter->id }}', true)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            <form id="revoke-invitations-form-{{ $voter->id }}" method="POST"
                                                action="{{ route('admin.voters.revoke-invitations', [$election, $voter->id]) }}"
                                                class="hidden">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- @if ($voters->hasPages()) --}}
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $voters->links() }}
            </div>
            {{-- @endif --}}
        </div>
    </div>

    <!-- Send Invitations Confirmation Modal -->
    <div id="sendInvitationsModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex justify-center items-center">
        <div
            class="bg-white rounded-xl shadow-2xl p-8 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modal_header" class="text-2xl font-bold text-gray-900">Confirm Invitation Send</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition"
                    onclick="hideSendInvitationsModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="text-gray-700 text-center">
                <p id="modal_body" class="font-semibold mb-4">Are you sure you want to send invitation to eligible
                    voter(s)?</p>
            </div>
            <div class="mt-6 flex justify-center space-x-4">
                <button type="button"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                    onclick="hideSendInvitationsModal()">
                    Cancel
                </button>
                <button type="button" id="confirmSendBtn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 transition-colors">
                    Send
                </button>
            </div>
        </div>
    </div>

    <form id="send-invitations-form" method="POST" action="{{ route('admin.voters.send-invitations', $election) }}"
        class="hidden">
        @csrf
    </form>

    <script>
        function showSendInvitationsModal(voter = true, revoke = false) {
            const modal = document.getElementById('sendInvitationsModal');
            const modalContent = modal.querySelector('div');

            if (revoke) {
                console.log("Revoke");
                // document.getElementById("modal_header").innerHTML = "Revoke Invite";
            }

            document.getElementById('confirmSendBtn').onclick = () => {
                if (voter) {
                    document.getElementById('send-single-invitation-form-' + voter).submit();
                    if (revoke) {
                        document.getElementById('revoke-invitations-form-' + voter).submit();
                    }
                } else {
                    document.getElementById('send-invitations-form').submit();
                }
            };

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                if (revoke) {
                    console.log("Revoke");
                    document.getElementById("modal_header").innerHTML = "Revoke Invite";
                }
            }, 10);
        }

        function hideSendInvitationsModal() {
            const modal = document.getElementById('sendInvitationsModal');
            const modalContent = modal.querySelector('div');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
