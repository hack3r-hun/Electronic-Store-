<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['LED Bulb 9W Warm White', 'Bulbs & Lighting', 350, 299, 120, true],
            ['LED Bulb 12W Cool White', 'Bulbs & Lighting', 450, null, 85, true],
            ['20W LED Tube Light', 'Bulbs & Lighting', 650, 599, 60, false],
            ['100uF Electrolytic Capacitor', 'Capacitors & Components', 25, null, 500, false],
            ['470uF 25V Capacitor Pack (10pc)', 'Capacitors & Components', 180, 150, 200, true],
            ['1.5mm Copper Wire (90m roll)', 'Wiring & Cables', 2800, 2650, 40, true],
            ['2.5mm Twin Core Cable (per meter)', 'Wiring & Cables', 95, null, 300, false],
            ['PVC Pipe 1 inch (3m)', 'Pipes & Fittings', 420, null, 75, false],
            ['PVC Elbow 1 inch (pack of 10)', 'Pipes & Fittings', 350, 320, 90, false],
            ['M8 Hex Bolt (pack of 50)', 'Bolts, Nuts & Fasteners', 280, null, 150, false],
            ['M8 Nut (pack of 50)', 'Bolts, Nuts & Fasteners', 180, 160, 180, true],
            ['Insulation Tape Black', 'Tools & Accessories', 45, null, 250, false],
            ['16A Switch (white)', 'Tools & Accessories', 120, 99, 100, true],
            ['Combination Pliers 8 inch', 'Tools & Accessories', 550, 499, 35, true],
            ['MCB 32A Single Pole', 'Capacitors & Components', 380, null, 55, true],
            ['Ceiling Fan Capacitor 2.25uF', 'Capacitors & Components', 85, 75, 220, false],
        ];

        foreach ($products as $index => [$name, $categoryName, $price, $salePrice, $stock, $featured]) {
            $category = Category::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($name).'-'.($index + 1),
                'sku' => 'EM-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'description' => "High quality {$name} available at ".shop_name().'. Trusted by local electricians and contractors.',
                'price' => $price,
                'sale_price' => $salePrice,
                'stock_quantity' => $stock,
                'low_stock_threshold' => 10,
                'specifications' => [
                    'Brand' => 'ElectroMart',
                    'Warranty' => '6 Months',
                    'Origin' => 'Local Market Standard',
                ],
                'is_active' => true,
                'is_featured' => $featured,
            ]);
        }
    }
}
