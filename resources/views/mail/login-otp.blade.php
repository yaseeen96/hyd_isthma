<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> JIH IJTEMA </title>
    <link rel="shortcut icon" type="image/png" href="/favicon.png" />
    <meta name="description" content="">
    <meta name="keywords" content=" ">
    <meta name="author" content="JIH IJTEMA">
    <meta property="og:image" content="">
    <meta property="og:site_name" content="">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
</head>
</head>

<body style="background: #f0f0f1; margin: 0px;">
    <!--special-offer-section-->
    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
        <div style="margin:50px auto;width:70%;padding:20px 0">
            <div style="border-bottom:1px solid #eee">
                <a href="" style="font-size:1.4em;color: purple;text-decoration:none;font-weight:600">
                    JIH IJTEMA</a>
            </div>
            <p style="font-size:1.1em">Hi,{{ $member->name }}</p>
            <p>Thank you for choosing JIH App. Use the following OTP to complete your login procedures. OTP is
                valid for 30 minutes</p>
            <h2
                style="background: purple;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
                {{ $otp }}</h2>
            <p style="font-size:0.9em;">Regards,<br /> JIH IJTEMA</p>
            <hr style="border:none;border-top:1px solid #eee" />
            <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                <p>office@jih.org.in</p>
                <p>011-26951409</p>
                <p>D-321, Abul Fazal Enclave,<br /> Jamia Nagar,<br /> Okhla, New Delhi, <br />India. PIN:110025</p>
            </div>
        </div>
    </div>
</body>

</html>
