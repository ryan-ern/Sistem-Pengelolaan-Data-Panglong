<nav class="navbar navbar-expand-lg bg-timber px-3 shadow-sm">
    <a class="navbar-brand d-flex align-items-center gap-2 text-white fw-bold" href="{{ route('dashboard') }}">
        <img src="{{ asset('logo.png') }}" alt="Logo Panglong" width="40">
        PANG<strong>LONG</strong>
    </a>

    <ul class="navbar-nav ms-auto gap-3 align-items-center">

        <li class="nav-item">
            <a class="nav-link text-white opacity-75
                {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white opacity-75
                {{ request()->routeIs('transaksi*') ? 'active' : '' }}"
                href="{{ route('transaksi') }}">
                <i class="bi bi-receipt me-1"></i> Transaksi
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white opacity-75
                {{ request()->routeIs('kayu*') ? 'active' : '' }}"
                href="{{ route('kayu') }}">
                <i class="bi bi-boxes me-1"></i> Kayu
            </a>
        </li>

        @if (Auth::user()->role != 'admin')
            <li class="nav-item">
                <a class="nav-link text-white opacity-75
                {{ request()->routeIs('cabang*') ? 'active' : '' }}"
                    href="{{ route('cabang') }}">
                    <i class="bi bi-diagram-3 me-1"></i> Cabang
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white opacity-75
                {{ request()->routeIs('pengguna*') ? 'active' : '' }}"
                    href="{{ route('pengguna') }}">
                    <i class="bi bi-people me-1"></i> Pengguna
                </a>
            </li>
        @endif

    </ul>

    {{-- USER DROPDOWN --}}
    <div class="dropdown ms-4">
        <a class="text-white text-decoration-none dropdown-toggle fw-semibold d-flex align-items-center gap-2 text-capitalize"
            href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i>
            {{ auth()->user()->nama }}
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item btn btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
