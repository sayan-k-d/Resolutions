<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ @$title ?? 'Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.2/dist/sweetalert2.min.css"
        integrity="sha256-PFxP4VzO//YWpakxkrmQGdaTZS+qrhXUmXQ3DZveRcc=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fireworks-js/dist/fireworks.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" />
</head>

<body>
    {{-- @dd($auth) --}}
    <nav class="navbar bg-dark navbar-expand-lg sticky-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand fs-3 text-uppercase fw-bold" href="{{ route('dashboard') }}">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex flex-grow-1 flex-to-right"></div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    @if (Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('profile') }}">Profile</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ Auth::check() ? route('logout') : route('signup') }}">{{ Auth::check() ? 'Logout' : 'Sign Up' }}</a>
                    </li>
                </ul>
                <form action="{{ route('search') }}" role="search" class="d-flex hide-search" method="GET">
                    <input class="form-control me-2 search-area" name="search" type="search" placeholder="Search"
                        value="{{ $search }}" aria-label="Search" id="search" oninput="handleRedirect(event)"
                        required />
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    {{ @$content }}
    <footer class="footer bg-dark text-gray">
        <div class="container">
            <p class="m-0">&copy; 2024 Sayan Kumar Das. All Rights Reserved.</p>
        </div>
    </footer>
</body>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

</html>
