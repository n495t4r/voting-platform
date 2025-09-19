@extends('layouts.voting')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Include the dynamic breadcrumb component --}}
        @include('admin.components._breadcrumb', ['election' => $election])

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $election->title }}</h1>
                <p class="mt-1 text-lg text-gray-600">{{ $election->organization->name }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full uppercase tracking-wide
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
                    <button
                        type="button"
                        id="open-election-btn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                        data-action="open">
                        Open Election
                    </button>
                    <a href="{{ route('admin.elections.edit', $election) }}"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Edit Election
                    </a>
                @elseif($election->isOpen())
                    <button
                        type="button"
                        id="close-election-btn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        data-action="close">
                        Close Election
                    </button>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow-xl rounded-lg p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.positions.index', $election) }}"
                    class="group flex items-center justify-center p-4 bg-purple-50 rounded-lg transition-colors duration-200 hover:bg-purple-100">
                    <div class="text-center">
                        <svg class="h-8 w-8 text-purple-600 mx-auto transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="mt-2 block text-sm font-semibold text-purple-700">Manage Positions</span>
                    </div>
                </a>
                <a href="{{ route('admin.voters.index', $election) }}"
                    class="group flex items-center justify-center p-4 bg-green-50 rounded-lg transition-colors duration-200 hover:bg-green-100">
                    <div class="text-center">
                        <svg class="h-8 w-8 text-green-600 mx-auto transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="mt-2 block text-sm font-semibold text-green-700">Manage Voters</span>
                    </div>
                </a>
                @if ($election->isClosed())
                    <a href="{{ route('admin.elections.results', $election) }}"
                        class="group flex items-center justify-center p-4 bg-blue-50 rounded-lg transition-colors duration-200 hover:bg-blue-100">
                        <div class="text-center">
                            <svg class="h-8 w-8 text-blue-600 mx-auto transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="mt-2 block text-sm font-semibold text-blue-700">View Results</span>
                        </div>
                    </a>
                @else
                    <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg cursor-not-allowed">
                        <div class="text-center">
                            <svg class="h-8 w-8 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="mt-2 block text-sm font-medium text-gray-400">Results Available After Close</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Election Details -->
        <div class="bg-white shadow-xl rounded-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Election Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <p class="mt-1 text-gray-900">{{ $election->starts_at->format('M j, Y g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <p class="mt-1 text-gray-900">{{ $election->ends_at->format('M j, Y g:i A') }}</p>
                </div>
                @if ($election->description)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-gray-900">{{ $election->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex justify-center items-center">
            <div class="bg-white rounded-xl shadow-2xl p-8 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Confirm</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideConfirmationModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="text-gray-700 text-center">
                    <p class="font-semibold mb-4" id="modal-message"></p>
                </div>
                <div class="mt-6 flex justify-center space-x-4">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors" onclick="hideConfirmationModal()">
                        Cancel
                    </button>
                    <button type="button" id="confirmActionBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
<form method="POST" id="actionForm" class="hidden">
    @csrf
    <input type="hidden" name="_method" id="action-method">
</form>
<script>
    const openBtn = document.getElementById('open-election-btn');
    const closeBtn = document.getElementById('close-election-btn');
    const modal = document.getElementById('confirmationModal');
    const modalContent = modal.querySelector('div');
    const actionForm = document.getElementById('actionForm');
    const confirmBtn = document.getElementById('confirmActionBtn');
    const modalMessage = document.getElementById('modal-message');

    // Attach listeners if buttons exist
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            modalMessage.innerText = 'Are you sure you want to open this election? Once open, you can no longer edit it.';
            actionForm.action = "{{ route('admin.elections.open', $election) }}";
            document.getElementById('action-method').value = 'POST';
            showConfirmationModal();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modalMessage.innerText = 'Are you sure you want to close this election? This action cannot be undone.';
            actionForm.action = "{{ route('admin.elections.close', $election) }}";
            document.getElementById('action-method').value = 'POST';
            showConfirmationModal();
        });
    }

    // Handle modal confirmation click
    confirmBtn.addEventListener('click', () => {
        actionForm.submit();
    });

    // Show and hide confirmation modal
    function showConfirmationModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideConfirmationModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
