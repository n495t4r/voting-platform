@extends('layouts.voting')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('admin.components._breadcrumb', ['election' => $election])

        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Edit Election</h1>
            <p class="mt-1 text-lg text-gray-600">Update the details for **{{ $election->title }}**</p>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-8">
            <form method="POST" action="{{ route('admin.elections.update', $election) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Organisation -->
                <div>
                    <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Organization
                    </label>
                    <select id="organization_id" name="organization_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200">
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}"
                                {{ old('organization_id', $election->organization_id) == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Election Title
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $election->title) }}"
                           required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200">{{ old('description', $election->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date & Time
                    </label>
                    <input type="datetime-local" id="starts_at" name="starts_at"
                           value="{{ old('starts_at', $election->starts_at->format('Y-m-d\TH:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200">
                    @error('starts_at')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date & Time
                    </label>
                    <input type="datetime-local" id="ends_at" name="ends_at"
                           value="{{ old('ends_at', $election->ends_at->format('Y-m-d\TH:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200">
                    @error('ends_at')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-blue-600 hover:bg-blue-700 hover:shadow-xl transition-all duration-300">
                        <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Update Election
                    </button>
                    <a href="{{ route('admin.elections.show', $election) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-all duration-300">
                        <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
@endsection
