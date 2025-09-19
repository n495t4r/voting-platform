@extends('layouts.voting')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Include the dynamic breadcrumb component --}}
        @include('admin.components._breadcrumb', ['organization' => $organization ?? null, 'election' => null])

        <div class="mb-6">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Create Election</h1>
            <p class="mt-1 text-lg text-gray-600">Set up a new voting election</p>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-6">
            <form method="POST" action="{{ route('admin.elections.store') }}" class="space-y-6">
                @csrf

                <!-- Organization -->
                <div>
                    <label for="organization_id" class="block text-sm font-medium text-gray-700">
                        Organization
                    </label>
                    <select id="organization_id" name="organization_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Election Title
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700">
                        Start Date & Time
                    </label>
                    <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('starts_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700">
                        End Date & Time
                    </label>
                    <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('ends_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Create Election
                    </button>
                    <a href="{{ route('admin.elections.index') }}"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
