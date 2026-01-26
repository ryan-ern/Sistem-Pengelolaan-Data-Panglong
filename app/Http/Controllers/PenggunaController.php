<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Cabang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::with('cabang')->orderBy('updated_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $pengguna = $query->paginate(10)->withQueryString();
        $cabang   = Cabang::orderBy('nama_cabang')->get();

        return view('pages.pengguna', compact('pengguna', 'cabang'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'cabang_id' => 'required|exists:cabang,id',
                'nama'      => 'required|string|max:150',
                'username'  => 'required|string|max:100|unique:pengguna,username',
                'password'  => 'required|min:6',
                'role'      => 'required|in:superadmin,admin',
            ], [
                'cabang.required' => 'cabang harus diisi',
                'nama.required' => 'Nama Harus Diisi',
                'role.required' => 'role harus diisi',
                'username.required' => 'username harus diisi',
                'password.required' => 'password harus diisi',
                'username.unique' => 'Username Sudah Digunakan',
                'password.min' => 'Password Minimal 6 Karakter',
                'role.in' => 'role yang anda pilih tidak tersedia',
            ]);

            Pengguna::create([
                'cabang_id' => $request->cabang_id,
                'nama'      => $request->nama,
                'username'  => $request->username,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
            ]);

            notify()->success(
                'Berhasil menambahkan pengguna ' . $request->nama,
                'Tambah Data Pengguna Berhasil'
            );
        } catch (Exception $e) {
            Log::error('Gagal tambah Pengguna', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal menambahkan data pengguna ' . $request->nama,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }

        return redirect()->route('pengguna');
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'cabang_id' => 'required|exists:cabang,id',
                'nama'      => 'required|string|max:150',
                'username'  => 'required|string|max:100|unique:pengguna,username,' . $id,
                'role'      => 'required|in:superadmin,admin',
                'password'  => 'nullable|min:6',
            ], [
                'cabang.required' => 'cabang harus diisi',
                'nama.required' => 'Nama Harus Diisi',
                'role.required' => 'role harus diisi',
                'username.required' => 'username harus diisi',
                'username.unique' => 'Username Sudah Digunakan',
                'password.min' => 'Password Minimal 6 Karakter',
                'role.in' => 'role yang anda pilih tidak tersedia',
            ]);

            $data = Pengguna::findOrFail($id);

            $payload = $request->only(['cabang_id', 'nama', 'username', 'role']);

            if ($request->password) {
                $payload['password'] = Hash::make($request->password);
            }

            $data->update($payload);

            notify()->success(
                'Berhasil memperbarui pengguna ' . $request->nama,
                'Edit Data Pengguna Berhasil'
            );
        } catch (Exception $e) {
            Log::error('Gagal update pengguna', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal memperbarui data pengguna ' . $request->nama,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }

        return redirect()->route('pengguna');
    }

    public function destroy($id)
    {
        try {
            $data = Pengguna::findOrFail($id);
            $data->delete();

            notify()->success(
                'Berhasil menghapus pengguna ' . $data->nama,
                'Hapus Data Pengguna Berhasil'
            );
        } catch (Exception $e) {
            Log::error('Gagal hapus pengguna', [
                '
            error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal menghapus data pengguna ' . $data->nama,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }

        return redirect()->route('pengguna');
    }
}
