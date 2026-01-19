<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 48px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            font-size: 72px;
            color: #667eea;
            margin-bottom: 16px;
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 16px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        a {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }
        a:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>500</h1>
        <h2>Server Error</h2>
        <p>Something went wrong on our end. We've been notified and are working to fix it. Please try again later.</p>
        <a href="{{ url('/') }}">Go to Homepage</a>
    </div>
</body>
</html>
