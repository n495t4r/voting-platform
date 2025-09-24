@extends('layouts.ballot')

@section('content')

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h1 class="text-xl font-bold text-gray-900">{{ $election->title }}</h1>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-gray-700">Welcome, {{ $voter->full_name }}</p>
            <p class="text-xs text-gray-500">{{ $voter->email }}</p>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">{{ $election->title }}</h1>
        <p class="mt-2 text-lg text-gray-600">{{ $election->description }}</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form id="votingForm" method="POST" action="{{ route('vote.submit', ['token' => $token, 'signature'=>$signature]) }}" class="space-y-12">
        @csrf

        <!-- Modal for validation errors -->
        <div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-50 hidden flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-red-600">Voting Error</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideErrorModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div id="errorModalBody" class="text-gray-700 space-y-2"></div>
                <div class="mt-6 text-center">
                    <button type="button" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors" onclick="hideErrorModal()">
                        Got it
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal for final confirmation -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-50 hidden flex justify-center items-center">
            <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full transform transition-all duration-300 scale-95 opacity-0">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Confirm Your Vote</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="hideConfirmationModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="text-gray-700 text-center">
                    <p class="font-semibold mb-4">Are you sure you want to submit your ballot?</p>
                    <p>Your vote cannot be changed after submission.</p>
                </div>
                <div class="mt-6 flex justify-center space-x-4">
                    <button type="button" class="bg-gray-200 text-gray-700 font-semibold py-2 px-6 rounded-full hover:bg-gray-300 transition-colors" onclick="hideConfirmationModal()">
                        Cancel
                    </button>
                    <button type="button" id="confirmSubmitBtn" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors">
                        Confirm & Submit
                    </button>
                </div>
            </div>
        </div>

        @foreach($positions as $position)
            <div id="position-{{ $position->id }}" class="bg-white rounded-3xl shadow-xl p-6 sm:p-8 border border-gray-200" data-min-select="{{ $position->min_select }}" data-max-select="{{ $position->max_select }}">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $position->title }}</h2>
                        <p class="mt-1 text-gray-600">{{ $position->description }}</p>
                        <p class="mt-2 text-sm text-gray-500 font-semibold">
                            @if($position->max_select == 1)
                                Select one candidate.
                            @else
                                Select between {{ $position->min_select }} and {{ $position->max_select }} candidates.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($position->candidates as $candidate)
                        <label for="candidate-{{ $candidate->id }}" class="flex flex-col p-6 border-2 rounded-2xl cursor-pointer transition-all duration-200 hover:shadow-lg"
                            data-type="{{ $position->max_select == 1 ? 'radio' : 'checkbox' }}"
                            data-position-id="{{ $position->id }}">

                            <!-- Candidate Photo -->
                            <div class="flex items-center space-x-4 mb-4">
                                <img src="{{ $candidate->photo_url ?? 'https://placehold.co/80x80/E2E8F0/1A202C?text=Photo' }}"
                                    alt="{{ $candidate->name }}"
                                    class="w-20 h-20 object-cover rounded-full shadow-md border-2 border-white">
                                <div class="flex-1">
                                    @if($position->max_select == 1)
                                        <input type="radio" name="selections[{{ $position->id }}]" value="{{ $candidate->id }}" id="candidate-{{ $candidate->id }}" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500 checked:bg-blue-600 transition duration-150 ease-in-out">
                                    @else
                                        <input type="checkbox" name="selections[{{ $position->id }}][]" value="{{ $candidate->id }}" id="candidate-{{ $candidate->id }}" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 checked:bg-blue-600 transition duration-150 ease-in-out">
                                    @endif
                                </div>
                            </div>

                            <!-- Candidate Name -->
                            <span class="block text-sm font-bold text-gray-900 mb-2">{{ $candidate->name }}</span>

                            <!-- Candidate Manifesto -->
                            @if($candidate->manifesto)
                                <div class="text-sm text-gray-600 mt-2">
                                    <h4 class="font-semibold text-gray-700">Manifesto:</h4>
                                    <p class="mt-1 leading-relaxed">{{ Str::limit($candidate->manifesto, 150) }}</p>
                                </div>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-center pt-8">
            <button type="submit" id="submitBtn" class="bg-blue-600 text-white font-bold py-4 px-12 rounded-full shadow-lg hover:bg-blue-700 transition-transform transform hover:scale-105 active:scale-95">
                Submit Ballot
            </button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-6 mt-auto" >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'PEVA Vote') }}. All rights reserved.</p>
        <p class="mt-2">
            <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            <span class="mx-2">|</span>
            <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
        </p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Style the selection cards on initial load
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            const label = input.closest('label');
            if (input.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
            } else {
                label.classList.add('border-gray-200');
            }
        });

        // Add change event listeners for styling
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', () => {
                const label = input.closest('label');
                const positionId = label.getAttribute('data-position-id');
                const inputType = label.getAttribute('data-type');

                // Handle radio buttons (only one can be selected per position)
                if (inputType === 'radio') {
                    document.querySelectorAll(`label[data-position-id="${positionId}"]`).forEach(otherLabel => {
                        otherLabel.classList.remove('border-blue-500', 'bg-blue-50');
                        otherLabel.classList.add('border-gray-200');
                    });
                }

                // Style the selected label
                if (input.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-200');
                }
            });
        });

        // Handle form submission and validation
        document.getElementById('votingForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Stop form submission for now

            let errors = [];
            let positions = document.querySelectorAll('[id^="position-"]');

            positions.forEach(position => {
                const min = parseInt(position.getAttribute('data-min-select'));
                const max = parseInt(position.getAttribute('data-max-select'));
                const positionTitle = position.querySelector('h2').textContent;

                const selectedCandidates = position.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
                const selectedCount = selectedCandidates.length;

                if (selectedCount < min) {
                    errors.push(`Please select at least ${min} candidate(s) for "${positionTitle}".`);
                }

                if (selectedCount > max) {
                    errors.push(`You have selected too many candidates for "${positionTitle}". Please select a maximum of ${max}.`);
                }
            });

            if (errors.length > 0) {
                showErrorModal(errors);
            } else {
                showConfirmationModal();
            }
        });

        // Add a click listener to the confirmation button to submit the form
        document.getElementById('confirmSubmitBtn').addEventListener('click', () => {
            document.getElementById('votingForm').submit();
        });
    });

    // Show and hide error modal
    function showErrorModal(errorMessages) {
        const modal = document.getElementById('errorModal');
        const modalBody = document.getElementById('errorModalBody');
        const modalContent = modal.querySelector('div');

        modalBody.innerHTML = '';
        errorMessages.forEach(msg => {
            const p = document.createElement('p');
            p.textContent = msg;
            modalBody.appendChild(p);
        });

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideErrorModal() {
        const modal = document.getElementById('errorModal');
        const modalContent = modal.querySelector('div');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Show and hide confirmation modal
    function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('div');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('div');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
