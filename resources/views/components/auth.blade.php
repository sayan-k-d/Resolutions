<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('public/assets/img/favicon.png') }}" />
    <style>
        body {
            font-family: "Arial", sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4a4e69, #9a8c98);
            overflow: hidden;
        }

        .image-container {
            flex: 1;
            background: url("public/assets/img/login.svg") no-repeat center;
            background-size: contain;
        }
    </style>
</head>

<body>
    {{ $content }}
</body>

</html>
