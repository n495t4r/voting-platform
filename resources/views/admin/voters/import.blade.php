@extends('layouts.voting')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Include the dynamic breadcrumb component --}}
        @include('admin.components._breadcrumb', ['election' => $election])

        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Import Voters</h1>
            <p class="mt-1 text-lg text-gray-600">Upload a CSV file to add voters to: **{{ $election->title }}**</p>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-8">
            <form method="POST" action="{{ route('admin.voters.process-import', $election) }}"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- File Upload -->
                <div>
                    <label for="voters_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload CSV File
                    </label>
                    <div id="drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m-4-4l-4 4m-4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="voters_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="file-label">Upload a file</span>
                                    <input id="voters_file" name="voters_file" type="file" class="sr-only" accept=".csv" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                CSV file up to 10MB
                            </p>
                        </div>
                    </div>
                    @error('voters_file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Please upload a CSV file with columns: `full_name`, `email`, `phone` (optional), and `external_id` (optional).
                    </p>
                </div>

                <!-- CSV Format Example -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">CSV Format Example:</h4>
                    <pre class="text-xs text-gray-600 overflow-x-auto p-3 rounded bg-gray-100"><code>full_name,email,phone,external_id
John Doe,john@example.com,+1234567890,ID001
Jane Smith,jane@example.com,,ID002</code></pre>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-blue-600 hover:bg-blue-700 hover:shadow-xl transition-all duration-300">
                        <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Import Voters
                    </button>
                    <a href="{{ route('admin.voters.index', $election) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-all duration-300">
                        <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Download Template -->
        <div class="bg-white shadow-xl rounded-xl p-8 mt-6 flex flex-col sm:flex-row items-center justify-between">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-xl font-semibold text-gray-900 mb-1">Need a Template?</h3>
                <p class="text-gray-600 text-sm">
                    Download a CSV template to get started with the correct format.
                </p>
            </div>
            <a href="data:text/csv;charset=utf-8,full_name%2Cemail%2Cphone%2Cexternal_id%0AJohn%20Doe%2Cjohn%40example.com%2C%2B1234567890%2CID001%0AJane%20Smith%2Cjane%40example.com%2C%2CID002"
               download="voters_template.csv"
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-full text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 shrink-0">
                <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm2.293 11.293a1 1 0 01-1.414 0L10 12.414l-1.879 1.879a1 1 0 01-1.414-1.414l2.5-2.5a1 1 0 011.414 0l2.5 2.5a1 1 0 010 1.414zM10 6a1 1 0 011 1v5a1 1 0 11-2 0V7a1 1 0 011-1z" />
                </svg>
                Download Template
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('voters_file');
            const fileLabel = document.getElementById('file-label');

            dropZone.addEventListener('click', () => fileInput.click());

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-500');
                dropZone.classList.remove('border-gray-300');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500');
                dropZone.classList.add('border-gray-300');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500');
                dropZone.classList.add('border-gray-300');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileLabel.textContent = files[0].name;
                }
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileLabel.textContent = fileInput.files[0].name;
                }
            });
        });
    </script>
@endsection
