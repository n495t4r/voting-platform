@extends('layouts.voting')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Import Voters</h1>
        <p class="text-gray-600">{{ $election->title }}</p>
    </div>

    <div class="card-mobile">
        <form method="POST" action="{{ route('admin.voters.process-import', $election) }}"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

             File Upload
            <div>
                <label for="voters_file" class="block text-sm font-medium text-gray-700 mb-1">
                    CSV File
                </label>
                <input type="file" id="voters_file" name="voters_file" accept=".csv"
                       required class="input-mobile w-full">
                @error('voters_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Upload a CSV file with columns: full_name, email, phone (optional), external_id (optional)
                </p>
            </div>

             CSV Format Example
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">CSV Format Example:</h4>
                <pre class="text-xs text-gray-600 overflow-x-auto">full_name,email,phone,external_id
John Doe,john@example.com,+1234567890,ID001
Jane Smith,jane@example.com,,ID002</pre>
            </div>

             Submit Buttons
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                    Import Voters
                </button>
                <a href="{{ route('admin.voters.index', $election) }}"
                   class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

     Download Template
    <div class="card-mobile mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Need a Template?</h3>
        <p class="text-gray-600 text-sm mb-4">
            Download a CSV template to get started with the correct format.
        </p>
        <a href="data:text/csv;charset=utf-8,full_name%2Cemail%2Cphone%2Cexternal_id%0AJohn%20Doe%2Cjohn%40example.com%2C%2B1234567890%2CID001%0AJane%20Smith%2Cjane%40example.com%2C%2CID002"
           download="voters_template.csv"
           class="btn-mobile bg-green-50 text-green-700 hover:bg-green-100">
            Download Template
        </a>
    </div>
</div>
@endsection
