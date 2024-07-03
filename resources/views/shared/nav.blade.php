<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#menu">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bookings">Bookings</a>
                </li>

                {{-- If user is a guest, show login/registration options --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Registration</a>
                    </li>
                @endguest

                {{-- Checks if user is logged in or not --}}
                @auth()
                    <li class="nav-item me-2">
                        <a class="nav-link" href="/dashboard">{{ Auth::user()->name }}'s Dashboard</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                        </form>
                    </li>
                @endauth

                {{-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> --}}

            </ul>
        </div>
    </div>
</nav>
