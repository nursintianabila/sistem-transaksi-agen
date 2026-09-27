<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
    'jenis_transaksi',
    'nama_pelanggan',
    'nomor_tujuan',
    'nominal',
    'biaya_admin',
    'total',
    'tanggal_transaksi',
    ];
}