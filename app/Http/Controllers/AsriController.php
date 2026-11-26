<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsriController extends Controller
{
    private function load(): array
    {
        return json_decode(file_get_contents(resource_path('data/asri.json')), true);
    }

    public function index()
    {
        $d = $this->load();

        return view('landing', [
            'total'   => $d['total'],
            'divisi'  => collect($d['divisi'])->sortByDesc('pct')->values(),
            'modules' => $d['modules'],
            'ins'     => $d['insight'],
        ]);
    }

    // Halaman laporan khusus cetak / simpan PDF.
    // ?divisi=Nama  -> hanya satu divisi | ?lengkap=1 -> sertakan daftar modul tidak digunakan
    public function laporan(Request $r)
    {
        $d = $this->load();
        $divisi = collect($d['divisi'])->sortByDesc('pct')->values();
        $pilih = $r->query('divisi');

        return view('laporan', [
            'total'   => $d['total'],
            'divisi'  => $divisi,
            'tampil'  => $pilih ? $divisi->where('name', $pilih)->values() : $divisi,
            'modules' => $d['modules'],
            'ins'     => $d['insight'],
            'lengkap' => $r->boolean('lengkap'),
            'pilih'   => $pilih,
        ]);
    }
}
