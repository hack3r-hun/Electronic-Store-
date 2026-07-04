<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'admin']);
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $category = Category::create(['name' => 'Bulbs', 'slug' => 'bulbs', 'is_active' => true]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'New LED Bulb',
            'sku' => 'LED-NEW',
            'price' => 350,
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        $product = Product::where('sku', 'LED-NEW')->first();

        $response->assertRedirect(route('admin.products.edit', $product));
        $this->assertDatabaseHas('products', ['sku' => 'LED-NEW']);
    }

    public function test_non_admin_cannot_access_admin_products(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->get(route('admin.products.index'));
        $response->assertForbidden();
    }

    public function test_non_admin_cannot_write_products(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $category = Category::create(['name' => 'Bulbs', 'slug' => 'bulbs', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'LED Bulb',
            'slug' => 'led-bulb',
            'sku' => 'LED-1',
            'price' => 100,
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $payload = [
            'category_id' => $category->id,
            'name' => 'Hacked',
            'sku' => 'HAX-1',
            'price' => 1,
            'stock_quantity' => 1,
        ];

        $this->actingAs($user)->post(route('admin.products.store'), $payload)->assertForbidden();
        $this->actingAs($user)->put(route('admin.products.update', $product), $payload)->assertForbidden();
        $this->actingAs($user)->delete(route('admin.products.destroy', $product))->assertForbidden();

        $this->assertDatabaseHas('products', ['sku' => 'LED-1', 'name' => 'LED Bulb']);
        $this->assertDatabaseMissing('products', ['sku' => 'HAX-1']);
    }
}
