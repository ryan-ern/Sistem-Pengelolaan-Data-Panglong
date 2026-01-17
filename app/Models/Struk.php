<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    use HasFactory;

    protected $table = 'struk';

    protected $fillable = [
        'transaksi_id',
        'tanggal_cetak',
        'status_struk',
    ];

    protected $casts = [
        'tanggal_cetak' => 'date',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
