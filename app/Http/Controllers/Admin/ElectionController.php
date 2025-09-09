<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateElectionRequest;
use App\Http\Requests\UpdateElectionRequest;
use App\Models\Election;
use App\Models\Organization;
use App\Services\ElectionService;
use App\Services\ResultsService;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function __construct(
        private ElectionService $electionService,
        private ResultsService $resultsService
    ) {
        $this->authorizeResource(Election::class, 'election');
    }

    /**
     * Display a listing of elections.
     */
    public function index(Request $request)
    {
        $elections = Election::with('organization')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.elections.index', compact('elections'));
    }

    /**
     * Show the form for creating a new election.
     */
    public function create()
    {
        $organizations = Organization::all();
        return view('admin.elections.create', compact('organizations'));
    }

    /**
     * Store a newly created election.
     */
    public function store(CreateElectionRequest $request)
    {
        $election = $this->electionService->create($request->validated());

        return redirect()
            ->route('admin.elections.show', $election)
            ->with('success', 'Election created successfully.');
    }

    /**
     * Display the specified election.
     */
    public function show(Election $election)
    {
        $election->load(['positions.candidates', 'voters']);
        $turnoutStats = $this->electionService->getTurnoutStats($election);

        return view('admin.elections.show', compact('election', 'turnoutStats'));
    }

    /**
     * Show the form for editing the election.
     */
    public function edit(Election $election)
    {
        $organizations = Organization::all();
        return view('admin.elections.edit', compact('election', 'organizations'));
    }

    /**
     * Update the specified election.
     */
    public function update(UpdateElectionRequest $request, Election $election)
    {
        $election->update($request->validated());

        return redirect()
            ->route('admin.elections.show', $election)
            ->with('success', 'Election updated successfully.');
    }

    /**
     * Open an election for voting.
     */
    public function open(Election $election)
    {
        $this->authorize('update', $election);

        try {
            $this->electionService->open($election);
            return back()->with('success', 'Election opened successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Close an election.
     */
    public function close(Election $election)
    {
        $this->authorize('update', $election);

        try {
            $this->electionService->close($election);
            return back()->with('success', 'Election closed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show election results.
     */
    public function results(Election $election)
    {
        $this->authorize('viewResults', $election);

        if (!$election->isClosed()) {
            return back()->with('error', 'Results are only available for closed elections.');
        }

        $results = $this->resultsService->calculateResults($election);

        // dd($results);

        return view('admin.elections.results', compact('election', 'results'));
    }

    /**
     * Export election results.
     */
    public function exportResults(Election $election)
    {
        $this->authorize('viewResults', $election);

        $csv = $this->resultsService->exportToCsv($election);
        $filename = "election-{$election->slug}-results.csv";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
