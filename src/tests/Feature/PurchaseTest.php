<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 「購入する」ボタンを押下すると購入が完了する()
    {
        // 出品者を作成
        $seller = User::factory()->create();

        // 商品を出品者に紐づけて作成
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 購入者を作成してログイン
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        // 購入処理を実行（payment_method を追加）
        $response = $this->post(route('purchase.complete', ['item_id' => $product->id]), [
            'payment_method' => 'card',
        ]);

        // リダイレクト確認（必要に応じて修正）
        $response->assertRedirect(route('mypage', ['page' => 'buy']));

        // DBの更新確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $buyer->id,
            'is_sold' => true,
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面にて「SOLD」と表示される()
    {
        // 出品者を作成
        $seller = User::factory()->create();

        // 商品を出品者に紐づけて作成
        $product = Product::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 購入者を作成してログイン
        $buyer = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($buyer);

        // 購入処理（payment_methodがバリデーションで必須）
        $this->post(route('purchase.complete', ['item_id' => $product->id]), [
            'payment_method' => 'card',
        ]);

        // 商品一覧ページを確認
        $response = $this->get(route('top'));

        // 商品名と "SOLD" 表記が含まれているか確認
        $response->assertSee($product->name);
        $response->assertSee('SOLD');
    }

    /** @test */
public function 「プロフィール購入した商品一覧」に追加される()
{
    $this->withoutExceptionHandling();

    // 出品者と商品を作成
    $seller = User::factory()->create();
    $product = Product::factory()->create([
        'user_id' => $seller->id,
    ]);

    // 購入者を作成・ログイン
    $buyer = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $this->actingAs($buyer);

    // 購入処理
    $this->post(route('purchase.complete', ['item_id' => $product->id]), [
        'payment_method' => 'card', // or 'convenience'
    ]);

    // プロフィールの購入一覧ページへアクセス
    $response = $this->get(route('mypage', ['page' => 'buy']));

    // 商品名が含まれていれば、購入一覧に追加されているとみなせる
    $response->assertStatus(200);
    $response->assertSee($product->name);
}
}
