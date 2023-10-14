<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-lg-between">

        <h1 class="logo me-auto me-lg-0"><a href="/">Phopixel</a></h1>
        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li><a class="nav-link scrollto" href="/#about">About</a></li>
                <li><a class="nav-link scrollto" href="/#prizes">Prizes</a></li>
                <li><a class="nav-link" href="{{ url('/faq') }}">FAQ</a></li>
                <li><a class="nav-link" href="{{ url('/contact-us') }}">Contact Us</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>

        @if (Route::has('login'))
            <a href="{{ url('/home') }}" class="get-started-btn">Get Started</a>
        @endif

    </div>
</header>
