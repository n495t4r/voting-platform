<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voting Error</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center">
             Error Icon 
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">Unable to Access Voting</h1>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <p class="text-gray-700">{{ $message }}</p>
            </div>

            @if($canRetry)
                <div class="space-y-3">
                    <button onclick="window.location.reload()" 
                            class="btn-mobile bg-blue-600 text-white hover:bg-blue-700 w-full">
                        Try Again
                    </button>
                    <p class="text-sm text-gray-500">
                        If the problem persists, please contact the election administrator.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    <p class="text-sm text-gray-500">
                        Please contact the election administrator for assistance.
                    </p>
                    <a href="{{ route('home') }}" 
                       class="btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 w-full">
                        Return Home
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
