<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class MyListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        // ログインユーザーと別の出品者を作成
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 他の出品者の商品を2つ作成
        $productLiked = Product::factory()->create(['user_id' => $otherUser->id]);
        $productNotLiked = Product::factory()->create(['user_id' => $otherUser->id]);

        // ログインユーザーが1つだけ「いいね」
        $user->favorites()->attach($productLiked->id);

        // 認証状態で「マイリスト」にアクセス
        $response = $this->actingAs($user)->get('/?page=mylist');

        // いいねした商品は表示される
        $response->assertSee($productLiked->name);

        // いいねしていない商品は表示されない
        $response->assertDontSee($productNotLiked->name);
    }

    /** @test */
    public function 購入済み商品は「SOLD」と表示される()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 購入済み商品を作成（is_sold: true）
        $soldProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'is_sold' => true,
            'name' => 'Sold Product',
        ]);

        // 未購入の商品
        $availableProduct = Product::factory()->create([
            'user_id' => $otherUser->id,
            'is_sold' => false,
            'name' => 'Available Product',
        ]);

        // ログインユーザーが両方に「いいね」
        $user->favorites()->attach([$soldProduct->id, $availableProduct->id]);

        // マイリストページにアクセス
        $response = $this->actingAs($user)->get('/?page=mylist');

        // 購入済み商品には「Sold」の表示
        $response->assertSee('SOLD');

        // 購入していない商品名が表示されていてもよい（今回は両方表示される）
        $response->assertSee($soldProduct->name);
        $response->assertSee($availableProduct->name);
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        // 自分が出品した商品（自分自身が出品者）
        $ownProduct = Product::factory()->create(['user_id' => $user->id, 'name' => 'Self Product']);

        // 他人が出品した商品
        $otherUser = User::factory()->create();
        $otherProduct = Product::factory()->create(['user_id' => $otherUser->id, 'name' => 'Other Product']);

        // 自分が両方に「いいね」
        $user->favorites()->attach([$ownProduct->id, $otherProduct->id]);

        // マイリストページにアクセス
        $response = $this->actingAs($user)->get('/?page=mylist');

        // 自分が出品した商品は表示されない
        $response->assertDontSee($ownProduct->name);

        // 他人が出品した商品は表示される
        $response->assertSee($otherProduct->name);
    }

    /** @test */
public function 未承認の場合は何も表示されない()
{
    $response = $this->get('/?page=mylist');

    // 「マイリストはありません。」が表示されていることを確認
    $response->assertSee('マイリストはありません。');

    // 念のため、商品カードがないことも確認
    $response->assertDontSee('product-card');

    // ステータスコードが正常であることを確認
    $response->assertStatus(200);
}
}