<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .card {
            width: 75%
        }

        @media screen and (width <=600px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mx-auto mt-5">
        <div class="card mx-auto mx-100 p-3 shadow-lg mb-5 bg-white rounded">
            <div class="card-body text-left">
                <p class="heading">hello {{ $name }}!, {{ $token }}</p>
                <p class="mb-4 mt-3">Someone requested a link to change your password. Click the button below to proceed</p>
                <a href="{{ route('resetPasswordForm', ['token' => $token]) }}" class="btn btn-primary text-center">Change my password</a>
                <p class="mb-4 mt-3">If you didn't request this, please ignore the email. Your password will stay safe and won't be changed.</p>
                <p class="border-top pt-2">Sincerely,</p>
            </div>
        </div>
    </div>
</body>

</html>
