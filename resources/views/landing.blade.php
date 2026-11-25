<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Perkembangan ASRI per Divisi</title>
<link rel="stylesheet" href="{{ asset('css/asri.css') }}">
</head>
<body>
<nav class="dots" id="dots"></nav>
<header class="top"><b>ASRI</b><span>Paparan Perkembangan Implementasi · Direksi &amp; Komisaris Utama</span></header>

<main id="deck">
<section class="slide hero" data-title="Ringkasan">
  <div class="wrap">
    <p class="eyebrow">Paparan Manajemen</p>
    <h1>Perkembangan Implementasi <em>ASRI</em><br>di Seluruh Divisi</h1>
    <p class="lead">Status penggunaan {{ $total['total'] }} modul pada {{ $divisi->count() }} divisi/bagian.</p>
    <div class="hero-grid">
      <div class="gauge" style="--p:{{ $total['pct'] }}"><div><b>{{ number_format($total['pct'],1,',','.') }}%</b><span>modul digunakan</span></div></div>
      <div class="kpis">
        <div class="kpi"><b>{{ $total['total'] }}</b><span>Total Modul</span></div>
        <div class="kpi ok"><b>{{ $total['used'] }}</b><span>Digunakan</span></div>
        <div class="kpi no"><b>{{ $total['unused'] }}</b><span>Tidak Digunakan</span></div>
        <div class="kpi up"><b>{{ $total['update'] }}</b><span>Butuh Update</span></div>
      </div>
    </div>
  </div>
</section>

<section class="slide" data-title="Peringkat Divisi">
  <div class="wrap">
    <p class="eyebrow">Perbandingan Divisi</p>
    <h2>Tingkat penggunaan modul per divisi</h2>
    <div class="legend"><i class="c-ok"></i>&nbsp;&nbsp;Digunakan <i class="c-no"></i>&nbsp;&nbsp;Tidak digunakan <i class="c-up"></i>&nbsp;&nbsp;Butuh update</div>
    <div class="rank">
      @foreach($divisi as $d)
      <button class="row" data-div="{{ $d['name'] }}">
        <span class="nm">{{ $d['name'] }}</span>
        <span class="bar"><i class="c-ok" style="width:{{ $d['used']/$d['total']*100 }}%"></i><i class="c-up" style="width:{{ $d['update']/$d['total']*100 }}%"></i><i class="c-no" style="width:{{ $d['unused']/$d['total']*100 }}%"></i></span>
        <span class="pc {{ $d['pct']>=75?'g':($d['pct']>=50?'a':'r') }}">{{ number_format($d['pct'],1,',','.') }}%</span>
        <span class="ct">{{ $d['used'] }}/{{ $d['total'] }}</span>
      </button>
      @endforeach
    </div>
  </div>
</section>

<section class="slide" data-title="Detail Divisi">
  <div class="wrap">
    <p class="eyebrow">Detail per Divisi</p>
    <h2>Klik divisi untuk melihat rincian modul</h2>
    <div class="cards">
      @foreach($divisi as $d)
      <button class="card" data-div="{{ $d['name'] }}">
        <h3>{{ $d['name'] }}</h3>
        <div class="big {{ $d['pct']>=75?'g':($d['pct']>=50?'a':'r') }}">{{ number_format($d['pct'],1,',','.') }}%</div>
        <div class="mini"><i style="width:{{ $d['pct'] }}%"></i></div>
        <p>{{ $d['used'] }} digunakan · {{ $d['unused'] }} tidak{{ $d['update'] ? ' · '.$d['update'].' update' : '' }}</p>
      </button>
      @endforeach
    </div>
  </div>
</section>

<section class="slide" data-title="Sorotan">
  <div class="wrap">
    <p class="eyebrow">Sorotan Utama</p>
    <h2>Catatan penting dari data</h2>
    <div class="insights">
      <article><h4>Tertinggi</h4><p><b>{{ $divisi[0]['name'] }}</b> {{ number_format($divisi[0]['pct'],1,',','.') }}% — diikuti {{ $divisi[1]['name'] }} ({{ number_format($divisi[1]['pct'],1,',','.') }}%) dan {{ $divisi[2]['name'] }} ({{ number_format($divisi[2]['pct'],1,',','.') }}%).</p></article>
      <article class="warn"><h4>Di bawah 50%</h4><p>{{ count($ins['low']) }} divisi: <b>{{ implode(', ', $ins['low']) }}</b>.</p></article>
      <article><h4>Digantikan SIPAS</h4><p><b>{{ $ins['sipas'] }}</b> modul tercatat tidak digunakan karena fungsinya sudah digantikan/ada di SIPAS.</p></article>
      <article><h4>Butuh Update</h4><p><b>{{ count($ins['update']) }}</b> modul:
        @foreach($ins['update'] as $u){{ $u['div'] }} – {{ $u['nama'] }}{{ $u['ket'] ? ' ('.$u['ket'].')' : '' }}{{ !$loop->last ? '; ' : '.' }}@endforeach</p></article>
      <article><h4>Menu File (TI)</h4><p><b>{{ $ins['ti'] }}</b> modul bertanda “digunakan oleh TI” (menu administrasi sistem), bukan oleh pengguna divisi.</p></article>
      <article><h4>Catatan Lain</h4><p>{{ $ins['wms'] }} modul Pengolahan beralih ke WMS; sebagian modul Pengolahan belum diperbarui sesuai kebutuhan.</p></article>
    </div>
  </div>
</section>

<section class="slide" data-title="Tindak Lanjut">
  <div class="wrap">
    <p class="eyebrow">Usulan Tindak Lanjut</p>
    <h2>Langkah berikutnya</h2>
    <ol class="steps">
      <li><b>Evaluasi modul tidak digunakan</b> pada divisi berpenggunaan rendah ({{ implode(', ', $ins['low']) }}): pertahankan, sederhanakan, atau nonaktifkan.</li>
      <li><b>Selesaikan {{ count($ins['update']) }} modul “Butuh Update”</b> sesuai catatan masing-masing divisi.</li>
      <li><b>Validasi modul yang digantikan SIPAS</b> untuk rasionalisasi dan penyederhanaan aplikasi.</li>
      <li><b>Dorong pemanfaatan</b> dengan pendampingan pengguna dan pemantauan persentase penggunaan berkala.</li>
    </ol>
    <p class="foot">Sumber data: ASRI.xlsx — Ringkasan Modul per Divisi &amp; Master Data Modul.</p>
  </div>
</section>
</main>

<div class="modal" id="modal" hidden>
  <div class="panel">
    <button class="x" id="close" aria-label="Tutup">&times;</button>
    <div id="mbody"></div>
  </div>
</div>

<script>window.ASRI = @json(['divisi' => $divisi, 'modules' => $modules]);</script>
<script src="{{ asset('js/asri.js') }}"></script>
</body>
</html>
