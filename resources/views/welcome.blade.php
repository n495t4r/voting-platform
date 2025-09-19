@extends('layouts.voting')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Online Voting Platform
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Secure, transparent, and accessible voting for your organization
        </p>

        @guest
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md text-lg hover:bg-blue-700 transition">
                Get Started
            </a>
        @else
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md text-lg hover:bg-blue-700 transition">
                Go to Dashboard
            </a>
        @endguest
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="text-center">
            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure</h3>
            <p class="text-gray-600">End-to-end encryption and audit trails ensure vote integrity</p>
        </div>

        <div class="text-center">
            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparent</h3>
            <p class="text-gray-600">Real-time monitoring and verifiable results</p>
        </div>

        <div class="text-center">
            <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Mobile-First</h3>
            <p class="text-gray-600">Optimized for mobile devices with intuitive interface</p>
        </div>
    </div>
</div>
@endsection
