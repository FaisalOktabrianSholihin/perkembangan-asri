<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Perkembangan ASRI{{ $pilih ? ' - '.$pilih : '' }}</title>
<style>
@page{size:A4;margin:14mm 12mm}
*{box-sizing:border-box}body{font-family:"Segoe UI",Arial,sans-serif;font-size:11px;color:#1b2430;margin:0;-webkit-print-color-adjust:exact;print-color-adjust:exact}
.tool{position:sticky;top:0;background:#0e5a37;color:#fff;padding:10px 20px;display:flex;gap:14px;align-items:center;font-size:13px}
.tool button,.tool a{background:#8dc63f;color:#0b3d24;border:0;border-radius:6px;padding:7px 16px;font-weight:700;cursor:pointer;text-decoration:none;font-size:13px}.tool a.o{background:#fff}
.page{max-width:190mm;margin:0 auto;padding:12px}
.kop{display:flex;align-items:center;gap:16px;border-bottom:3px solid #46a94c;padding-bottom:10px;margin-bottom:14px}.kop img{height:52px}
.kop h1{font-size:17px;margin:0;color:#0e5a37}.kop p{margin:2px 0 0;color:#64748b}
h2{font-size:13px;color:#0e5a37;margin:16px 0 6px;border-left:4px solid #8dc63f;padding-left:8px}
.kpi{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}.kpi div{border:1px solid #cfe3d3;border-top:3px solid #46a94c;border-radius:6px;padding:8px 10px;background:#f4faf5}
.kpi b{display:block;font-size:20px;color:#0e5a37}.kpi span{color:#64748b}
table{width:100%;border-collapse:collapse;margin-top:4px}th{background:#0e5a37;color:#fff;text-align:left;padding:5px 7px}
td{padding:4px 7px;border-bottom:1px solid #dce8df}tr:nth-child(even) td{background:#f6fbf7}.n{text-align:right}
tr,.blk{break-inside:avoid}.dv{break-before:page}.dv:first-of-type{break-before:auto}
.pb{display:inline-block;width:70px;height:7px;background:#e3ece5;border-radius:4px;vertical-align:middle;margin-right:5px;overflow:hidden}.pb i{display:block;height:100%;background:#2fa04f}
.no{color:#b42323}.up{color:#946300}.ok{color:#1d7a3d}
.note{margin:6px 0;padding:6px 10px;background:#fff8e6;border-left:3px solid #e0a21b}
.foot{margin-top:18px;color:#64748b;font-size:10px;border-top:1px solid #dce8df;padding-top:6px}
@media print{.tool{display:none}.page{padding:0;max-width:none}}
</style>
</head>
<body>
<div class="tool">
  <button onclick="window.print()">⎙ Cetak / Simpan sebagai PDF</button>
  <a class="o" href="{{ route('laporan', array_filter(['divisi'=>$pilih, 'lengkap'=>$lengkap?null:1])) }}">{{ $lengkap ? 'Versi ringkas' : 'Sertakan daftar modul tidak digunakan' }}</a>
  <a class="o" href="{{ route('asri') }}">← Kembali</a>
  <span>Pada dialog cetak, pilih “Save as PDF” dan aktifkan “Background graphics”.</span>
</div>
<div class="page">
  <div class="kop"><img src="{{ asset('img/logo.png') }}" alt="Mitratani Dua Tujuh">
    <div><h1>Laporan Perkembangan Implementasi ASRI{{ $pilih ? ' — Divisi '.$pilih : '' }}</h1>
    <p>PT Mitratani Dua Tujuh · Dicetak {{ date('d/m/Y H:i') }}</p></div></div>

  <h2>Ringkasan Keseluruhan</h2>
  <div class="kpi">
    <div><b>{{ $total['total'] }}</b><span>Total Modul</span></div>
    <div><b>{{ $total['used'] }} ({{ number_format($total['pct'],1,',','.') }}%)</b><span>Digunakan</span></div>
    <div><b>{{ $total['unused'] }}</b><span>Tidak Digunakan</span></div>
    <div><b>{{ $total['update'] }}</b><span>Butuh Update</span></div>
  </div>

  @unless($pilih)
  <h2>Perbandingan Divisi</h2>
  <table><tr><th>#</th><th>Divisi</th><th class="n">Digunakan</th><th class="n">Tidak</th><th class="n">Update</th><th class="n">Total</th><th>% Digunakan</th></tr>
  @foreach($divisi as $d)
  <tr><td>{{ $loop->iteration }}</td><td><b>{{ $d['name'] }}</b></td><td class="n">{{ $d['used'] }}</td><td class="n">{{ $d['unused'] }}</td><td class="n">{{ $d['update'] }}</td><td class="n">{{ $d['total'] }}</td>
  <td><span class="pb"><i style="width:{{ $d['pct'] }}%"></i></span>{{ number_format($d['pct'],1,',','.') }}%</td></tr>
  @endforeach
  <tr><td></td><td><b>TOTAL</b></td><td class="n"><b>{{ $total['used'] }}</b></td><td class="n"><b>{{ $total['unused'] }}</b></td><td class="n"><b>{{ $total['update'] }}</b></td><td class="n"><b>{{ $total['total'] }}</b></td><td><b>{{ number_format($total['pct'],1,',','.') }}%</b></td></tr></table>

  <h2>Catatan Utama</h2>
  <div class="note">Divisi dengan penggunaan di bawah 50%: <b>{{ implode(', ', $ins['low']) }}</b>.</div>
  <div class="note">{{ $ins['sipas'] }} modul tidak digunakan karena digantikan SIPAS; {{ $ins['ti'] }} modul menu File digunakan oleh TI.</div>
  <div class="note">Modul butuh update ({{ count($ins['update']) }}):
    @foreach($ins['update'] as $u){{ $u['div'] }} – {{ $u['nama'] }}{{ $u['ket'] ? ' ('.$u['ket'].')' : '' }}{{ !$loop->last ? '; ' : '.' }}@endforeach</div>
  @endunless

  @foreach($tampil as $d)
  <section class="dv">
    <h2>Divisi {{ $d['name'] }} — {{ number_format($d['pct'],1,',','.') }}% digunakan ({{ $d['used'] }} dari {{ $d['total'] }} modul)</h2>
    <table><tr><th>Menu / Sistem Utama</th><th class="n">Digunakan</th><th class="n">Tidak</th><th class="n">Update</th><th class="n">Total</th><th>% Digunakan</th></tr>
    @foreach($modules[$d['name']] as $m)
      @php $it = collect($m['items']); $u = $it->where(1,'Digunakan')->count(); @endphp
      <tr><td>{{ $m['menu'] }}</td><td class="n ok">{{ $u }}</td><td class="n no">{{ $it->where(1,'Tidak Digunakan')->count() }}</td><td class="n up">{{ $it->where(1,'Butuh Update')->count() }}</td><td class="n">{{ $it->count() }}</td>
      <td><span class="pb"><i style="width:{{ $u/$it->count()*100 }}%"></i></span>{{ number_format($u/$it->count()*100,0) }}%</td></tr>
    @endforeach</table>
    @php
      $rows = collect($modules[$d['name']])->flatMap(fn($m) => collect($m['items'])->map(fn($i) => [$m['menu'], ...$i]))
              ->filter(fn($r) => $r[2] === 'Butuh Update' || ($lengkap && $r[2] === 'Tidak Digunakan'));
    @endphp
    @if($rows->count())
    <table style="margin-top:8px"><tr><th>Menu</th><th>Modul</th><th>Status</th><th>Keterangan</th></tr>
    @foreach($rows as $r)<tr><td>{{ $r[0] }}</td><td>{{ $r[1] }}</td><td class="{{ $r[2]==='Butuh Update'?'up':'no' }}">{{ $r[2] }}</td><td>{{ $r[3] }}</td></tr>@endforeach</table>
    @endif
  </section>
  @endforeach
  <p class="foot">Sumber data: ASRI.xlsx (Ringkasan Modul per Divisi &amp; Master Data Modul).</p>
</div>
</body>
</html>
