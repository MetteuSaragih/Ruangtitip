<?php

namespace Database\Seeders;

use App\Models\Courier;
use App\Models\ItemSize;
use App\Models\Storage;
use Illuminate\Database\Seeder;

/*
|--------------------------------------------------------------------------
| RuangTitipSeeder (REVISI) — harga mengikuti dokumen Laporan Akhir RUTIP
| Tabel 5.2.1 Struktur Harga Layanan (harga per BULAN)
|--------------------------------------------------------------------------
| Jalankan ulang: php artisan migrate:fresh --seed
| atau kosongkan tabel terkait lalu db:seed --class=RuangTitipSeeder
*/

class RuangTitipSeeder extends Seeder
{
    public function run(): void
    {
        // ── Gudang ──
        $storages = [
            ['name' => 'Gudang Utama Soekarno-Hatta', 'address' => 'Jl. Soekarno-Hatta No. 12, Lowokwaru', 'rating' => 4.9, 'reviews_count' => 142, 'filled' => 72, 'price' => 30000, 'tags' => ['AC', 'CCTV', '24 Jam', 'WiFi'], 'emoji' => '🏢'],
            ['name' => 'Gudang Veteran', 'address' => 'Jl. Veteran No. 3, Ketawanggede', 'rating' => 4.8, 'reviews_count' => 98, 'filled' => 52, 'price' => 30000, 'tags' => ['CCTV', '24 Jam', 'Penjaga'], 'emoji' => '🏪'],
            ['name' => 'Gudang Dieng', 'address' => 'Jl. MT. Haryono No. 165, Dinoyo', 'rating' => 4.7, 'reviews_count' => 64, 'filled' => 88, 'price' => 30000, 'tags' => ['CCTV', 'Ventilasi'], 'emoji' => '🏬'],
        ];

        $reviews = [
            ['reviewer_name' => 'Dimas A.', 'rating' => 5, 'text' => 'Gudang bersih, staf ramah. Barang aman 3 bulan penuh!', 'avatar' => 'DA', 'color' => '#7c3aed'],
            ['reviewer_name' => 'Fira R.', 'rating' => 5, 'text' => 'Proses masuk gudang cepat, foto dokumentasi lengkap.', 'avatar' => 'FR', 'color' => '#7c3aed'],
            ['reviewer_name' => 'Kevin S.', 'rating' => 4, 'text' => 'Lokasi strategis, akses mudah. Recommended!', 'avatar' => 'KS', 'color' => '#059669'],
        ];

        foreach ($storages as $s) {
            $storage = Storage::create($s);
            foreach ($reviews as $r) $storage->reviews()->create($r);
        }

        // ── Ukuran item (harga/bulan sesuai Tabel 5.2.1 dokumen) ──
        $sizes = [
            // Kardus
            ['type' => 'kardus', 'code' => 'ks',  'label' => 'S',  'dims' => '35×25×20 cm', 'price' => 30000],
            ['type' => 'kardus', 'code' => 'km',  'label' => 'M',  'dims' => '45×30×25 cm', 'price' => 45000],
            ['type' => 'kardus', 'code' => 'kl',  'label' => 'L',  'dims' => '55×35×28 cm', 'price' => 60000],
            ['type' => 'kardus', 'code' => 'kxl', 'label' => 'XL', 'dims' => '60×40×30 cm', 'price' => 75000],
            // Koper / Tas
            ['type' => 'koper', 'code' => 'kps',  'label' => 'S',  'dims' => '21"–23"', 'price' => 45000],
            ['type' => 'koper', 'code' => 'kpm',  'label' => 'M',  'dims' => '24"–25"', 'price' => 60000],
            ['type' => 'koper', 'code' => 'kpl',  'label' => 'L',  'dims' => '26"–28"', 'price' => 75000],
            ['type' => 'koper', 'code' => 'kpxl', 'label' => 'XL', 'dims' => '30"', 'price' => 90000],
            // Barang Umum (Dimensi)
            ['type' => 'dimensi', 'code' => 'dimA', 'label' => 'Dimensi A', 'dims' => 'Maks 50×50×50 cm', 'price' => 45000],
            ['type' => 'dimensi', 'code' => 'dimB', 'label' => 'Dimensi B', 'dims' => 'Maks 50×70×100 cm', 'price' => 90000],
            ['type' => 'dimensi', 'code' => 'dimC', 'label' => 'Dimensi C', 'dims' => 'Di atas 50×70×100 cm', 'price' => 135000],
        ];
        foreach ($sizes as $z) ItemSize::create($z);

        // ── Kurir ──
        // Anjem RuTip: tarif jarak (Rp/km) + jasa packing/kardus dihitung di controller.
        // price = tarif dasar layanan, price_per_km = Rp10.000/km (proyeksi dokumen Th.1)
        $couriers = [
            // Instant shipper (dummy Biteship) — tarif flat
            ['group' => 'instant', 'code' => 'gojek',    'name' => 'Gojek',    'service' => 'GoSend Instant', 'eta' => '15–30 menit', 'price' => 12000, 'price_per_km' => 0, 'logo' => '🛵'],
            ['group' => 'instant', 'code' => 'grab',     'name' => 'Grab',     'service' => 'GrabExpress',    'eta' => '20–40 menit', 'price' => 11000, 'price_per_km' => 0, 'logo' => '🟢'],
            ['group' => 'instant', 'code' => 'lalamove', 'name' => 'Lalamove', 'service' => 'Motor',          'eta' => '30–60 menit', 'price' => 14000, 'price_per_km' => 0, 'logo' => '🟡'],
            // Anjem RuTip — satu-satunya opsi untuk layanan Packing+Anjem
            ['group' => 'rutip', 'code' => 'rutip_fleet', 'name' => 'Kurir RuTip', 'service' => 'Packing + Antar-Jemput', 'eta' => 'Sesuai jadwal', 'price' => 0, 'price_per_km' => 10000, 'logo' => '🚚'],
        ];
        foreach ($couriers as $c) Courier::create($c);
    }
}
