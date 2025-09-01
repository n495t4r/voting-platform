<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Voting Platform') }}</title>

     Scripts 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
         Navigation 
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-900">
                            Voting Platform
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-sm text-gray-600">
                                {{ auth()->user()->name }}
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2">
                                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                                </span>
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

         Page Content 
        <main class="py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
