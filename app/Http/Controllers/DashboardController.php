<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Voter;
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
        // Super admin specific data
        $metrics = [
            'totalElections' => \App\Models\Election::count(),
            'activeUsers' => \App\Models\User::count(),
            'totalVotes' => \App\Models\Voter::where('status', 'voted')->count(),
        ];

        $recentActivity = \App\Models\AuditLog::orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $recentElections = \App\Models\Election::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.super-admin', compact('user', 'metrics', 'recentActivity', 'recentElections'));
    }

    /**
     * Admin/Committee dashboard.
     */
    private function adminDashboard($user): View
    {
        // Admin specific data
        $electionCounts = [
            'draft' => \App\Models\Election::countDrafts(),
            'open' => \App\Models\Election::countOpen(),
            'closed' => \App\Models\Election::countClosed(),
        ];

        // dd($electionCounts);

        $recentElections = \App\Models\Election::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // dd($recentElections);

        return view('dashboard.admin', compact('user', 'electionCounts', 'recentElections'));

        // return view('dashboard.admin', compact('user'));
    }

    /**
     * Voter dashboard.
     */
    private function voterDashboard($user): View
    {
        // Voter specific data
        // $voter = \App\Models\Voter::with(['election.organization', 'election.voterTokens', 'ballots'])
        //     ->where('email', $user->email)
        //     ->orWhere('phone', $user->phone)
        //     ->get();
        // dd($voter);

        $invitedElectionIds = Voter::where(function ($query) use ($user) {
            $query->where('email', $user->email)
                  ->orWhere('phone', $user->phone);
            })
            ->whereIn('status', ['invited','verified','voted','draft'])
            ->pluck('election_id')
            ->toArray();

        $votedElection_ids = Voter::where(function ($query) use ($user) {
            $query->where('email', $user->email)
              ->orWhere('phone', $user->phone);
            })
            ->where('status', 'voted')
            ->pluck('election_id')
            ->toArray();

        // $availableElections = $voter->filter(fn($v) => $v->status === 'invited' && $v->election->isOpen());

        $availableElections = Election::with(['organization','ballots', 'voterTokens'])
            ->whereIn('id',$invitedElectionIds)
            ->get();

        // dd($availableElections);
        // $votedElections = $voter->filter(fn($v) => $v->status === 'voted');

        $votedElections =  Election::with(['ballots'])->whereIn('id', $votedElection_ids)->get();

        // Pass $voters, $availableElections, and $votedElections to the view
        return view('dashboard.voter', compact('user', 'availableElections', 'votedElections'));

    }
}
