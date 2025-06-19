<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;

class DetailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が表示される()
    {
        $user = User::factory()->create();

        // カテゴリを2つ作成
        $category1 = Category::factory()->create(['name' => '果物']);
        $category2 = Category::factory()->create(['name' => '季節限定']);

        // 商品作成
        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '青りんご',
            'brand' => 'りんご園',
            'price' => 1234,
            'description' => 'おいしい青りんごです。',
            'condition' => '新品'
        ]);

        // カテゴリを紐づけ
        $product->categories()->attach([$category1->id, $category2->id]);

        // コメントを2件登録
        $commenter1 = User::factory()->create(['name' => 'たろう']);
        $commenter2 = User::factory()->create(['name' => 'はなこ']);

        Comment::create([
            'user_id' => $commenter1->id,
            'product_id' => $product->id,
            'body' => '美味しそうですね！'
        ]);

        Comment::create([
            'user_id' => $commenter2->id,
            'product_id' => $product->id,
            'body' => '買いたいです！'
        ]);

        // 詳細ページにアクセス
        $response = $this->get(route('product.show', ['item_id' => $product->id]));

        // 商品基本情報
        $response->assertSee($product->name);
        $response->assertSee($product->brand);
        $response->assertSee(number_format($product->price));
        $response->assertSee($product->description);

        // 商品状態
        $response->assertSee($product->condition);

        // カテゴリ名
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);

        // コメント数
        $response->assertSee('コメント (2)');

        // コメントユーザー情報 & 内容
        $response->assertSee('たろう');
        $response->assertSee('美味しそうですね！');
        $response->assertSee('はなこ');
        $response->assertSee('買いたいです！');
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されているか()
    {
        $user = \App\Models\User::factory()->create();

        // カテゴリ2つ作成
        $category1 = \App\Models\Category::factory()->create(['name' => '果物']);
        $category2 = \App\Models\Category::factory()->create(['name' => '季節限定']);

        // 商品作成
        $product = \App\Models\Product::factory()->create(['user_id' => $user->id]);

        // 商品にカテゴリを紐づけ（多対多）
        $product->categories()->attach([$category1->id, $category2->id]);

        // 詳細ページアクセス
        $response = $this->get(route('product.show', ['item_id' => $product->id]));

        // 両方のカテゴリ名が表示されていることを確認
        $response->assertSee('果物');
        $response->assertSee('季節限定');
    }
}
