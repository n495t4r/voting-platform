@extends('layouts.voting')@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8"><div class="mb-6"><h1 class="text-3xl font-bold text-gray-900">Edit Candidate</h1><p class="text-gray-600">Editing: <span class="font-medium">{{ $candidate->name }}</span> for Position: <span class="font-medium">{{ $position->title }}</span></p>
    </div>
    <div class="card-mobile">
    <form method="POST" action="{{ route('admin.candidates.update', [$election, $position, $candidate]) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Candidate Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $candidate->name) }}" required class="input-mobile w-full">
        </div>

        <div>
            <label for="photo" class="block text-sm font-medium text-gray-700">Current Photo</label>
            @if($candidate->photo_path)
                <img src="{{ asset('storage/' . $candidate->photo_path) }}" alt="{{ $candidate->name }}" class="h-20 w-20 rounded-full object-cover mb-2">
            @else
                <p class="text-sm text-gray-500 mb-2">No photo uploaded.</p>
            @endif
            <label for="photo" class="block text-sm font-medium text-gray-700">Change Photo (Optional)</label>
            <input type="file" name="photo" id="photo" class="input-mobile w-full">
        </div>

        <div>
            <label for="manifesto" class="block text-sm font-medium text-gray-700">Manifesto (Optional)</label>
            <textarea name="manifesto" id="manifesto" rows="6" class="input-mobile w-full">{{ old('manifesto', $candidate->manifesto) }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                Update Candidate
            </button>
            <a href="{{ route('admin.candidates.index', [$election, $position]) }}" class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                Cancel
            </a>
        </div>
    </form>
</div>
</div>@endsection
