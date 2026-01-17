<!-- HEADER MOBILE -->
<header class="header-mobile fixed-top  d-lg-none">
    <div class="container-fluid d-flex align-items-center justify-content-between py-2">

        <!-- LOGO -->
        <div>
            <img src="{{ asset('logo.png') }}" alt="Logo Panglong" class="header-logo">

        </div>
        <div class="logo-text">
            <span class="wood-text">PANG</span><span class="dark-text">LONG</span>
        </div>

        <div class="gap-3 ">
            <a href="{{ route('logout') }}" class="nav-item nav-logout p-2"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</header>
