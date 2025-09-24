<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportVotersRequest;
use App\Models\Election;
use App\Models\Voter;
use App\Services\VoterOnboardingService;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function __construct(
        private VoterOnboardingService $voterOnboardingService
    ) {
    }

    /**
     * Display voters for an election.
     */
    public function index(Election $election, Request $request)
    {
        $this->authorize('manageVoters', $election);

        $voters = $election->voters()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('full_name', 'like', "%{$request->search}%"))
            ->orderBy('full_name')
            ->paginate(20);

        return view('admin.voters.index', compact('election', 'voters'));
    }

    /**
     * Show import form.
     */
    public function import(Election $election)
    {
        $this->authorize('manageVoters', $election);
        return view('admin.voters.import', compact('election'));
    }

    /**
     * Process voter import.
     */
    public function processImport(ImportVotersRequest $request, Election $election)
    {
        $this->authorize('manageVoters', $election);

        $file = $request->file('voters_file');
        $votersData = $this->parseVotersFile($file);

        $result = $this->voterOnboardingService->importVoters($election, $votersData);

        $message = "Import completed: {$result['imported']} imported, {$result['skipped']} skipped";
        $status = "success";
        if (!empty($result['errors'])) {
            $message .= ". " . count($result['errors']) . " errors occurred.";
            $status = "error";
        }

        return redirect()
            ->route('admin.voters.index', $election)
            ->with($status, $message)
            ->with('import_errors', $result['errors']);
    }

    /**
     * Send invitations to voters.
     */
    public function sendInvitations(Election $election, Request $request)
    {
        $this->authorize('manageVoters', $election);

        $singleVoter = $request->voter_id ? true : false;

        $voters = $request->voter_ids
                ? $election->voters()->whereIn('id', $request->voter_ids)->get()
                : null;

        // dd($single, " ID: ". $request->voter_id);
        if ($singleVoter) {

            // dd($request->voter_id);
            $voter = $request->voter_id
                ? $election->voters()->where('id', $request->voter_id)->first()
                : null;
            $sent = $this->voterOnboardingService->sendToVoter($election, $voter);
        }else{
            $sent = $this->voterOnboardingService->sendInvitations($election, $voters);
        }

        if(!$sent){
            return back()->with('error', "Invitations not sent to voter(s).");
        }

        return back()->with('success', "Invitations sent to {$sent} voter(s).");
    }


    public function revokeInvitations(Election $election, Voter $voter)
    {
        $this->authorize('manageVoters', $election);

        if($voter->status === "voted"){
            return back()->with('error', "{$voter->full_name}, voted already!");
        }

        $this->voterOnboardingService->revokeTokens( $voter, $election);
        return back()->with('success', "Invitations revoked for {$voter->full_name}.");
    }

    /**
     * Parse uploaded voters file.
     */
    private function parseVotersFile($file): array
    {
        $extension = $file->getClientOriginalExtension();
        $path = $file->getRealPath();

        if ($extension === 'csv') {
            return $this->parseCsv($path);
        }

        throw new \InvalidArgumentException('Unsupported file format. Please upload a CSV file.');
    }

    /**
     * Parse CSV file.
     */
    private function parseCsv(string $path): array
    {
        $data = [];
        $headers = null;

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if ($headers === null) {
                    $headers = array_map('strtolower', $row);
                    continue;
                }

                $data[] = array_combine($headers, $row);
            }
            fclose($handle);
        }

        return $data;
    }



}
