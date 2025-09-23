<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <!-- Main Container Table -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 0;">

                <!-- Email Content Wrapper -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                    <tr>
                        <td align="center" style="padding: 40px;">

                            <!-- Header -->
                            <h1 style="color: #333333; font-size: 28px; margin: 0 0 10px 0; font-weight: bold;">
                                Voting Invitation
                            </h1>
                            <p style="color: #666666; font-size: 16px; line-height: 1.5; margin: 0 0 25px 0;">
                                Hello, {{ $voterName }}!
                            </p>

                            <!-- Main Content -->
                            <p style="color: #666666; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0; text-align: center;">
                                You have been cordially invited to participate in this important election. Your vote helps shape the future.
                            </p>

                            <!-- Election Details (Minimalist Modern) -->
                            <div style="margin-bottom: 24px; text-align: center;">
                                <div style="font-size: 18px; font-weight: 600; color: #222; margin-bottom: 8px;">
                                    {{ $election->title }}
                                </div>
                                <div style="gap: 16px; font-size: 14px; color: #555;">
                                    <span>
                                        <strong>Starts:</strong> {{ \Carbon\Carbon::parse($election->starts_at)->format('F jS, Y h:i A') }}
                                    </span>
                                    <br>
                                    <span>
                                        <strong>Ends:</strong> {{ \Carbon\Carbon::parse($election->ends_at)->format('F jS, Y h:i A') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Call to Action Button -->
                            <div style="text-align: center; margin-bottom: 25px;">
                                <a href="{{ $votingUrl }}" style="display: inline-block; padding: 12px 24px; font-size: 16px; font-weight: bold; color: #ffffff; background-color: #007bff; border-radius: 25px; text-decoration: none;">
                                    Cast Your Vote
                                </a>
                            </div>

                            <p style="color: #666666; font-size: 16px; line-height: 1.6; margin: 0; text-align: center;">
                                Thank you for your participation!
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="color: #aaaaaa; font-size: 12px; line-height: 1.5;">
                            &copy; {{ date('Y') }} {{ config('app.name', 'PEVA Vote') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
