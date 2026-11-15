<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Mutasi Tabungan</title>
    <style>
        .header { font-size: 14pt; font-weight: bold; text-align: center; }
        .sub { font-size: 11pt; text-align: center; color: #555; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #2563EB; color: #ffffff; border: 1px solid #000000; padding: 6px; text-align: center; font-weight: bold; }
        td { border: 1px solid #000000; padding: 5px; }
        .num { mso-number-format: "\#\,\#\#0"; text-align: right; }
        .str { mso-number-format: "\@"; }
        .date { mso-number-format: "dd\/mm\/yyyy"; text-align: center; }
        .setor { color: #15803d; font-weight: bold; }
        .tarik { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="8" class="header">CELENGAN IMPIAN - LAPORAN MUTASI TABUNGAN</td>
        </tr>
        <tr>
            <td colspan="8" class="sub">SMK JAKARTA PUSAT 1 - Rekapitulasi Riwayat Tabungan Pribadi Siswa</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; font-size: 9pt;">Waktu Unduh: {{ date('d/m/Y H:i:s') }} | Pemilik: {{ Auth::user()->name ?? 'Haikal Suryadarmansyah' }}</td>
        </tr>
        <tr><td colspan="8"></td></tr>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Target Impian</th>
                <th>Jenis Transaksi</th>
                <th>Sumber Dana</th>
                <th>Keterangan</th>
                <th>Nominal (Rp)</th>
                <th>Saldo Akhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totSetor = 0;
                $totTarik = 0;
            @endphp
            @forelse($transaksi as $i => $t)
                @php
                    if($t->jenis_transaksi === 'SETOR') $totSetor += $t->nominal;
                    else $totTarik += $t->nominal;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td>{{ $t->target->nama_target ?? '-' }}</td>
                    <td style="text-align: center;" class="{{ $t->jenis_transaksi === 'SETOR' ? 'setor' : 'tarik' }}">
                        {{ $t->jenis_transaksi }}
                    </td>
                    <td>{{ $t->sumber_dana }}</td>
                    <td>{{ $t->keterangan }}</td>
                    <td class="num {{ $t->jenis_transaksi === 'SETOR' ? 'setor' : 'tarik' }}">
                        {{ $t->jenis_transaksi === 'SETOR' ? $t->nominal : -$t->nominal }}
                    </td>
                    <td class="num">{{ $t->saldo_sesudah }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data mutasi</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right; background-color: #f1f5f9; color: #000;">TOTAL SETORAN:</th>
                <th class="num setor" style="background-color: #f1f5f9;">{{ $totSetor }}</th>
                <th style="background-color: #f1f5f9;"></th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: right; background-color: #f1f5f9; color: #000;">TOTAL PENARIKAN:</th>
                <th class="num tarik" style="background-color: #f1f5f9;">{{ $totTarik }}</th>
                <th style="background-color: #f1f5f9;"></th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: right; background-color: #e2e8f0; color: #000;">SALDO BERSIH TERSIMPAN:</th>
                <th class="num" style="background-color: #e2e8f0; color: #2563eb; font-weight: bold;">{{ $totSetor - $totTarik }}</th>
                <th style="background-color: #e2e8f0;"></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
