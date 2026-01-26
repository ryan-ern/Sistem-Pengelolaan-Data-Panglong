@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
    <div class="card costume-card shadow-sm border-0 my-4 my-md-2" style="min-height: 90vh">
        <div class="card-body p-4">

            <div class="mb-4">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-lg-8 col-md-12">
                        <h5 class="fw-semibold mb-1">Manajemen Data Pengguna</h5>
                        <small class="opacity-75">Kelola data pengurus cabang yang menggunakan sistem</small>
                    </div>

                    <div class="col-lg-4 col-md-12 text-md-end">
                        <div class="row g-2">
                            {{-- SEARCH --}}
                            <div class="col-lg-6">
                                <form method="GET" id="filterForm">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Cari Pengguna..." value="{{ request('search') }}">
                                </form>
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-forest w-100" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    <i class="bi bi-plus-circle"></i> Pengguna
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr class="border border-2 opacity-20">
                </div>



                <div class="dashboard-inner">

                    {{-- DESKTOP --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-dark table-hover align-middle text-center">
                            <thead class="table-light text-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Cabang</th>
                                    <th>Role</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengguna as $item)
                                    <tr>
                                        <td>{{ $pengguna->firstItem() + $loop->index }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->cabang->nama_cabang ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $item->role === 'admin' ? 'info' : 'success' }} text-dark">
                                                {{ strtoupper($item->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-light dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#modalEdit{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('pengguna.destroy', $item->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Hapus pengguna {{ $item->nama }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item bg-danger">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Data pengguna belum tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE --}}
                    <div class="d-block d-md-none">
                        @foreach ($pengguna as $item)
                            <div class="card mb-3 dark-bg text-light border-0">
                                <div class="card-body rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="fw-semibold mb-0">
                                            {{ $pengguna->firstItem() + $loop->index }}. {{ $item->nama }}
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
                                                    <form action="{{ route('pengguna.destroy', $item->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus data {{ $item->nama }} dari {{ $item->cabang->nama_cabang }}?')">
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

                                    <small class="opacity-75">Role: {{ strtoupper($item->role) }}</small>
                                    <div class="small mt-2">
                                        <div>Cabang: {{ $item->cabang->nama_cabang ?? '-' }}</div>
                                        <div>Username: {{ $item->username }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end mt-3 costume-paginate">
                        {{ $pengguna->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                @foreach ($pengguna as $item)
                    <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('pengguna.update', $item->id) }}" method="POST"
                                class="modal-content dark-bg text-light border-0">
                                @csrf
                                @method('PUT')

                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Edit Data Pengguna</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    {{-- Cabang --}}
                                    <div class="mb-2">
                                        <label class="form-label">Cabang</label>
                                        <select name="cabang_id" class="form-control bg-secondary text-light" required>
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ $item->cabang_id == $c->id ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Nama --}}
                                    <div class="mb-2">
                                        <label class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control bg-secondary text-light"
                                            value="{{ $item->nama }}" required>
                                    </div>

                                    {{-- Username --}}
                                    <div class="mb-2">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control bg-secondary text-light"
                                            value="{{ $item->username }}" required>
                                    </div>

                                    {{-- Password (opsional) --}}
                                    <div class="mb-2">
                                        <label class="form-label">Password (Opsional)</label>
                                        <input type="password" name="password" class="form-control bg-secondary text-light"
                                            placeholder="Kosongkan jika tidak diubah">
                                    </div>

                                    {{-- Role --}}
                                    <div class="mb-2">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-control bg-secondary text-light" required>
                                            <option value="superadmin"
                                                {{ $item->role == 'superadmin' ? 'selected' : '' }}>
                                                Superadmin
                                            </option>
                                            <option value="admin" {{ $item->role == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer border-0">
                                    <button class="btn btn-secondary" type="reset"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-warning">Perbarui Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach



                <div class="modal fade" id="modalTambah" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('pengguna.store') }}" method="POST"
                            class="modal-content dark-bg text-light border-0">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Data Pengguna</h5>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                {{-- Cabang --}}
                                <div class="mb-2">
                                    <label class="form-label">Cabang</label>
                                    <select name="cabang_id" class="form-control bg-secondary text-light" required>
                                        <option value="">Pilih Cabang</option>
                                        @foreach ($cabang as $c)
                                            <option value="{{ $c->id }}">{{ $c->nama_cabang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Nama --}}
                                <div class="mb-2">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="nama" class="form-control bg-secondary text-light"
                                        required>
                                </div>

                                {{-- Username --}}
                                <div class="mb-2">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control bg-secondary text-light"
                                        required>
                                </div>

                                {{-- Password --}}
                                <div class="mb-2">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control bg-secondary text-light"
                                        required>
                                </div>

                                {{-- Role --}}
                                <div class="mb-2">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control bg-secondary text-light" required>
                                        <option value="">Pilih Role</option>
                                        <option value="superadmin">Superadmin</option>
                                        <option value="admin">Admin</option>
                                    </select>
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
