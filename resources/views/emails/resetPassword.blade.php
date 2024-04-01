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
                <p class="heading">Dear {{ $name }},</p>
                <p class="mb-4 mt-3">Your password has been successfully reset.</p>
                <p>Best Regards,</p>
            </div>
        </div>
    </div>
</body>

</html>
