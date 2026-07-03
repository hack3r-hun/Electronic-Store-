<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bulbs & Lighting',
                'description' => 'LED bulbs, tube lights, and all lighting solutions.',
                'children' => ['LED Bulbs', 'Tube Lights', 'Panel Lights'],
            ],
            [
                'name' => 'Capacitors & Components',
                'description' => 'Electronic components for repairs and projects.',
                'children' => ['Capacitors', 'Resistors', 'IC Chips'],
            ],
            [
                'name' => 'Wiring & Cables',
                'description' => 'Quality wires and cables for home and commercial use.',
                'children' => ['Copper Wire', 'Cable Rolls', 'Connectors'],
            ],
            [
                'name' => 'Pipes & Fittings',
                'description' => 'PVC pipes, elbows, and plumbing fittings.',
                'children' => ['PVC Pipes', 'Elbows & Tees', 'Valves'],
            ],
            [
                'name' => 'Bolts, Nuts & Fasteners',
                'description' => 'Hardware fasteners for every job.',
                'children' => ['Bolts', 'Nuts', 'Screws & Washers'],
            ],
            [
                'name' => 'Tools & Accessories',
                'description' => 'Hand tools and electrical accessories.',
                'children' => ['Pliers', 'Tape & Insulation', 'Switches'],
            ],
        ];

        foreach ($categories as $index => $data) {
            $parent = Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);

            foreach ($data['children'] as $childIndex => $childName) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName.'-'.$parent->id),
                    'is_active' => true,
                    'sort_order' => $childIndex + 1,
                ]);
            }
        }
    }
}
