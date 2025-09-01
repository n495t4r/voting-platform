@extends('layouts.welcome')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Voter Dashboard</h1>
        <p class="text-gray-600">View your voting opportunities</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
         Available Elections
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Elections</h3>
            <p class="text-gray-500 text-sm">No elections available for voting</p>
        </div>

         Voting History
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Voting History</h3>
            <p class="text-gray-500 text-sm">No voting history yet</p>
        </div>
    </div>
</div>
@endsection
