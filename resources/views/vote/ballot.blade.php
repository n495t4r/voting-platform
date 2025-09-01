<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vote - {{ $election->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
     Sticky Header 
    <header class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">{{ $election->title }}</h1>
                    <p class="text-sm text-gray-600">{{ $voter->full_name }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $positions->count() }} {{ Str::plural('Position', $positions->count()) }}
                    </div>
                    @if($existingBallot && $canRevote)
                        <div class="text-xs text-orange-600">
                            Revoting Allowed
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

     Main Content 
    <main class="max-w-4xl mx-auto px-4 py-6">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($existingBallot)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            You have already voted in this election.
                            @if($canRevote)
                                You can change your vote until the election closes.
                            @else
                                Your vote has been recorded and cannot be changed.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

         Voting Form 
        <form method="POST" action="{{ route('vote.submit', $token) }}" id="voting-form">
            @csrf

             Positions 
            <div class="space-y-6">
                @foreach($positions as $position)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                         Position Header 
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $position->title }}</h2>
                                @if($position->description)
                                    <p class="text-gray-600 mt-1">{{ $position->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    Select {{ $position->min_select }}
                                    @if($position->max_select > $position->min_select)
                                        - {{ $position->max_select }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $position->candidates->count() }} {{ Str::plural('candidate', $position->candidates->count()) }}
                                </div>
                            </div>
                        </div>

                         Candidates 
                        <div class="space-y-3">
                            @foreach($position->candidates as $candidate)
                                <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input 
                                        type="{{ $position->max_select > 1 ? 'checkbox' : 'radio' }}" 
                                        name="selections[{{ $position->id }}]{{ $position->max_select > 1 ? '[]' : '' }}" 
                                        value="{{ $candidate->id }}"
                                        class="mt-1 h-5 w-5 text-blue-600 border-gray-300 rounded {{ $position->max_select > 1 ? '' : 'rounded-full' }} focus:ring-blue-500"
                                        data-position="{{ $position->id }}"
                                        data-min="{{ $position->min_select }}"
                                        data-max="{{ $position->max_select }}"
                                    >
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-start">
                                            @if($candidate->photo_path)
                                                <img src="{{ $candidate->photo_url }}" 
                                                     alt="{{ $candidate->name }}" 
                                                     class="w-12 h-12 rounded-full object-cover mr-4">
                                            @endif
                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $candidate->name }}</h3>
                                                @if($candidate->manifesto)
                                                    <p class="text-gray-600 text-sm mt-1 line-clamp-3">{{ $candidate->manifesto }}</p>
                                                    @if(strlen($candidate->manifesto) > 150)
                                                        <button type="button" 
                                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-1"
                                                                onclick="toggleManifesto(this)">
                                                            Read more
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                         Selection Counter 
                        <div class="mt-4 text-sm text-gray-600" id="counter-{{ $position->id }}">
                            <span class="selected-count">0</span> of {{ $position->max_select }} selected
                            @if($position->min_select > 1)
                                (minimum {{ $position->min_select }} required)
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

             Submit Section 
            <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 mt-8 -mx-4">
                <div class="max-w-4xl mx-auto">
                    @if(!$canRevote && $existingBallot)
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">You have already voted in this election.</p>
                            <a href="{{ route('vote.receipt', $existingBallot->ballot_uid) }}" 
                               class="btn-mobile bg-blue-600 text-white hover:bg-blue-700">
                                View Receipt
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col gap-3">
                            <button type="button" 
                                    onclick="showConfirmModal()" 
                                    id="submit-btn"
                                    class="btn-mobile bg-green-600 text-white hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                @if($existingBallot)
                                    Update My Vote
                                @else
                                    Submit My Vote
                                @endif
                            </button>
                            <p class="text-xs text-gray-500 text-center">
                                @if($canRevote)
                                    You can change your vote until the election closes.
                                @else
                                    This action cannot be undone. Please review your selections carefully.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </main>

     Confirmation Modal 
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Your Vote</h3>
                <p class="text-gray-600 mb-6">
                    @if($canRevote)
                        Are you sure you want to update your vote? This will replace your previous selections.
                    @else
                        Are you sure you want to submit your vote? This action cannot be undone.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            onclick="submitVote()" 
                            class="btn-mobile bg-green-600 text-white hover:bg-green-700">
                        Yes, Submit Vote
                    </button>
                    <button type="button" 
                            onclick="hideConfirmModal()" 
                            class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Selection validation and counting
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('voting-form');
            const submitBtn = document.getElementById('submit-btn');
            
            // Update counters and validation
            function updateValidation() {
                let allValid = true;
                
                @foreach($positions as $position)
                    const position{{ $position->id }} = document.querySelectorAll('input[data-position="{{ $position->id }}"]:checked');
                    const counter{{ $position->id }} = document.getElementById('counter-{{ $position->id }}');
                    const selectedCount = position{{ $position->id }}.length;
                    
                    counter{{ $position->id }}.querySelector('.selected-count').textContent = selectedCount;
                    
                    if (selectedCount < {{ $position->min_select }} || selectedCount > {{ $position->max_select }}) {
                        allValid = false;
                        counter{{ $position->id }}.classList.add('text-red-600');
                        counter{{ $position->id }}.classList.remove('text-gray-600');
                    } else {
                        counter{{ $position->id }}.classList.remove('text-red-600');
                        counter{{ $position->id }}.classList.add('text-gray-600');
                    }
                @endforeach
                
                submitBtn.disabled = !allValid;
            }
            
            // Add event listeners to all inputs
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                input.addEventListener('change', updateValidation);
            });
            
            // Initial validation
            updateValidation();
        });

        // Modal functions
        function showConfirmModal() {
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        function hideConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
        }

        function submitVote() {
            document.getElementById('voting-form').submit();
        }

        // Manifesto toggle
        function toggleManifesto(button) {
            const manifesto = button.previousElementSibling;
            if (manifesto.classList.contains('line-clamp-3')) {
                manifesto.classList.remove('line-clamp-3');
                button.textContent = 'Read less';
            } else {
                manifesto.classList.add('line-clamp-3');
                button.textContent = 'Read more';
            }
        }
    </script>
</body>
</html>
