<?php

namespace App\Services;

use App\Mail\SendEmailInvitation;
use Illuminate\Support\Facades\Mail;
use Exception;
use Log;

class EmailService
{
    /**
     * Sends a voting invitation via email.
     *
     * @param string $recipientEmail The recipient's email address.
     * @param string $voterName The name of the voter.
     * @return bool True on success, false on failure.
     */
    public function sendInvitation(string $recipientEmail, string $voterName, $election, $votingUrl): bool
    {
        try {
            Mail::to($recipientEmail)->send(new SendEmailInvitation($voterName, $election, $votingUrl));

            // Log a success message for your records
            Log::info("Voting invitation email sent successfully to {$recipientEmail}.");

            return true;
        } catch (Exception $e) {
            // Log the error message
            Log::error("Failed to send voting invitation email to {$recipientEmail}: " . $e->getMessage());

            return false;
        }
    }
}
