@extends('layouts.voting')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create Election</h1>
            <p class="text-gray-600">Set up a new voting election</p>
        </div>

        <div class="card-mobile">
            <form method="POST" action="{{ route('admin.elections.store') }}" class="space-y-6">
                @csrf

                <!-- Organization -->
                <div>
                    <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Organization
                    </label>
                    <select id="organization_id" name="organization_id" required class="input-mobile w-full">
                        <option value="">Select Organization</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Election Title
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="input-mobile w-full">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4" class="input-mobile w-full">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">
                        Start Date & Time
                    </label>
                    <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required
                        class="input-mobile w-full">
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">
                        End Date & Time
                    </label>
                    <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required
                        class="input-mobile w-full">
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                        Create Election
                    </button>
                    <a href="{{ route('admin.elections.index') }}"
                        class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
