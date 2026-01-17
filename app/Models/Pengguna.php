<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'Pengguna';

    protected $fillable = [
        'cabang_id',
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }
}
