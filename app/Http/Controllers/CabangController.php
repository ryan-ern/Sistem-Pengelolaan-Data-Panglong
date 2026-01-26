<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $cabang = Cabang::orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pages.cabang', compact('cabang'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_cabang' => 'required|string|max:150',
                'alamat'      => 'required|string',
            ]);

            Cabang::create($request->all());

            notify()
                ->success(
                    'Berhasil Menambahkan Data ' . $request->nama_cabang,
                    'Tambah Data Cabang Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal tambah cabang', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal menambahkan data cabang '  . $request->nama_cabang,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }
        return redirect()->route('cabang');
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_cabang' => 'required|string|max:150',
                'alamat'      => 'required|string',
            ]);

            Cabang::findOrFail($id)->update($request->all());

            notify()
                ->success(
                    'Berhasil Memperbarui Data ' . $request->nama_cabang,
                    'Edit Data Cabang Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal update cabang', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal Memperbarui data cabang '  . $request->nama_cabang,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }
        return  redirect()->route('cabang');
    }

    public function destroy($id)
    {
        try {
            $data = Cabang::findOrFail($id);
            $data->delete();

            notify()
                ->success(
                    'Berhasil Menghapus Data ' . $data->nama_cabang,
                    'Hapus Data Cabang Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal hapus cabang', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal menghapus data cabang '  . $data->nama_cabang,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }
        return  redirect()->route('cabang');
    }
}
