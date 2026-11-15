<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiCelengan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_celengan';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_target',
        'jenis_transaksi',
        'nominal',
        'sumber_dana',
        'tanggal_transaksi',
        'keterangan',
        'saldo_sebelum',
        'saldo_sesudah',
    ];

    protected $casts = [
        'nominal' => 'float',
        'saldo_sebelum' => 'float',
        'saldo_sesudah' => 'float',
        'tanggal_transaksi' => 'date',
    ];

    public function target()
    {
        return $this->belongsTo(TargetTabungan::class, 'id_target', 'id_target');
    }
}
