<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 全商品が表示される()
    {
        $products = Product::factory()->count(3)->create([
            'is_sold' => false,
        ]);

        $response = $this->get('/');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    /** @test */
    public function 購入済み商品にはSoldラベルが表示される()
    {
        $soldProduct = Product::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('SOLD');
        $response->assertSee($soldProduct->name);
    }

    /** @test */
    public function 自分が出品した商品は一覧に表示されない()
    {
        $user = User::factory()->createOne();
        $otherProduct = Product::factory()->create(); // 他人の商品
        $myProduct = Product::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertSee($otherProduct->name);
        $response->assertDontSee($myProduct->name);
    }
}
