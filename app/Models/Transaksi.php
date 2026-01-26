<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'id',
        'user_id',
        'cabang_id',
        'tanggal',
        'jenis_transaksi',
        'informasi',
        'total',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'decimal:2',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function struk()
    {
        return $this->hasOne(Struk::class);
    }
}
