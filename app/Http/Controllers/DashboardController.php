<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        return match ($user->role) {
            'super_admin' => $this->superAdminDashboard($user),
            'admin', 'committee' => $this->adminDashboard($user),
            'voter' => $this->voterDashboard($user),
            default => view('dashboard.default'),
        };
    }

    /**
     * Super admin dashboard.
     */
    private function superAdminDashboard($user): View
    {
        // TODO: Add super admin specific data
        return view('dashboard.super-admin', compact('user'));
    }

    /**
     * Admin/Committee dashboard.
     */
    private function adminDashboard($user): View
    {
        // TODO: Add admin specific data
        return view('dashboard.admin', compact('user'));
    }

    /**
     * Voter dashboard.
     */
    private function voterDashboard($user): View
    {
        // TODO: Add voter specific data
        return view('dashboard.voter', compact('user'));
    }
}
