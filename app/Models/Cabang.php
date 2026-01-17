<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function dataKayu()
    {
        return $this->hasMany(DataKayu::class);
    }

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
