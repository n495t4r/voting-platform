<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitBallotRequest;
use App\Services\BallotService;
use App\Services\FeatureFlagService;
use App\Services\TokenService;
use App\Services\VoterOnboardingService;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    public function __construct(
        private TokenService $tokenService,
        private BallotService $ballotService,
        private VoterOnboardingService $voterOnboardingService,
        private FeatureFlagService $featureFlagService
    ) {}

    /**
     * Show the voting ballot.
     */
    public function show(Request $request, string $token)
    {
        try {
            // Validate token
            $dto = $this->tokenService->validate($token);

            // Check if election is open
            if (!$dto->election->isOpen()) {
                return view('vote.closed', ['election' => $dto->election, 'voter'=> $dto->voter]);
            }

            // Mark voter as verified if not already
            if ($dto->voter->status === 'invited') {
                $this->voterOnboardingService->markVerified($dto->voter);
            }

            // Build ballot data
            $ballotData = $this->ballotService->buildForVoter($dto->voter, $dto->election);

            return view('vote.ballot', [
                'token' => $token,
                'voter' => $dto->voter,
                'election' => $dto->election,
                'positions' => $ballotData['positions'],
                'canRevote' => $ballotData['can_revote'],
                'existingBallot' => $ballotData['existing_ballot'],
                'signature' => $request->signature
            ]);

        } catch (\Exception $e) {
            return view('vote.error', [
                'message' => $e->getMessage(),
                'canRetry' => false,
            ]);
        }
    }

    /**
     * Submit the ballot.
     */
    public function submit(SubmitBallotRequest $request, string $token)
    {
        try {
            // Validate token again
            $dto = $this->tokenService->validate($token);

            // Check if election is still open
            if (!$dto->election->isOpen()) {
                return back()->with('error', 'Voting has closed for this election.');
            }

            // Submit ballot
            $ballot = $this->ballotService->submit(
                $dto->voter,
                $dto->election,
                $request->validated()['selections']
            );

            // Consume token if single-use
            if (!$this->featureFlagService->isEnabled('allow_multi_use_link', $dto->election)) {
                $this->tokenService->consume($dto->token);
            }

            return redirect()->route('vote.receipt', $ballot->ballot_uid);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show voting receipt.
     */
    public function receipt(string $ballotUid)
    {
        $ballot = \App\Models\Ballot::where('ballot_uid', $ballotUid)->first();

        if (!$ballot) {
            abort(404, 'Receipt not found.');
        }

        return view('vote.receipt', [
            'ballot' => $ballot,
            'election' => $ballot->election,
        ]);
    }

    /**
     * Show voting status/dashboard for authenticated voters.
     */
    public function status(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'voter') {
            return redirect()->route('login');
        }

        // Get voter records for this user (by email)
        $voters = \App\Models\Voter::with(['election', 'ballots'])
            ->where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vote.status', compact('voters'));
    }
}
