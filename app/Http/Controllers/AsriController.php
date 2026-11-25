<?php

namespace App\Http\Controllers;

class AsriController extends Controller
{
    public function index()
    {
        $d = json_decode(file_get_contents(resource_path('data/asri.json')), true);
        $divisi = collect($d['divisi'])->sortByDesc('pct')->values();

        return view('landing', [
            'total'   => $d['total'],
            'divisi'  => $divisi,
            'modules' => $d['modules'],
            'ins'     => $d['insight'],
        ]);
    }
}
