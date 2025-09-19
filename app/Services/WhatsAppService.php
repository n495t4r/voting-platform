<?php

namespace App\Services;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use GuzzleHttp\Client as GuzzleClient;
use Twilio\Http\CurlClient; // Import the correct class


class WhatsAppService
{
    /**
     * Sends a voting invitation via WhatsApp.
     *
     * @param  string  $phoneNumber The recipient's phone number in E.164 format (e.g., +2348012345678).
     * @param  string  $voterName The name of the voter to personalize the message.
     * @return \Illuminate\Http\Response
     */
    public function sendWhatsAppInvitation()
    {
        // Retrieve credentials from .env
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_TOKEN');
        // $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');

        $twilioWhatsAppNumber ="whatsapp:+17282156682";


        // $twilio = new Client($sid, $token);

        $recipientNumber = "+2347066994652";
        $voterName = "John Doe";

        // $recipientNumber = $request->input('phone_number');
        // $voterName = $request->input('name');

        // You should have a template pre-approved by WhatsApp for this message.
        // For example, "voting_invitation".
        $messageBody = "Hello {$voterName},\n\nYou are invited to vote in our upcoming election. Please use the following link to cast your vote:\n\n[Link to your voting platform]\n\nThank you!";

        try {

                // Create a custom cURL client with SSL verification disabled
            // This is for local development ONLY. Do NOT use this in production.
            $httpClient = new CurlClient([
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ]);

            // Initialize Twilio client and pass the custom HTTP client
            $twilio = new Client($twilioSid, $twilioToken, null, null, $httpClient);
            // Send the message
            $message = $twilio->messages
                ->create(
                    "whatsapp:{$recipientNumber}", // The recipient's number
                    [
                        "from" => $twilioWhatsAppNumber, // Your Twilio WhatsApp number
                        "body" => $messageBody,
                    ]
                );

            // Log the message SID for your records
            \Log::info("Message SID: " . $message->sid);

            return response()->json(['message' => 'Invitation sent successfully!', 'sid' => $message->sid], 200);

        } catch (\Exception $e) {
            \Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return response()->json(['error' => 'Failed to send invitation. Please check the phone number and your configuration.'], 500);
        }
    }
}
