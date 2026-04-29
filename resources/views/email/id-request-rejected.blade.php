<!DOCTYPE html>
<html>
<head>
    <title>Alumni ID Request Update</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Alumni ID Request Update</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $user->first_name }} {{ $user->last_name }},</h2>
            <p>Your Alumni ID request has been <strong style="color: #dc2626;">rejected</strong>.</p>
            @if($reason)
                <p><strong>Reason:</strong> {{ $reason }}</p>
            @endif
            <p>You may submit a new request after addressing the issue mentioned above.</p>
            <center>
                <a href="{{ url('/alumni-id/request') }}" class="button">Submit New Request</a>
            </center>
            <p>If you have questions, please contact our support team.</p>
            <p>Best regards,<br>Alumni Management Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Alumni Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>