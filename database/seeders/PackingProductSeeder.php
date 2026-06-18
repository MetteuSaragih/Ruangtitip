<?php

namespace Database\Seeders;

use App\Models\PackingProduct;
use Illuminate\Database\Seeder;

class PackingProductSeeder extends Seeder
{
    /**
     * Data produk diambil persis dari desain Figma (TokoPackingPage.tsx).
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Kardus Small',       'category' => 'Kardus',    'price' => 5000,  'stock' => 85,  'discount' => null, 'emoji' => '📦',  'description' => 'Kardus kualitas premium, ukuran 20×15×10 cm. Cocok untuk barang-barang kecil seperti buku, elektronik mini, dan aksesori. Bahan double-wall tahan benturan.'],
            ['name' => 'Kardus Medium',      'category' => 'Kardus',    'price' => 8000,  'stock' => 62,  'discount' => 10,   'emoji' => '📦',  'description' => 'Kardus kuat ukuran 30×20×15 cm. Ideal untuk pakaian, sepatu, dan barang sehari-hari. Material tebal, tahan lembap.'],
            ['name' => 'Kardus Large',       'category' => 'Kardus',    'price' => 12000, 'stock' => 40,  'discount' => null, 'emoji' => '📦',  'description' => 'Kardus besar 40×30×20 cm untuk barang bervolume. Double-wall extra kuat, cocok untuk peralatan dapur dan elektronik medium.'],
            ['name' => 'Kardus Extra Large', 'category' => 'Kardus',    'price' => 18000, 'stock' => 24,  'discount' => 15,   'emoji' => '🗃️', 'description' => 'Kardus terbesar 50×40×30 cm. Kapasitas maksimal untuk banyak barang sekaligus. Dilengkapi tutup pengunci ekstra kuat.'],
            ['name' => 'Bubble Wrap 1m',     'category' => 'Pelindung', 'price' => 8500,  'stock' => 120, 'discount' => null, 'emoji' => '🫧',  'description' => 'Bubble wrap berkualitas tinggi lebar 100cm, potongan 1 meter. Lapisan udara optimal untuk melindungi barang fragil.'],
            ['name' => 'Bubble Wrap 5m',     'category' => 'Pelindung', 'price' => 35000, 'stock' => 45,  'discount' => 5,    'emoji' => '🫧',  'description' => 'Bubble wrap ekonomis 5 meter. Pilihan hemat untuk packing banyak barang. Cocok untuk pindahan kos atau gudang.'],
            ['name' => 'Lakban Coklat',      'category' => 'Perekat',   'price' => 9500,  'stock' => 200, 'discount' => null, 'emoji' => '🟫',  'description' => 'Lakban coklat 48mm × 100m. Daya rekat super kuat, anti robek. Wajib untuk menutup kardus dengan aman.'],
            ['name' => 'Lakban Transparan',  'category' => 'Perekat',   'price' => 8000,  'stock' => 180, 'discount' => null, 'emoji' => '📏',  'description' => 'Lakban bening 48mm × 100m. Ideal jika tidak ingin mengubah tampilan kemasan. Sama kuatnya dengan lakban coklat.'],
            ['name' => 'Styrofoam Sheet',    'category' => 'Pelindung', 'price' => 15000, 'stock' => 35,  'discount' => 20,   'emoji' => '⬜',  'description' => 'Lembaran styrofoam 60×90cm tebal 2cm. Pelindung sempurna untuk barang pecah belah dan elektronik sensitif.'],
            ['name' => 'Stretchwrap 100m',   'category' => 'Pelindung', 'price' => 28000, 'stock' => 28,  'discount' => null, 'emoji' => '🌀',  'description' => 'Stretchwrap 100m untuk membungkus barang berukuran besar. Tahan air dan melindungi dari debu selama penyimpanan.'],
        ];

        foreach ($products as $p) {
            PackingProduct::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
