<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetTabungan extends Model
{
    use HasFactory;

    protected $table = 'target_tabungan';
    protected $primaryKey = 'id_target';

    protected $fillable = [
        'user_id',
        'nama_target',
        'kategori',
        'target_nominal',
        'saldo_terkumpul',
        'tenggat_waktu',
        'ikon',
        'warna',
        'status',
        'catatan',
    ];

    protected $casts = [
        'target_nominal' => 'float',
        'saldo_terkumpul' => 'float',
        'tenggat_waktu' => 'date',
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiCelengan::class, 'id_target', 'id_target')->orderBy('tanggal_transaksi', 'desc')->orderBy('id_transaksi', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor: Persentase Tercapai
    public function getPersentaseAttribute()
    {
        if ($this->target_nominal <= 0) return 0;
        $pct = ($this->saldo_terkumpul / $this->target_nominal) * 100;
        return min(round($pct, 1), 100);
    }

    // Accessor: Sisa Dana yang Dibutuhkan
    public function getSisaNominalAttribute()
    {
        return max(0, $this->target_nominal - $this->saldo_terkumpul);
    }

    // Accessor: Sisa Hari ke Tenggat Waktu
    public function getSisaHariAttribute()
    {
        if (!$this->tenggat_waktu) return null;
        $today = now()->startOfDay();
        $target = $this->tenggat_waktu->startOfDay();
        return $today->diffInDays($target, false);
    }

    // Accessor: Saran Nabung per Hari
    public function getSaranHarianAttribute()
    {
        $sisa = $this->sisa_nominal;
        $hari = $this->sisa_hari;
        if ($sisa <= 0) return 0;
        if (!$hari || $hari <= 0) return $sisa;
        return ceil($sisa / $hari);
    }
}
