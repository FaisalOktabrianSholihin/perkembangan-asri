# Landing Page Perkembangan ASRI (Laravel)

1. `composer create-project laravel/laravel asri-web`
2. Salin isi folder ini ke proyek tersebut (timpa `routes/web.php`).
3. `php artisan serve` lalu buka http://127.0.0.1:8000

Tanpa dependensi CDN, aman dipresentasikan tanpa internet.
Data: `resources/data/asri.json`. Jika Excel berubah: `python scripts/excel_to_json.py ASRI.xlsx`
Navigasi: panah (kiri/kanan/atas/bawah), titik di kanan, klik divisi untuk detail, Esc menutup.

Laporan PDF: tombol 'Cetak Laporan PDF' (route /laporan). Tambahkan ?divisi=Nama untuk satu divisi, ?lengkap=1 untuk daftar modul tidak digunakan. Pilih 'Save as PDF' pada dialog cetak.
