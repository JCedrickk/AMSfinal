<!DOCTYPE html>
<html>
<head>
    <title>Account Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a2a4a 0%, #2c3e66 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #ddd;
        }
        .button {
            display: inline-block;
            background: #2c3e66;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Alumni Management System!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $user->first_name }} {{ $user->last_name }},</h2>
            <p>Congratulations! Your alumni account has been <strong>approved</strong>.</p>
            <p>You can now log in to the Alumni Management System and start connecting with fellow alumni.</p>
            
            <p><strong>Your Account Details:</strong></p>
            <ul>
                <li>Email: {{ $user->email }}</li>
                <li>Role: {{ ucfirst($user->role) }}</li>
            </ul>
            
            <center>
                <a href="{{ url('/login') }}" class="button">Click Here to Login</a>
            </center>
            
            <p>If you have any questions or concerns, please contact our support team.</p>
            <p>Best regards,<br>Alumni Management Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Alumni Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>