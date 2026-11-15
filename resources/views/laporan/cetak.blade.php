<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Mutasi Tabungan - {{ date('d-m-Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #fff;
            color: #1e293b;
            padding: 30px;
            font-size: 11pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header h2 {
            font-size: 13pt;
            font-weight: 600;
            color: #2563eb;
            margin-top: 2px;
        }
        .header p {
            font-size: 9.5pt;
            color: #64748b;
            margin-top: 4px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-data th, .table-data td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-size: 9.5pt;
        }
        .table-data th {
            background-color: #f1f5f9;
            color: #0f172a;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
            letter-spacing: 0.5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .font-bold { font-weight: bold; }
        .badge-setor {
            color: #15803d;
            font-weight: bold;
        }
        .badge-tarik {
            color: #b91c1c;
            font-weight: bold;
        }
        .summary-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 320px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .summary-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .signatures {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }
        .sign-space {
            height: 70px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak (Hanya tampil di layar) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background-color: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Dokumen Ini
        </button>
    </div>

    <!-- Kop Laporan -->
    <div class="header">
        <h1>SMK JAKARTA PUSAT 1</h1>
        <h2>CELENGAN IMPIAN - LAPORAN REKAPITULASI TABUNGAN</h2>
        <p>Aplikasi Pengelolaan & Progres Tabungan Mandiri Siswa | Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Informasi Siswa / Pemilik -->
    <table class="meta-table">
        <tr>
            <td style="width: 18%;"><strong>Nama Siswa</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%;">{{ Auth::user()->name ?? 'Haikal Suryadarmansyah' }}</td>
            <td style="width: 18%;"><strong>Status Tabungan</strong></td>
            <td style="width: 2%;">:</td>
            <td style="width: 25%;"><span style="color: #16a34a; font-weight: bold;">Aktif & Terverifikasi</span></td>
        </tr>
        <tr>
            <td><strong>Username / NIS</strong></td>
            <td>:</td>
            <td>{{ Auth::user()->username ?? 'haikal' }} (12460)</td>
            <td><strong>Periode Mutasi</strong></td>
            <td>:</td>
            <td>{{ request('tgl_mulai') ? date('d/m/Y', strtotime(request('tgl_mulai'))) : 'Semua Periode' }} s/d {{ request('tgl_selesai') ? date('d/m/Y', strtotime(request('tgl_selesai'))) : 'Saat Ini' }}</td>
        </tr>
    </table>

    <!-- Tabel Data Mutasi -->
    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">No</th>
                <th style="width: 85px;">Tanggal</th>
                <th>Target Impian</th>
                <th class="text-center" style="width: 65px;">Jenis</th>
                <th style="width: 110px;">Sumber Dana</th>
                <th>Keterangan</th>
                <th class="text-right" style="width: 115px;">Nominal</th>
                <th class="text-right" style="width: 120px;">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $trx)
                <tr>
                    <td class="text-center font-mono">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td class="font-bold">{{ $trx->target->nama_target ?? '-' }}</td>
                    <td class="text-center">
                        @if($trx->jenis_transaksi === 'SETOR')
                            <span class="badge-setor">SETOR</span>
                        @else
                            <span class="badge-tarik">TARIK</span>
                        @endif
                    </td>
                    <td>{{ $trx->sumber_dana }}</td>
                    <td>{{ $trx->keterangan ?: '-' }}</td>
                    <td class="text-right font-mono font-bold {{ $trx->jenis_transaksi === 'SETOR' ? 'badge-setor' : 'badge-tarik' }}">
                        {{ $trx->jenis_transaksi === 'SETOR' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                    </td>
                    <td class="text-right font-mono">
                        Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data mutasi yang tercatat</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Akumulasi Saldo -->
    <div style="width: 100%; display: table; margin-bottom: 20px;">
        <div style="display: table-cell; width: 55%; vertical-align: top;">
            <p style="font-size: 9pt; color: #64748b; font-style: italic;">
                * Laporan ini dicetak secara otomatis melalui portal Celengan Impian dan merupakan bukti sah mutasi tabungan pribadi.
            </p>
        </div>
        <div style="display: table-cell; width: 45%; vertical-align: top;">
            <table class="summary-table" style="width: 100%;">
                <tr>
                    <td><strong>Total Setoran:</strong></td>
                    <td class="text-right font-mono" style="color: #16a34a; font-weight: bold;">Rp {{ number_format($totalSetor, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Penarikan:</strong></td>
                    <td class="text-right font-mono" style="color: #dc2626; font-weight: bold;">Rp {{ number_format($totalTarik, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td><strong>Saldo Tersimpan Bersih:</strong></td>
                    <td class="text-right font-mono" style="color: #2563eb; font-size: 11pt;">Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Orang Tua / Wali Siswa</strong>
                <div class="sign-space"></div>
                ( .................................................. )
            </td>
            <td>
                Jakarta, {{ date('d F Y') }}<br>
                <strong>Pemilik Tabungan / Siswa</strong>
                <div class="sign-space"></div>
                ( <strong>{{ Auth::user()->name ?? 'Haikal Suryadarmansyah' }}</strong> )
            </td>
        </tr>
    </table>

</body>
</html>
