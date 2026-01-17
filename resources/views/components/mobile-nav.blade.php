<nav class="navbar fixed-bottom bg-forest border-top shadow-sm mobile-nav d-md-none">
    <div class="d-flex justify-content-center w-100 position-relative">

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-inner">
                <i class="bi bi-house-door"></i>
                <small>Dashboard</small>
            </span>
        </a>

        <a href="{{ route('transaksi') }}" class="nav-item {{ request()->routeIs('transaksi*') ? 'active' : '' }}">
            <span class="nav-inner">
                <i class="bi bi-receipt"></i>
                <small>Transaksi</small>
            </span>
        </a>

        <a href="{{ route('kayu') }}" class="nav-item {{ request()->routeIs('kayu*') ? 'active' : '' }}">
            <span class="nav-inner">
                <i class="bi bi-boxes"></i>
                <small>Kayu</small>
            </span>
        </a>

        @if (Auth::user()->role != 'admin')
            <a href="{{ route('cabang') }}" class="nav-item {{ request()->routeIs('cabang*') ? 'active' : '' }}">
                <span class="nav-inner">
                    <i class="bi bi-diagram-3"></i>
                    <small>Cabang</small>
                </span>
            </a>

            <a href="{{ route('pengguna') }}" class="nav-item {{ request()->routeIs('pengguna*') ? 'active' : '' }}">
                <span class="nav-inner">
                    <i class="bi bi-people"></i>
                    <small>Pengguna</small>
                </span>
            </a>
        @endif
    </div>
</nav>
