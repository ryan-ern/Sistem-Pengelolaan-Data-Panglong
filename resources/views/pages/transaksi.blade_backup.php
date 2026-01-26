@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
    <div class="card costume-card shadow-sm border-0 my-4 my-md-2" style="min-height: 90vh">
        <div class="card-body p-4">
            <div class="mb-4">
                <div class="row g-3 align-items-center mb-3">
                    {{-- Judul --}}
                    <div class="col-12 col-md-5">
                        <h5 class="fw-semibold mb-1">Manajemen Data Transaksi</h5>
                        <small class="opacity-75">Kelola data transaksi
                            {{ Auth::user()->role != 'admin' ? 'per cabang' : '' }}</small>
                    </div>

                    {{-- Filter & Aksi --}}
                    <div class="col-12 col-md-7">
                        <form method="GET" id="filterForm">
                            <div class="row g-2">

                                {{-- Filter Cabang --}}
                                @if (Auth::user()->role != 'admin')
                                    <div class="col-12 col-sm-6 col-lg-6">
                                        <select name="cabang_id" id="cabangFilter" class="form-select">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ request('cabang_id') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                {{-- Search --}}
                                <div class="col-12 col-sm-6 col-lg-6 ">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Cari Transaksi..." value="{{ request('search') }}">
                                </div>

                                {{-- Print PDF --}}
                                <div class="col-12 col-sm-6 col-lg-6">
                                    <div class="dropdown w-100">
                                        <button class="btn btn-forest-light w-100 dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-printer"></i>
                                            <span class="d-none d-md-inline"> Download PDF</span>
                                        </button>

                                        <ul class="dropdown-menu w-100">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('transaksi.print.filter', array_merge(request()->query(), ['periode' => 'hari'])) }}"
                                                    target="_blank">
                                                    Harian
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('transaksi.print.filter', array_merge(request()->query(), ['periode' => 'minggu'])) }}"
                                                    target="_blank">
                                                    Mingguan
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('transaksi.print.filter', array_merge(request()->query(), ['periode' => 'bulan'])) }}"
                                                    target="_blank">
                                                    Bulanan
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('transaksi.print.filter', array_merge(request()->query(), ['periode' => 'tahun'])) }}"
                                                    target="_blank">
                                                    Tahunan
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item fw-semibold"
                                                    href="{{ route('transaksi.print.filter', array_merge(request()->query(), ['periode' => 'all'])) }}"
                                                    target="_blank">
                                                    Semua Data
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div
                                    class="col-12 col-sm-{{ Auth::user()->role != 'admin' ? '6' : '12' }} col-lg-{{ Auth::user()->role != 'admin' ? '6' : '12' }}">
                                    <div class="btn btn-forest w-100" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                        <i class="bi bi-plus-circle"></i> <span>
                                            Transaksi</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-12 col-sm-12 col-lg-12 mt-2">
                            <div class="btn-group w-100">
                                <a href="?jenis_transaksi=masuk"
                                    class="btn btn-primary {{ request('jenis_transaksi') == 'masuk' ? 'active' : '' }}">
                                    Masuk
                                </a>
                                <a href="?jenis_transaksi=keluar"
                                    class="btn btn-primary {{ request('jenis_transaksi') == 'keluar' ? 'active' : '' }}">
                                    Keluar
                                </a>
                                @if (request('jenis_transaksi'))
                                    <a href="?" class="btn btn-info">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="border border-2 opacity-20">

            </div>


            <div class="dashboard-inner">
                {{-- DESKTOP MODE --}}
                <div class="table-responsive d-none d-md-block rounded">
                    <table class="table table-dark table-hover align-middle mb-0 text-center">
                        <thead class="table-light text-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Cabang</th>
                                <th>Pengguna</th>
                                <th>Transaksi</th>
                                <th>Informasi</th>
                                <th>Total</th>
                                <th>#</th>
                            </tr>
                        </thead>

                        <tbody class="text-capitalize">
                            @forelse ($transaksi as $item)
                                <tr>
                                    <td> {{ $transaksi->firstItem() + $loop->index }}</td>
                                    <td>{{ $item->tanggal->format('d F Y, H:i:s') }}</td>
                                    <td>{{ $item->cabang->nama_cabang ?? '-' }}</td>
                                    <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $item->jenis_transaksi === 'masuk' ? 'success' : 'primary' }}">
                                            {{ $item->jenis_transaksi }}
                                        </span>
                                    </td>
                                    <td style="white-space: pre-line;">
                                        {{ $item->informasi }}
                                    </td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i> Aksi
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $item->id }}">
                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item bg-primary"
                                                        href="{{ route('transaksi.print.id', $item->id) }}"
                                                        target="_blank">
                                                        <i class="bi bi-printer me-2"></i> Print
                                                    </a>
                                                </li>

                                                <li>
                                                    <form action="{{ route('transaksi.destroy', $item->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus data {{ $item->informasi }} dari {{ $item->cabang->nama_cabang }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item bg-danger">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-light">
                                        Data transaksi belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                {{-- CARD MOBILE --}}
                <div class="d-block d-md-none">
                    @forelse ($transaksi as $item)
                        <div class="card mb-3 shadow-sm border-0  dark-bg text-light">
                            <div class="card-body rounded">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h1>
                                        <span
                                            class="p-3 rounded bg-{{ $item->jenis_transaksi === 'masuk' ? 'success' : 'forest' }} mb-0 text-capitalize">
                                            {{ $transaksi->firstItem() + $loop->index }}.
                                            Transaksi
                                            {{ $item->jenis_transaksi }}
                                        </span>
                                    </h1>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalEdit{{ $item->id }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item bg-primary"
                                                    href="{{ route('transaksi.print.id', $item->id) }}" target="_blank">
                                                    <i class="bi bi-printer me-2"></i> Print
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus data {{ $item->informasi }} dari {{ $item->cabang->nama_cabang }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item bg-danger">
                                                        <i class="bi bi-trash me-2"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between small">
                                    <span>Cabang</span>
                                    <strong>{{ $item->cabang->nama_cabang }}</strong>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Pengguna</span>
                                    <strong>{{ $item->pengguna->nama }}</strong>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Diperbarui</span>
                                    <strong>{{ $item->created_at->format('d M Y, H:i') }}</strong>
                                </div>

                                <div class="small border border-1 rounded-2 p-2 m-1">
                                    <div>Informasi :</div>
                                    <div>
                                        <strong style="white-space: pre-line;">{{ $item->informasi }}</strong>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between small">
                                    <span>Total</span>
                                    <strong class="text-warning">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </strong>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center text-light">
                            Data transaksi belum tersedia
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-end mt-3 costume-paginate">
                    {{ $transaksi->links('pagination::bootstrap-5') }}
                </div>

            </div>

            @foreach ($transaksi as $item)
                <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content dark-bg text-light">

                            <form action="{{ route('transaksi.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- HEADER --}}
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Transaksi
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                {{-- BODY --}}
                                <div class="modal-body">
                                    <div class="row g-4">

                                        {{-- FORM UTAMA --}}
                                        <div class="col-md-4">
                                            @if (Auth::user()->role != 'admin')
                                                <label class="form-label">Cabang</label>
                                                <select name="cabang_id" class="form-select mb-3" required>
                                                    @foreach ($cabang as $c)
                                                        <option value="{{ $c->id }}"
                                                            {{ $item->cabang_id == $c->id ? 'selected' : '' }}>
                                                            {{ $c->nama_cabang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif

                                            <label class="form-label">Jenis Transaksi</label>
                                            <select name="jenis_transaksi" class="form-select mb-3" required>
                                                <option value="masuk"
                                                    {{ $item->jenis_transaksi == 'masuk' ? 'selected' : '' }}>
                                                    Masuk
                                                </option>
                                                <option value="keluar"
                                                    {{ $item->jenis_transaksi == 'keluar' ? 'selected' : '' }}>
                                                    Keluar
                                                </option>
                                            </select>

                                            <label class="form-label">Tanggal</label>
                                            <input type="datetime-local" name="tanggal" class="form-control"
                                                value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d\TH:i:s') }}"
                                                required>
                                        </div>

                                        {{-- INPUT KAYU --}}
                                        <div class="col-md-4">
                                            <h6 class="fw-semibold mb-2">Tambah Kayu</h6>

                                            <label class="form-label">Jenis Kayu</label>
                                            <select class="form-select mb-2 kayuSelect" data-id="{{ $item->id }}">
                                                <option value="">Pilih Kayu</option>
                                                @foreach ($kayu as $k)
                                                    <option value="{{ $k->id }}" data-nama="{{ $k->jenis_kayu }}"
                                                        data-harga="{{ $k->harga_satuan }}">
                                                        {{ $k->jenis_kayu }} -
                                                        Rp {{ number_format($k->harga_satuan, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label class="form-label">Jumlah</label>
                                            <input type="number" class="form-control mb-3 qtyInput"
                                                data-id="{{ $item->id }}" min="1">

                                            <button type="button" class="btn btn-success w-100 addToCart"
                                                data-id="{{ $item->id }}">
                                                <i class="bi bi-plus-circle me-1"></i> Tambah ke Keranjang
                                            </button>
                                        </div>

                                        {{-- KERANJANG --}}
                                        <div class="col-md-4">
                                            <h6 class="fw-semibold mb-2">Keranjang</h6>

                                            <ul class="list-group small mb-3 cartList" data-id="{{ $item->id }}">
                                                @foreach (explode("\n", $item->informasi) as $row)
                                                    @if ($row)
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            {{ $row }}
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger removeItem">×</button>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>

                                            <input type="hidden" name="informasi" class="informasi"
                                                value="{{ $item->informasi }}">

                                            <input type="hidden" name="total" class="total"
                                                value="{{ $item->total }}">

                                            <div class="border-top pt-2">
                                                <strong>Total :</strong>
                                                <span class="float-end text-success fw-bold totalText">
                                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- FOOTER --}}
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-warning">
                                        <i class="bi bi-save me-1"></i> Perbarui Data
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="modal fade" id="modalTambah" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content dark-bg text-light ">

                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf

                            {{-- HEADER --}}
                            <div class="modal-header bg-forest text-white">
                                <h5 class="modal-title">
                                    <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
                                </h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            {{-- BODY --}}
                            <div class="modal-body">
                                <div class="row g-4">

                                    {{-- FORM UTAMA --}}
                                    <div class="col-md-4">
                                        @if (Auth::user()->role != 'admin')
                                            <label class="form-label">Cabang</label>
                                            <select name="cabang_id" class="form-select mb-3" required>
                                                <option value="">Pilih Cabang</option>
                                                @foreach ($cabang as $c)
                                                    <option value="{{ $c->id }}">{{ $c->nama_cabang }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                        <label class="form-label">Jenis Transaksi</label>
                                        <select name="jenis_transaksi" class="form-select mb-3" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="masuk">Masuk</option>
                                            <option value="keluar">Keluar</option>
                                        </select>

                                        <label class="form-label">Tanggal</label>
                                        <input type="datetime-local" name="tanggal" class="form-control"
                                            value="{{ now()->format('Y-m-d\TH:i:s') }}" required>

                                    </div>

                                    {{-- INPUT KAYU --}}
                                    <div class="col-md-4">
                                        <h6 class="fw-semibold mb-2">Tambah Kayu</h6>

                                        <label class="form-label">Jenis Kayu</label>
                                        <select id="kayuSelect" class="form-select mb-2">
                                            <option value="">Pilih Kayu</option>
                                            @foreach ($kayu as $item)
                                                <option value="{{ $item->id }}" data-nama="{{ $item->jenis_kayu }}"
                                                    data-harga="{{ $item->harga_satuan }}">
                                                    {{ $item->jenis_kayu }} -
                                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <label class="form-label">Jumlah</label>
                                        <input type="number" id="qtyInput" class="form-control mb-3" min="1">

                                        <div class="btn btn-success w-100" id="addToCart">
                                            <i class="bi bi-plus-circle me-1"></i> Tambah ke Keranjang
                                        </div>
                                    </div>

                                    {{-- KERANJANG --}}
                                    <div class="col-md-4">
                                        <h6 class="fw-semibold mb-2">Keranjang</h6>

                                        <ul class="list-group small mb-3" id="cartList">
                                            <li class="list-group-item text-muted text-center">
                                                Belum ada item
                                            </li>
                                        </ul>

                                        <input type="hidden" name="informasi" id="informasi">
                                        <input type="hidden" name="total" id="total">

                                        <div class="border-top pt-2">
                                            <strong>Total :</strong>
                                            <span class="float-end text-success fw-bold" id="totalText">
                                                Rp 0
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- FOOTER --}}
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-forest">
                                    <i class="bi bi-save me-1"></i> Simpan Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
