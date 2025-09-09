@extends('layouts.voting')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="mb-6">
<h1 class="text-3xl font-bold text-gray-900">Manage Positions</h1>
<p class="text-gray-600">Election: <span class="font-medium">{{ $election->title }}</span></p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Positions List -->
    <div class="card-mobile md:col-span-1">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Positions</h3>
        @forelse($positions as $position)
            <div class="border-b last:border-b-0 py-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-gray-800">{{ $position->title }}</h4>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.elections.positions.candidates.index', [$election, $position]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                            Candidates ({{ $position->candidates->count() }})
                        </a>
                        <!-- Edit Button -->
                        <button
                            type="button"
                            class="btn-mobile bg-yellow-500 text-blue hover:bg-yellow-600 px-3 py-1 rounded text-sm"
                            onclick="editPosition({{ $position->id }}, '{{ addslashes($position->title) }}', '{{ addslashes($position->description) }}', {{ $position->min_select }}, {{ $position->max_select }}, {{ $position->order }})"
                        >
                            Edit
                        </button>
                        <!-- Delete Form -->
                        <form action="{{ route('admin.positions.destroy', [$election, $position]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this position?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-mobile bg-red-600 text-white hover:bg-red-700 px-3 py-1 rounded text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-sm text-gray-600">Description: {{ $position->description ?? 'No description' }}</p>
                <p class="text-sm text-gray-600">
                    Min Select: {{ $position->min_select }} /
                    Max Select: {{ $position->max_select }}
                </p>
                <p class="text-sm text-gray-600">Order: {{ $position->order }}</p>
                @if($position->candidates->count())
                    <ul class="list-disc list-inside mt-2 text-sm text-gray-700">
                        @foreach($position->candidates as $candidate)
                            <li>{{ $candidate->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 italic mt-2">No candidates yet.</p>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-500">No positions found for this election.</p>
        @endforelse
    </div>

    <!-- Add/Edit Position Form -->
    <div class="card-mobile md:col-span-1">
        <h3 class="text-lg font-semibold text-gray-900 mb-4" id="form-title">Add New Position</h3>
        <form id="position-form" action="{{ route('admin.positions.store', $election) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="position_id" id="position_id">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" required class="input-mobile w-full">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="input-mobile w-full"></textarea>
            </div>
            <div>
                <label for="min_select" class="block text-sm font-medium text-gray-700">Min Select</label>
                <input type="number" name="min_select" id="min_select" value="1" min="1" required class="input-mobile w-full">
            </div>
            <div>
                <label for="max_select" class="block text-sm font-medium text-gray-700">Max Select</label>
                <input type="number" name="max_select" id="max_select" value="1" min="1" required class="input-mobile w-full">
            </div>
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                <input type="number" name="order" id="order" value="0" min="0" required class="input-mobile w-full">
            </div>
            <button type="submit" id="form-submit" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 w-full">
                Create Position
            </button>
            <button type="button" id="form-cancel" class="btn-mobile bg-gray-400 text-white hover:bg-gray-500 w-full mt-2 hidden" onclick="resetForm()">
                Cancel Edit
            </button>
        </form>
    </div>
</div>
</div>

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
    document.getElementById('position-form').action = "{{ route('admin.positions.update', [$election, 'POSITION_ID']) }}".replace('POSITION_ID', id);
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
</script>
@endsection
