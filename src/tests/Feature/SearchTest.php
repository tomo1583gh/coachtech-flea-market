<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 「商品名」で部分一致検索ができる()
    {
        // 商品を作成
        $user = User::factory()->create();

        // 出品者は他人
        $otherUser = User::factory()->create();

        $productA = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '青りんご'
        ]);

        $productB = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '赤いりんご'
        ]);

        $productC = Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'バナナ'
        ]);

        // ログイン状態で「りんご」で検索
        $response = $this->actingAs($user)->get('/?keyword=りんご');

        // 「りんご」を含む商品は表示される
        $response->assertSee($productA->name);
        $response->assertSee($productB->name);

        // 「りんご」を含まない商品は表示されない
        $response->assertDontSee($productC->name);
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = \App\Models\User::factory()->create();
        $otherUser = \App\Models\User::factory()->create();

        // 「りんご」含む商品と含まない商品を作成
        $likedApple = \App\Models\Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '青りんご'
        ]);

        $likedBanana = \App\Models\Product::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'バナナ'
        ]);

        // ログインユーザーが両方をお気に入り登録
        $user->favorites()->attach([$likedApple->id, $likedBanana->id]);

        // 検索状態でマイリストにアクセス（keyword=りんご）
        $response = $this->actingAs($user)->get('/?page=mylist&keyword=りんご');

        // 「りんご」を含む商品は表示される
        $response->assertSee($likedApple->name);

        // 「りんご」を含まない商品は表示されない
        $response->assertDontSee($likedBanana->name);
    }
}
