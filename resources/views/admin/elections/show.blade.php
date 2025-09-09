@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $election->title }}</h1>
                <p class="text-gray-600">{{ $election->organization->name }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-3 py-1 text-sm font-medium rounded-full
                @if ($election->status === 'draft') bg-gray-100 text-gray-800
                @elseif($election->status === 'scheduled') bg-blue-100 text-blue-800
                @elseif($election->status === 'open') bg-green-100 text-green-800
                @elseif($election->status === 'closed') bg-red-100 text-red-800 @endif">
                        {{ ucfirst($election->status) }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 mt-4 sm:mt-0">
                @if ($election->isDraft())
                    <form method="POST" id="stateForm" action="{{ route('admin.elections.open', $election) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-mobile bg-green-600 text-white hover:bg-green-700">
                            Open Election
                        </button>
                    </form>
                    <a href="{{ route('admin.elections.edit', $election) }}"
                        class="btn-mobile bg-yellow-400 text-yellow-900 hover:bg-yellow-500">
                        Edit Election
                    </a>
                @elseif($election->isOpen())
                    <form method="POST" id="stateForm" action="{{ route('admin.elections.close', $election) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-mobile bg-red-600 text-white hover:bg-red-700">
                            Close Election
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-mobile mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="{{ route('admin.positions.index', $election) }}"
                    class="btn-mobile bg-purple-50 text-purple-700 hover:bg-purple-100 w-full text-center">
                    Manage Positions
                </a>
                <a href="{{ route('admin.voters.index', $election) }}"
                    class="btn-mobile bg-green-50 text-green-700 hover:bg-green-100 w-full text-center">
                    Manage Voters
                </a>
                @if ($election->isClosed())
                    <a href="{{ route('admin.elections.results', $election) }}"
                        class="btn-mobile bg-blue-50 text-blue-700 hover:bg-blue-100 w-full text-center">
                        View Results
                    </a>
                @else
                    <div class="btn-mobile bg-gray-100 text-gray-400 w-full text-center cursor-not-allowed">
                        Results Available After Close
                    </div>
                @endif
            </div>
        </div>

        <!-- Election Details -->
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
                @if ($election->description)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="text-gray-900">{{ $election->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal for final confirmation -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-50 hidden flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Confirm</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideConfirmationModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="text-gray-700 text-center">
                    <p class="font-semibold mb-4">Are you sure you want to continue?</p>
                    {{-- <p>Your vote cannot be changed after submission.</p> --}}
                </div>
                <div class="mt-6 flex justify-center space-x-4">
                    <button type="button" class="bg-gray-200 text-gray-700 font-semibold py-2 px-6 rounded-full hover:bg-gray-300 transition-colors" onclick="hideConfirmationModal()">
                        Cancel
                    </button>
                    <button type="button" id="confirmSubmitBtn" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors">
                        Confirm & Submit
                    </button>
                </div>
            </div>
        </div>

    </div>
<script>

    // Handle form submission and validation
        document.getElementById('stateForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Stop form submission for now
            showConfirmationModal();
            // Add a click listener to the confirmation button to submit the form
            document.getElementById('confirmSubmitBtn').addEventListener('click', () => {
                document.getElementById('stateForm').submit();
            });
        });


    // Show and hide confirmation modal
    function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('div');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('div');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
