<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Receipt - {{ $election->title }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
             Success Icon 
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Vote Submitted!</h1>
                <p class="text-gray-600 mt-2">Your vote has been recorded successfully</p>
            </div>

             Receipt Card 
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vote Receipt</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Election:</span>
                        <span class="font-medium text-gray-900">{{ $election->title }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Receipt ID:</span>
                        <span class="font-mono text-gray-900">{{ $ballot->ballot_uid }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Submitted:</span>
                        <span class="text-gray-900">{{ $ballot->submitted_at->format('M j, Y g:i A') }}</span>
                    </div>
                    
                    @if($ballot->revision > 1)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Revision:</span>
                            <span class="text-gray-900">#{{ $ballot->revision }}</span>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 pt-4 mt-4">
                    <p class="text-xs text-gray-500">
                        This receipt confirms your vote was recorded. Keep this for your records. 
                        Your individual selections remain private and anonymous.
                    </p>
                </div>
            </div>

             Actions 
            <div class="mt-6 space-y-3">
                <button onclick="window.print()" 
                        class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 w-full">
                    Print Receipt
                </button>
                
                @auth
                    <a href="{{ route('voter.dashboard') }}" 
                       class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 w-full text-center">
                        Back to Dashboard
                    </a>
                @endauth
            </div>

             Election Info 
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Election ends: {{ $election->ends_at->format('M j, Y g:i A') }}
                </p>
                @if($election->isClosed())
                    <p class="text-sm text-green-600 font-medium mt-1">
                        Election has ended. Results will be available soon.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            body { background: white; }
            .no-print { display: none; }
        }
    </style>
</body>
</html>
