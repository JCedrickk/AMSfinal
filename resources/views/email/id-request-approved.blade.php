<!DOCTYPE html>
<html>
<head>
    <title>Alumni ID Request Approved</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #ddd; }
        .id-card { background: linear-gradient(135deg, #1a2a4a 0%, #2c3e66 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0; }
        .button { display: inline-block; background: #2c3e66; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Alumni ID Request Approved!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $user->first_name }} {{ $user->last_name }},</h2>
            <p>Congratulations! Your Alumni ID request has been <strong style="color: #10b981;">approved</strong>.</p>
            
            <div class="id-card">
                <h3>Your Alumni ID Card</h3>
                <p><strong>ID Number:</strong> {{ $idRequest->alumni_id_number }}</p>
                <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                <p><strong>Course:</strong> {{ $user->profile->course ?? 'N/A' }}</p>
                <p><strong>Batch:</strong> {{ $user->profile->year_graduated ?? 'N/A' }}</p>
            </div>
            
            <p><strong>How to claim your physical ID:</strong></p>
            <ul>
                <li>Go to the Alumni Office at the school campus</li>
                <li>Pay the processing fee of ₱100.00</li>
                <li>Bring a valid government-issued ID for verification</li>
                <li>Office hours: Monday-Friday, 8:00 AM - 3:00 PM</li>
            </ul>
            
            <center>
                <a href="{{ url('/alumni-id/request') }}" class="button">View My ID</a>
            </center>
            
            <p>Best regards,<br>Alumni Management Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Alumni Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>