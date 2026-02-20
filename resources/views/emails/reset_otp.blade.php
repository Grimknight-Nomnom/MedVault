<!DOCTYPE html>
<html>
<head>
    <title>Password Reset - MedVault</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 20px; background-color: #f3f4f6; margin: 0;">
    
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">

        <div style="margin-bottom: 25px;">
            {{-- Make sure APP_URL is correctly set in your .env so asset() generates a valid link --}}
            <img src="{{ asset('Image/logo.png') }}" alt="MedVault Logo" style="height: 60px; width: auto; margin-bottom: 10px;">
            <h2 style="color: #065f46; margin: 0; font-size: 24px; letter-spacing: 1px;">MedVault</h2>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin-bottom: 25px;">

        <h3 style="color: #111827; font-size: 22px; margin-top: 0;">Password Reset Request</h3>
        
        <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
            We received a request to reset your MedVault account password. Please use the verification code below to proceed:
        </p>

        <div style="margin: 30px 0; padding: 20px; background-color: #ecfdf5; border: 2px dashed #34d399; border-radius: 10px; display: inline-block;">
            <h1 style="color: #059669; font-size: 40px; letter-spacing: 10px; margin: 0;">{{ $code }}</h1>
        </div>

        <p style="color: #6b7280; font-size: 14px; line-height: 1.5;">
            This code expires in <strong>10 minutes</strong>. <br>
            If you did not request a password reset, you can safely ignore this email.
        </p>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin-top: 30px; margin-bottom: 20px;">

        <p style="color: #9ca3af; font-size: 12px;">
            &copy; {{ date('Y') }} Barangay Looc Clinic - MedVault. All rights reserved.
        </p>
    </div>

</body>
</html>