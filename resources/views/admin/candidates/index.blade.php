@extends('layouts.voting')

@section('content')<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6"><h1 class="text-3xl font-bold text-gray-900">Manage Candidates</h1>
        <p class="text-gray-600">For Election: <span class="font-medium">{{ $election->title }}</span></p>
        <p class="text-gray-600">Position: <span class="font-medium">{{ $position->title }}</span></p>
    </div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Candidates List -->
    <div class="card-mobile md:col-span-1">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Candidates</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Photo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Manifesto
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($candidates as $candidate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($candidate->photo_path)
                                    <img src="{{ asset('storage/' . $candidate->photo_path) }}" alt="{{ $candidate->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-xs text-gray-600">No Photo</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $candidate->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 truncate max-w-xs">
                                {{ $candidate->manifesto ? Str::limit($candidate->manifesto, 50) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.elections.positions.candidates.edit', [$election, $position, $candidate]) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                <form action="{{ route('admin.elections.positions.candidates.destroy', [$election, $position, $candidate]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this candidate?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No candidates found for this position.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add New Candidate Form -->
    <div class="card-mobile md:col-span-1">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Candidate</h3>

        <form action="{{ route('admin.elections.positions.candidates.store', [$election, $position]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Candidate Name</label>
                <input type="text" name="name" id="name" required class="input-mobile w-full">
            </div>
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo (Optional)</label>
                <input type="file" name="photo" id="photo" class="input-mobile w-full">
            </div>
            <div>
                <label for="manifesto" class="block text-sm font-medium text-gray-700">Manifesto (Optional)</label>
                <textarea name="manifesto" id="manifesto" rows="4" class="input-mobile w-full"></textarea>
            </div>
            <button type="submit" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 w-full">
                Add Candidate
            </button>
        </form>
    </div>
</div>
</div>@endsection
