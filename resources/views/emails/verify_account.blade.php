<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Account - MedVault</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 20px; background-color: #f3f4f6; margin: 0;">
    
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">

        <div style="margin-bottom: 25px;">
            <img src="{{ $message->embed(public_path('Image/logo.png')) }}" alt="MedVault Logo" style="height: 60px; width: auto; margin-bottom: 10px;">
            <h2 style="color: #065f46; margin: 0; font-size: 24px; letter-spacing: 1px;">MedVault</h2>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin-bottom: 25px;">

        <h3 style="color: #111827; font-size: 22px; margin-top: 0;">Welcome to MedVault!</h3>
        
        <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
            Thank you for registering. To secure your account and complete your setup, please verify your email address by clicking the button below:
        </p>

        <a href="{{ $url }}" style="display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 16px; margin-bottom: 35px; box-shadow: 0 2px 4px rgba(5,150,105,0.2);">
            Verify Email Address
        </a>

        <div style="background-color: #ecfdf5; border: 1px solid #34d399; border-radius: 10px; padding: 20px; text-align: left; margin-bottom: 20px;">
            <h4 style="color: #065f46; margin-top: 0; margin-bottom: 15px; font-size: 16px; text-align: center;">Your Login Credentials</h4>
            
            <p style="color: #4b5563; font-size: 15px; margin: 0 0 10px 0;">
                <strong>Generated User ID:</strong> <span style="font-size: 20px; color: #059669; font-weight: bold; letter-spacing: 2px;">{{ $usernumber }}</span>
            </p>
            <p style="color: #4b5563; font-size: 15px; margin: 0 0 15px 0;">
                <strong>Email Address:</strong> {{ $email }}
            </p>
            
            <div style="background-color: #ffffff; padding: 10px; border-left: 4px solid #059669; border-radius: 4px;">
                <p style="color: #4b5563; font-size: 13px; line-height: 1.5; margin: 0;">
                    <strong style="color: #059669;">Important:</strong> You can log in to your MedVault dashboard using <strong>EITHER</strong> your Email Address <strong>OR</strong> your Generated User ID shown above.
                </p>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin-top: 30px; margin-bottom: 20px;">

        <p style="color: #9ca3af; font-size: 12px;">
            &copy; {{ date('Y') }} Barangay Looc Clinic - MedVault. All rights reserved.
        </p>
    </div>

</body>
</html>