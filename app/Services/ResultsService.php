<?php

namespace App\Services;

use App\Models\Election;
use Illuminate\Support\Collection;

class ResultsService
{
    /**
     * Calculate election results.
     */
    public function calculateResults(Election $election): array
    {
        if (!$election->isClosed()) {
            throw new \InvalidArgumentException('Results can only be calculated for closed elections.');
        }

        $positions = $election->positions()->with(['candidates', 'ballotSelections.candidate'])->get();
        $results = [];

        foreach ($positions as $position) {
            $results[$position->id] = $this->calculatePositionResults($position);
        }

        return [
            'election' => $election,
            'positions' => $results,
            'total_ballots' => $election->ballots()->count(),
            'calculated_at' => now(),
        ];
    }

    /**
     * Calculate results for a specific position.
     */
    private function calculatePositionResults($position): array
    {
        // Get all selections for this position from latest ballot revisions only
        $selections = $position->ballotSelections()
            ->whereIn('ballot_id', function ($query) use ($position) {
                $query->select('id')
                    ->from('ballots')
                    ->where('election_id', $position->election_id)
                    ->whereRaw('revision = (SELECT MAX(revision) FROM ballots b2 WHERE b2.voter_id = ballots.voter_id AND b2.election_id = ballots.election_id)');
            })
            ->with('candidate')
            ->get();

        // Count votes per candidate
        $voteCounts = $selections->groupBy('candidate_id')->map(function ($votes) {
            return $votes->count();
        });

        // Build candidate results
        $candidateResults = $position->candidates->map(function ($candidate) use ($voteCounts) {
            return [
                'candidate' => $candidate,
                'votes' => $voteCounts->get($candidate->id, 0),
            ];
        })->sortByDesc('votes')->values();

        // Determine winner(s)
        $maxVotes = $candidateResults->max('votes');
        $winners = $candidateResults->where('votes', $maxVotes);

        return [
            'position' => $position,
            'candidates' => $candidateResults,
            'total_votes' => $selections->count(),
            'winners' => $winners->values(),
            'is_tie' => $winners->count() > 1,
        ];
    }

    /**
     * Export results to CSV format.
     */
    public function exportToCsv(Election $election): string
    {
        $results = $this->calculateResults($election);
        $csv = "Position,Candidate,Votes,Percentage\n";

        foreach ($results['positions'] as $positionResult) {
            $position = $positionResult['position'];
            $totalVotes = $positionResult['total_votes'];

            foreach ($positionResult['candidates'] as $candidateResult) {
                $candidate = $candidateResult['candidate'];
                $votes = $candidateResult['votes'];
                $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) : 0;

                $csv .= sprintf(
                    "%s,%s,%d,%.2f%%\n",
                    $position->title,
                    $candidate->name,
                    $votes,
                    $percentage
                );
            }
        }

        return $csv;
    }
}
