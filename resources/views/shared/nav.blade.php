<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Services</a>
                    </li>

                    {{-- If user is a guest, show login/registration options --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/bookings">Bookings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Registration</a>
                        </li>
                    @endguest

                    {{-- Checks if user is logged in or not, as well as the user type (admin, employee, customer) --}}
                    @auth()
                        <li class="nav-item">
                            @if (Auth::user()->role === 'customer')
                                <a class="nav-link" href="/bookings">Bookings</a>
                            @endif
                        </li>
                        <li class="nav-item">
                            @if (Auth::user()->role === 'admin')
                                <a class="nav-link" href="/admin">{{ Auth::user()->name }}'s Dashboard</a>
                            @elseif(Auth::user()->role === 'employee')
                                <a class="nav-link" href="/employee">{{ Auth::user()->name }}'s Dashboard</a>
                            @else
                                <a class="nav-link" href="/customer">{{ Auth::user()->name }}'s Dashboard</a>
                            @endif
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>
