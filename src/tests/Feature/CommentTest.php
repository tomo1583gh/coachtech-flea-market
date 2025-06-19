<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function ログイン済みユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // コメント送信（POST）
        $response = $this->actingAs($user)->post(route('comment.store', ['item_id' => $product->id]), [
            'comment' => 'これはテストコメントです。',
        ]);

        // コメントがDBに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'body' => 'これはテストコメントです。',
        ]);

        // 商品を最新状態で取得（コメントとユーザー情報が最新になる）
        $product = $product->fresh('comments.user');

        // 詳細ページにアクセス
        $response = $this->actingAs($user)->get(route('product.show', ['item_id' => $product->id]));

        file_put_contents(storage_path('logs/test_output.html'), $response->getContent());

        // コメント本文と投稿者名が表示されていることを確認
        $response->assertSee('これはテストコメントです。');
        $response->assertSee($user->name);
    }
}
