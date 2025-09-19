@extends('layouts.voting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Include the dynamic breadcrumb component --}}
    @include('admin.components._breadcrumb', ['election' => $election])

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-700 tracking-tight">Manage Positions</h1>
        <p class="mt-1 text-lg text-gray-600">Election: <span class="font-bold text-gray-600">{{ $election->title }}</span></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Positions List -->
        <div class="bg-white shadow-xl rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-700 mb-4">Positions</h3>
            @forelse($positions as $position)
                <div class="border-b border-gray-200 last:border-b-0 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                        <h4 class="font-bold text-lg text-gray-600">{{ $position->title }}</h4>
                        <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0">
                            <a href="{{ route('admin.elections.positions.candidates.index', [$election, $position]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                Candidates ({{ $position->candidates->count() }})
                            </a>
                            <button
                                type="button"
                                class="btn-sm bg-yellow-400 text-yellow-900 hover:bg-yellow-500 rounded-full px-4 py-1.5 text-sm font-medium"
                                onclick="editPosition({{ $position->id }}, '{{ addslashes($position->title) }}', '{{ addslashes($position->description) }}', {{ $position->min_select }}, {{ $position->max_select }}, {{ $position->order }})"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn-sm bg-red-600 text-white hover:bg-red-700 rounded-full px-4 py-1.5 text-sm font-medium"
                                onclick="showDeleteModal({{ $position->id }})"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Description: {{ $position->description ?? 'No description' }}</p>
                    <div class="flex flex-wrap gap-x-4 text-sm text-gray-600 mt-1">
                        <span>Min Select: <span class="font-medium">{{ $position->min_select }}</span></span>
                        <span>Max Select: <span class="font-medium">{{ $position->max_select }}</span></span>
                        <span>Order: <span class="font-medium">{{ $position->order }}</span></span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">No positions found for this election.</p>
            @endforelse
        </div>

        <!-- Add/Edit Position Form -->
        <div class="bg-white shadow-xl rounded-lg p-6 h-fit sticky top-6">
            <h3 class="text-2xl font-bold text-gray-700 mb-4" id="form-title">Add New Position</h3>
            <form id="position-form" action="{{ route('admin.positions.store', $election) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="position_id" id="position_id">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div>
                    <label for="min_select" class="block text-sm font-medium text-gray-700">Min Select</label>
                    <input type="number" name="min_select" id="min_select" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="max_select" class="block text-sm font-medium text-gray-700">Max Select</label>
                    <input type="number" name="max_select" id="max_select" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="order" id="order" value="0" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit" id="form-submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Create Position
                </button>
                <button type="button" id="form-cancel" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors hidden" onclick="resetForm()">
                    Cancel Edit
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Deletion Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex justify-center items-center">
    <div class="bg-white rounded-xl shadow-2xl p-8 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Confirm Deletion</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideDeleteModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="text-gray-700 text-center">
            <p class="font-semibold mb-4">Are you sure you want to delete this position?</p>
            <p>This action cannot be undone.</p>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors" onclick="hideDeleteModal()">
                Cancel
            </button>
            <button type="button" id="confirmDeleteBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function editPosition(id, title, description, min_select, max_select, order) {
    document.getElementById('form-title').innerText = 'Edit Position';
    document.getElementById('title').value = title;
    document.getElementById('description').value = description;
    document.getElementById('min_select').value = min_select;
    document.getElementById('max_select').value = max_select;
    document.getElementById('order').value = order;
    document.getElementById('form-submit').innerText = 'Update Position';
    document.getElementById('form-cancel').classList.remove('hidden');
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('position_id').value = id;

    // Change form action to update route
    document.getElementById('position-form').action = `{{ route('admin.positions.update', [$election, 'POSITION_ID']) }}`.replace('POSITION_ID', id);
}

function resetForm() {
    document.getElementById('form-title').innerText = 'Add New Position';
    document.getElementById('title').value = '';
    document.getElementById('description').value = '';
    document.getElementById('min_select').value = 1;
    document.getElementById('max_select').value = 1;
    document.getElementById('order').value = 0;
    document.getElementById('form-submit').innerText = 'Create Position';
    document.getElementById('form-cancel').classList.add('hidden');
    document.getElementById('form-method').value = 'POST';
    document.getElementById('position_id').value = '';

    // Reset form action to store route
    document.getElementById('position-form').action = "{{ route('admin.positions.store', $election) }}";
}

function showDeleteModal(positionId) {
    const modal = document.getElementById('deleteModal');
    const modalContent = modal.querySelector('div');
    const deleteForm = document.getElementById('delete-form');
    deleteForm.action = `{{ route('admin.positions.destroy', [$election, 'POSITION_ID']) }}`.replace('POSITION_ID', positionId);

    document.getElementById('confirmDeleteBtn').onclick = () => {
        deleteForm.submit();
    };

    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = modal.querySelector('div');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endsection
