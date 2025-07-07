<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ShippingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 送付先住所変更にてに登録した住所が商品購入画面に反映されている()
    {
        // 出品者と商品を用意
        $seller = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        // 購入者としてログイン
        $buyer = User::factory()->create([
            'zip' => '000-0000',
            'address' => '旧住所',
            'building' => '旧ビル',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        // 送付先住所を変更する
        $response = $this->post(route('purchase.address.update', ['item_id' => $product->id]), [
            'zip' => '123-4567',
            'address' => '東京都港区新橋',
            'building' => '第一ビル',
        ]);

        $response->assertRedirect(route('purchase.show', ['item_id' => $product->id]));

        // 購入画面を開いて、住所が反映されていることを確認
        $response = $this->get(route('purchase.show', ['item_id' => $product->id]));

        $response->assertSee('123-4567');
        $response->assertSee('東京都港区新橋');
        $response->assertSee('第一ビル');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        // 出品者と商品を用意
        $seller = User::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 購入者としてログイン
        $buyer = User::factory()->create([
            'zip' => '123-4567',
            'address' => '東京都港区芝公園',
            'building' => '芝タワー',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        // 商品購入リクエストを実行
        $response = $this->post(route('purchase.complete', ['item_id' => $product->id]), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('mypage', ['page' => 'buy']));

        // 購入後の情報を検証
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $buyer->id,
            'is_sold' => true,
            'zip' => '123-4567',
            'address' => '東京都港区芝公園',
            'building' => '芝タワー',
        ]);
    }
}
