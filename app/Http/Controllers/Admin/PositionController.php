<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePositionRequest;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display positions for an election.
     */
    public function index(Election $election)
    {
        // dd($election);
        $this->authorize('update', $election);

        $positions = $election->positions()->with('candidates')->get();
        return view('admin.positions.index', compact('election', 'positions'));
    }

    /**
     * Store a new position.
     */
    public function store(CreatePositionRequest $request, Election $election)
    {
        $this->authorize('update', $election);

        if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'New Positions cannot be added to an Open or Closed Election.');
        }

        $position = $election->positions()->create($request->validated());

        return back()->with('success', 'Position created successfully.');
    }

    /**
     * Update a position.
     */
    public function update(CreatePositionRequest $request, Election $election, Position $position)
    {
        $this->authorize('update', $election);



        if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'Positions cannot be edited for an Open or Closed Election.');
        }

        $position->update($request->validated());

        return back()->with('success', 'Position updated successfully.');
    }

    /**
     * Delete a position.
     */
    public function destroy(Election $election, Position $position)
    {
        $this->authorize('update', $election);

         if ($election->status != 'draft' && $election->status != 'scheduled') {

            return back()->with('error', 'Positions cannot be deleted for an Open or Closed Election.');
        }

        $position->delete();

        return back()->with('success', 'Position deleted successfully.');
    }
}
