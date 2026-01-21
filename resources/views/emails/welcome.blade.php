<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to AIHRM!</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <p>Welcome to AIHRM - Your AI-powered Human Resource Management System!</p>
        
        <p>Your account has been successfully created. Here are your login details:</p>
        
        <p>
            <strong>Email:</strong> {{ $user->email }}<br>
            <strong>Temporary Password:</strong> {{ $password }}
        </p>
        
        <p><strong>Important:</strong> Please change your password after your first login for security purposes.</p>
        
        <a href="{{ url('/login') }}" class="button">Login to AIHRM</a>
        
        <p style="margin-top: 30px;">If you have any questions, please contact your HR administrator.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} AIHRM. All rights reserved.</p>
    </div>
</body>
</html>
