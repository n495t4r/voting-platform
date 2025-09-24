<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    /**
     * Display a list of candidates for a specific position.
     *
     * @param Election $election
     * @param Position $position
     * @return \Illuminate\View\View
     */
    public function index(Election $election, Position $position)
    {
        // dd($position);
        $this->authorize('update', $election);
        $candidates = $position->candidates()->get();
        return view('admin.candidates.index', compact('election', 'position', 'candidates'));
    }

    /**
     * Store a newly created candidate.
     *
     * @param Request $request
     * @param Election $election
     * @param Position $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Election $election, Position $position)
    {
        $this->authorize('update', $election);


        if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'New Candidates cannot be added to an Open or Closed Election.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('photo')) {
            $validatedData['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        $position->candidates()->create([
            'name' => $validatedData['name'],
            'manifesto' => $validatedData['manifesto'] ?? null,
            'photo_path' => $validatedData['photo_path'] ?? null,
        ]);

        return back()->with('success', 'Candidate created successfully.');
    }

    /**
     * Show the form for editing the specified candidate.
     *
     * @param Election $election
     * @param Position $position
     * @param Candidate $candidate
     * @return \Illuminate\View\View
     */
    // public function edit(Election $election, Position $position, Candidate $candidate)
    // {
    //     $this->authorize('update', $election);
    //     return view('admin.candidates.edit', compact('election', 'position', 'candidate'));
    // }

    /**
     * Update the specified candidate in storage.
     *
     * @param Request $request
     * @param Election $election
     * @param Position $position
     * @param Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Election $election, Position $position, Candidate $candidate)
    {
        $this->authorize('update', $election);

        if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'Candidates cannot be edited for an Open or Closed Election.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'manifesto' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($candidate->photo_path) {
                Storage::disk('public')->delete($candidate->photo_path);
            }
            $validatedData['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        $candidate->update([
            'name' => $validatedData['name'],
            'manifesto' => $validatedData['manifesto'] ?? $candidate->manifesto,
            'photo_path' => $validatedData['photo_path'] ?? $candidate->photo_path,
        ]);

        return back()->with('success', 'Candidate updated successfully.');
    }

    /**
     * Remove the specified candidate from storage.
     *
     * @param Election $election
     * @param Position $position
     * @param Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Election $election, Position $position, Candidate $candidate)
    {
        $this->authorize('update', $election);

        if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'Candidates cannot be deleted for an Open or Closed election.');
        }

        if ($candidate->photo_path) {
            Storage::disk('public')->delete($candidate->photo_path);
        }

        $candidate->delete();

        return back()->with('success', 'Candidate deleted successfully.');
    }
}
