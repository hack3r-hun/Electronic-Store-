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
}
