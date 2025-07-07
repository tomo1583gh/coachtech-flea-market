<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 小計画面で変更が反映される()
    {
        // 出品者作成
        $seller = User::factory()->create();

        // 商品作成（出品者に紐づけ）
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 購入者作成（メール認証済みにする）
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // ログイン処理
        $this->actingAs($buyer);

        // 購入画面にアクセス
        $response = $this->get(route('purchase.show', ['item_id' => $product->id]));

        // ステータスコード確認
        $response->assertStatus(200);

        // 支払い方法の選択肢が表示されているか確認
        $response->assertSee('支払い方法');
        $response->assertSee('カード支払い');
        $response->assertSee('コンビニ支払い');
    }
}