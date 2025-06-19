<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class FavoriteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function いいねアイコンを押下することによって、いいねした商品として登録することができる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // いいね前の確認：0件
        $this->assertEquals(0, $product->favorites()->count());

        // いいね処理を実行（POST）
        $response = $this->actingAs($user)->post(route('favorite.toggle', ['item_id' => $product->id]));

        // リダイレクトされる（戻り先は詳細ページなど）
        $response->assertRedirect();

        // DBに「favorite_product」登録があることを確認
        $this->assertDatabaseHas('favorite_product', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 最新の product モデルを取得して、いいね数が1になっているか確認
        $this->assertEquals(1, $product->fresh()->favorites()->count());

        // 商品詳細画面で「1」が表示されているか確認
        $detailResponse = $this->actingAs($user)->get(route('product.show', ['item_id' => $product->id]));
        $detailResponse->assertSee('1');
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
    {
        $user = \App\Models\User::factory()->create();
        $product = \App\Models\Product::factory()->create();

        // いいね実行
        $this->actingAs($user)->post(route('favorite.toggle', ['item_id' => $product->id]));

        $response = $this->actingAs($user)->get(route('product.show', ['item_id' => $product->id]));
        $response->assertSee('like-button favorite');
    }

    /** @test */
public function 再度いいねアイコンを押下することによって、いいねを解除することができる()
{
    $user = \App\Models\User::factory()->create();
    $product = \App\Models\Product::factory()->create();

    // 事前に1回「いいね」済みにしておく
    $this->actingAs($user)->post(route('favorite.toggle', ['item_id' => $product->id]));
    $this->assertDatabaseHas('favorite_product', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    // 再度押下 → いいね解除
    $this->actingAs($user)->post(route('favorite.toggle', ['item_id' => $product->id]));

    // DBに favorite_product 行が存在しないことを確認
    $this->assertDatabaseMissing('favorite_product', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    // 再度詳細ページにアクセス → いいね数が「0」と表示されているか確認
    $response = $this->actingAs($user)->get(route('product.show', ['item_id' => $product->id]));
    $response->assertSee('0');  // いいね数が0
}
}
