@extends('layouts.app')

@section('title', 'Data Cabang')

@section('content')
    <div class="card costume-card shadow-sm border-0 my-4 my-md-2" style="min-height:90vh">
        <div class="card-body p-4">

            <div class="row g-3 align-items-center mb-3">
                <div class="col-lg-9 col-md-6">
                    <h5 class="fw-semibold mb-1">Manajemen Data Cabang</h5>
                    <small class="opacity-75">Kelola data cabang panglong</small>
                </div>

                <div class="col-lg-3 col-md-6 text-md-end">
                    <button class="btn btn-forest w-100 " data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-circle"></i> Tambah Cabang
                    </button>
                </div>
            </div>

            <hr class="border border-2 opacity-20 mb-3">
            <div class="table-responsive d-none d-md-block">
                <table class="table table-dark table-hover align-middle text-center">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Diperbarui</th>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cabang as $item)
                            <tr>
                                <td>{{ $cabang->firstItem() + $loop->index }}</td>
                                <td>{{ $item->updated_at->format('d F Y, H:i') }}</td>
                                <td>{{ $item->nama_cabang }}</td>
                                <td style="max-width: 150px">{{ $item->alamat }}</td>
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
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('cabang.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus cabang ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-item bg-danger">
                                                        <i class="bi bi-trash me-2"></i>Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Data cabang belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-block d-md-none">
                @foreach ($cabang as $item)
                    <div class="card mb-3 shadow-sm border-0  dark-bg text-light">
                        <div class="card-body rounded">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-semibold mb-1">
                                    {{ $cabang->firstItem() + $loop->index }}. {{ $item->nama_cabang }}
                                </h6>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $item->id }}">
                                                Edit
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item bg-danger">Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <small class="opacity-75">
                                Diperbarui: {{ $item->updated_at->format('d M Y, H:i') }}
                            </small>
                            <div class="border border-1 rounded-1 p-2">
                                <small>
                                    Alamat
                                </small>
                                <small class="opacity-75 d-block">{{ $item->alamat }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-end mt-3 costume-paginate">
                {{ $cabang->links('pagination::bootstrap-5') }}
            </div>
        </div>
        <div class="modal fade" id="modalTambah">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('cabang.store') }}" method="POST"
                    class="modal-content dark-bg text-light border-0">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Cabang</h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Nama Cabang</label>
                        <input type="text" name="nama_cabang" class="form-control mb-2 bg-secondary text-light" required>

                        <label>Alamat Cabang</label>
                        <textarea name="alamat" class="form-control bg-secondary text-light" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="reset">Batal</button>
                        <button class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        @foreach ($cabang as $item)
            <div class="modal fade" id="modalEdit{{ $item->id }}">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('cabang.update', $item->id) }}" method="POST"
                        class="modal-content dark-bg text-light border-0">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Cabang</h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label>Nama Cabang</label>
                            <input type="text" name="nama_cabang" value="{{ $item->nama_cabang }}"
                                class="form-control mb-2 bg-secondary text-light" required>

                            <label>Alamat Cabang</label>
                            <textarea name="alamat" class="form-control bg-secondary text-light" required>{{ $item->alamat }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="reset">Batal</button>
                            <button class="btn btn-warning">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
