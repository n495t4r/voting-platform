<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Voter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class NotificationService
{
    /**
     * Send voting invitation to a voter.
     */
    public function sendVotingInvitation(Voter $voter, Election $election, string $token): void
    {
        $votingUrl = $this->generateVotingUrl($token);

        if ($voter->email) {
            $this->sendEmailInvitation($voter, $election, $votingUrl);
        } elseif ($voter->phone) {
            $this->sendSmsInvitation($voter, $election, $votingUrl);
        }

        // // Securely store the $votingUrl for retrieval or resending reminders
        // Voter::updateOrCreate(
        //     [
        //         'voter_id' => $voter->id,
        //         'election_id' => $election->id,
        //     ],
        //     [
        //         'voting_url' => encrypt($votingUrl),
        //     ]
        // );
    }

    /**
     * Send voting reminder.
     */
    public function sendVotingReminder(Voter $voter, Election $election, string $token): void
    {
        $votingUrl = $this->generateVotingUrl($token);

        if ($voter->email) {
            $this->sendEmailReminder($voter, $election, $votingUrl);
        }
    }

    /**
     * Send voting receipt.
     */
    public function sendVotingReceipt(Voter $voter, Election $election, string $ballotUid): void
    {
        if ($voter->email) {
            $this->sendEmailReceipt($voter, $election, $ballotUid);
        }
    }

    /**
     * Generate secure voting URL.
     */
    private function generateVotingUrl(string $token): string
    {
        return URL::signedRoute('vote.show', ['token' => $token]);
    }

    /**
     * Send email invitation.
     */
    private function sendEmailInvitation(Voter $voter, Election $election, string $votingUrl): void
    {
        // TODO: Implement actual email sending
        // This would typically use Laravel's Mail facade with a Mailable class

        // For now, just log the action
        logger()->info('Email invitation sent', [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'email' => $voter->email,
            'token' => $votingUrl
        ]);
    }

    /**
     * Send SMS invitation.
     */
    private function sendSmsInvitation(Voter $voter, Election $election, string $votingUrl): void
    {
        // TODO: Implement SMS sending (Twilio, etc.)

        logger()->info('SMS invitation sent', [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'phone' => $voter->phone,
            'token' => $votingUrl

        ]);
    }

    /**
     * Send email reminder.
     */
    private function sendEmailReminder(Voter $voter, Election $election, string $votingUrl): void
    {
        logger()->info('Email reminder sent', [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'email' => $voter->email,
            'token' => $votingUrl

        ]);
    }

    /**
     * Send email receipt.
     */
    private function sendEmailReceipt(Voter $voter, Election $election, string $ballotUid): void
    {
        logger()->info('Email receipt sent', [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
            'ballot_uid' => $ballotUid,
        ]);
    }
}
