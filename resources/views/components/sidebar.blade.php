<div class="bg-timber p-3 border-end shadow-sm sidebar" style="min-height: 100%">

    <a class="navbar-brand d-flex align-items-center gap-2 text-white fw-bold mb-3" href="{{ route('dashboard') }}">
        <img src="{{ asset('logo.png') }}" alt="Logo Panglong" width="40">
        PANG<strong>LONG</strong>
    </a>

    <ul class="nav flex-column gap-1 mt-4 ">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link d-flex align-items-center gap-2
                {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('transaksi') }}"
                class="nav-link d-flex align-items-center gap-2
                {{ request()->routeIs('transaksi*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transaksi
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('kayu') }}"
                class="nav-link d-flex align-items-center gap-2
                {{ request()->routeIs('kayu*') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i> Kayu
            </a>
        </li>
        @if (Auth::user()->role != 'admin')
            <li class="nav-item">
                <a href="{{ route('cabang') }}"
                    class="nav-link d-flex align-items-center gap-2
                {{ request()->routeIs('cabang*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i> Cabang
                </a>
            </li>

            <li class="nav-item mb-5">
                <a href="{{ route('pengguna') }}"
                    class="nav-link d-flex align-items-center gap-2
                {{ request()->routeIs('pengguna*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Pengguna
                </a>
            </li>
        @endif
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-100 d-flex align-items-center justify-content-center gap-2 btn btn-danger mt-5">
                    Keluar <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </li>
    </ul>
</div>
