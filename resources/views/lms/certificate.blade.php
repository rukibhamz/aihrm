<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .container {
            width: 100%;
            height: 100%;
            padding: 40px;
            box-sizing: border-box;
            border: 20px solid #000;
            position: relative;
        }
        .inner-border {
            border: 2px solid #000;
            height: 100%;
            padding: 60px;
            text-align: center;
            box-sizing: border-box;
        }
        .header {
            text-transform: uppercase;
            letter-spacing: 15px;
            font-size: 24px;
            font-weight: 1000;
            margin-bottom: 40px;
            border-bottom: 4px solid #000;
            display: inline-block;
            padding-bottom: 15px;
        }
        .sub-header {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 30px;
        }
        .recipient {
            font-size: 48px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 20px;
            display: block;
        }
        .context {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .course-title {
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            padding: 10px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-box {
            display: inline-block;
            width: 250px;
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #000;
            padding-top: 10px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .date {
            font-size: 14px;
            font-weight: bold;
        }
        .seal {
            position: absolute;
            bottom: 60px;
            right: 60px;
            width: 80px;
            height: 80px;
            border: 4px solid #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            transform: rotate(-15deg);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-border">
            <div class="header">AIHRM Platform</div>
            <div class="sub-header">Certificate of Completion</div>
            
            <p class="context">This is to certify that</p>
            
            <div class="recipient">{{ $user->name }}</div>
            
            <p class="context">has successfully completed all requirements for the professional training course:</p>
            
            <div class="course-title">{{ $course->title }}</div>
            
            <p class="context" style="margin-top: 30px;">
                Issued on {{ \Carbon\Carbon::parse($completion->completed_at)->format('F d, Y') }}
                <br>
                Verification ID: AIHRM-{{ strtoupper(substr(md5($completion->id), 0, 8)) }}
            </p>

            <div class="footer">
                <div class="signature-box" style="float: left;">
                    <br>
                    <div class="signature-line">Training Director</div>
                </div>
                
                <div class="signature-box" style="float: right;">
                    <div class="date">{{ \Carbon\Carbon::now()->format('Y') }}</div>
                    <div class="signature-line">Official Registry</div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div class="seal">
                Official<br>Certified
            </div>
        </div>
    </div>
</body>
</html>
