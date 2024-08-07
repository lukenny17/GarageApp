<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
</head>

<body>
    {{-- Navbar contents included here --}}
    @include('shared.nav')

    {{-- Page content goes here --}}
    <div>
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="bg-dark text-center text-light mt-auto py-3">
        <div class="container">
            <p>Follow us on
                <a href="https://www.facebook.com" target="_blank">Facebook</a>,
                <a href="https://www.twitter.com" target="_blank">X</a>,
                <a href="https://www.instagram.com" target="_blank">Instagram</a>
            </p>
            <p class="no-padding">&copy; 2024 {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
