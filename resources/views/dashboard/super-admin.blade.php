@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
        <p class="text-gray-600">Manage the entire voting platform</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
         System Overview 
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Overview</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Elections:</span>
                    <span class="font-medium">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Active Users:</span>
                    <span class="font-medium">1</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Votes Cast:</span>
                    <span class="font-medium">0</span>
                </div>
            </div>
        </div>

         Quick Actions 
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="block w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition">
                    Manage Users
                </a>
                <a href="#" class="block w-full text-left px-4 py-2 bg-green-50 text-green-700 rounded-md hover:bg-green-100 transition">
                    Feature Flags
                </a>
                <a href="#" class="block w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded-md hover:bg-purple-100 transition">
                    System Settings
                </a>
            </div>
        </div>

         Recent Activity 
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
            <p class="text-gray-500 text-sm">No recent activity</p>
        </div>
    </div>
</div>
@endsection
