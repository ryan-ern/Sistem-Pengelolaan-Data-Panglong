@extends('layouts.app')

@section('title', 'Data Kayu')

@section('content')

    <div class="card costume-card shadow-sm border-0 my-4 my-md-2" style="min-height: 90vh">
        <div class="card-body p-4">
            <div class="mb-4">
                <div class="row g-3 align-items-center mb-3">
                    {{-- Judul --}}
                    <div class="col-12 col-md-5">
                        <h5 class="fw-semibold mb-1">Manajemen Data Kayu</h5>
                        <small class="opacity-75">Kelola stok kayu
                            {{ Auth::user()->role != 'admin' ? 'per cabang' : '' }}</small>
                    </div>

                    {{-- Filter & Aksi --}}
                    <div class="col-12 col-md-7">
                        <form method="GET" id="filterForm">
                            <div class="row g-2">

                                @if (Auth::user()->role != 'admin')
                                    {{-- Filter Cabang --}}
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
                                <div class="col-12 col-sm-6 col-lg-6">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Cari jenis kayu..." value="{{ request('search') }}">
                                </div>

                                {{-- Print PDF --}}
                                <div class="col-12 col-sm-6 col-lg-6">
                                    <a href="{{ route('kayu.print', request()->query()) }}" target="_blank"
                                        class="btn btn-forest-light w-100">
                                        <i class="bi bi-printer"></i>
                                        <span class="d-none d-md-inline"> Print PDF</span>
                                    </a>
                                </div>

                                {{-- Button --}}
                                <div
                                    class="col-12 col-sm-{{ Auth::user()->role != 'admin' ? '6' : '12' }} col-lg-{{ Auth::user()->role != 'admin' ? '6' : '12' }}">
                                    <div class="btn btn-forest w-100" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                        <i class="bi bi-plus-circle"></i> <span> Data
                                            Kayu</span>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                <th>Tanggal Diperbarui</th>
                                <th>Cabang</th>
                                <th>Jenis Kayu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>#</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($kayu as $item)
                                <tr>
                                    <td> {{ $kayu->firstItem() + $loop->index }}</td>
                                    <td>{{ $item->updated_at->format('d F Y, H:i:s') }}</td>
                                    <td>{{ $item->cabang->nama_cabang ?? '-' }}</td>
                                    <td>{{ $item->jenis_kayu }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
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
                                                    <form action="{{ route('kayu.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus data {{ $item->jenis_kayu }} dari {{ $item->cabang->nama_cabang }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item bg-danger">
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
                                    <td colspan="7" class="text-center text-light">
                                        Data Kayu belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                {{-- CARD MOBILE --}}
                <div class="d-block d-md-none">
                    @forelse ($kayu as $item)
                        <div class="card mb-3 shadow-sm border-0  dark-bg text-light">
                            <div class="card-body rounded">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-semibold mb-0">
                                        {{ $kayu->firstItem() + $loop->index }}. {{ $item->jenis_kayu }}
                                    </h6>

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
                                                <form action="{{ route('kayu.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus data {{ $item->jenis_kayu }} dari {{ $item->cabang->nama_cabang }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item bg-danger">
                                                        <i class="bi bi-trash me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <small class="opacity-75 d-block mb-1">
                                    Cabang: {{ $item->cabang->nama_cabang ?? '-' }}
                                </small>

                                <div class="d-flex justify-content-between small">
                                    <span>Diperbarui</span>
                                    <strong>{{ $item->created_at->format('d M Y, H:i') }}</strong>
                                </div>

                                <div class="d-flex justify-content-between small">
                                    <span>Jumlah</span>
                                    <strong>{{ $item->jumlah }}</strong>
                                </div>

                                <div class="d-flex justify-content-between small">
                                    <span>Harga Satuan</span>
                                    <strong class="text-warning">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </strong>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center text-light">
                            Data kayu belum tersedia
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-end mt-3 costume-paginate">
                    {{ $kayu->links('pagination::bootstrap-5') }}
                </div>

            </div>

            @foreach ($kayu as $item)
                <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('kayu.update', $item->id) }}" method="POST"
                            class="modal-content dark-bg text-light border-0">
                            @csrf
                            @method('PUT')

                            <div class="modal-header border-0">
                                <h5 class="modal-title">Edit Data Kayu</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-2">
                                    @if (Auth::user()->role != 'admin')
                                        <label class="form-label">Cabang</label>
                                        <select name="cabang_id" class="form-control bg-secondary text-light" required>
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ $item->cabang_id == $c->id ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="cabang_id" value="{{ Auth::user()->cabang_id }}" />
                                    @endif
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Jenis Kayu</label>
                                    <input type="text" name="jenis_kayu" class="form-control bg-secondary text-light"
                                        value="{{ $item->jenis_kayu }}" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah" class="form-control bg-secondary text-light"
                                        value="{{ $item->jumlah }}" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Harga Satuan</label>
                                    <input type="number" name="harga_satuan"
                                        class="form-control bg-secondary text-light" value="{{ $item->harga_satuan }}"
                                        required>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button class="btn btn-secondary" type="reset" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-warning">Perbarui data</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach


            <div class="modal fade" id="modalTambah" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('kayu.store') }}" method="POST"
                        class="modal-content dark-bg text-light border-0">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Kayu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-2">
                                @if (Auth::user()->role != 'admin')
                                    <label class="form-label">Cabang</label>
                                    <select name="cabang_id" class="form-control bg-secondary text-light" required>
                                        <option value="" selected>Pilih Cabang</option>
                                        @if (Auth::user()->role != 'admin')
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ $item->cabang_id == $c->id ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                @else
                                    <input type="hidden" name="cabang_id" value="{{ Auth::user()->cabang_id }}" />
                                @endif
                            </div>

                            <div class="mb-2">
                                <label>Jenis Kayu</label>
                                <input type="text" name="jenis_kayu" class="form-control  bg-secondary text-light"
                                    required>
                            </div>

                            <div class="mb-2">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" class="form-control  bg-secondary text-light"
                                    required>
                            </div>

                            <div class="mb-2">
                                <label>Harga Satuan</label>
                                <input type="number" name="harga_satuan" class="form-control  bg-secondary text-light"
                                    required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="reset">Batal</button>
                            <button class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
