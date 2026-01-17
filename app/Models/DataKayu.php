<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKayu extends Model
{
    use HasFactory;

    protected $table = 'data_kayu';

    protected $fillable = [
        'cabang_id',
        'jenis_kayu',
        'jumlah',
        'harga_satuan',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}
