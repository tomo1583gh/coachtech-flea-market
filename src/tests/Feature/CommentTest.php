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

        // コメント本文と投稿者名が表示されていることを確認
        $response->assertSee('これはテストコメントです。');
        $response->assertSee($user->name);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        // 商品を用意（ログインなし）
        $product = Product::factory()->create();

        // コメントをPOST（未ログイン）
        $response = $this->post(route('comment.store', ['item_id' => $product->id]), [
            'comment' => 'ゲストによるテストコメント',
        ]);

        // 未ログインなので、ログイン画面にリダイレクトされること
        $response->assertRedirect(route('login'));

        // コメントが保存されていないこと
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'body' => 'ゲストによるテストコメント',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        // ユーザーと商品を作成
        $user = \App\Models\User::factory()->create();
        $product = \App\Models\Product::factory()->create();

        // コメントを空で送信
        $response = $this->actingAs($user)->post(route('comment.store', ['item_id' => $product->id]), [
            'comment' => '',
        ]);

        // バリデーションエラーがセッションに含まれていること
        $response->assertSessionHasErrors(['comment']);

        // 元の詳細ページにリダイレクトされていること
        $response->assertRedirect(route('product.show', ['item_id' => $product->id]));

        // DBにコメントが保存されていないこと
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = \App\Models\User::factory()->create();
        $product = \App\Models\Product::factory()->create();

        // 256文字のコメントを作成
        $longComment = str_repeat('あ', 256);

        // コメント送信（ログインユーザー）
        $response = $this->actingAs($user)->post(route('comment.store', ['item_id' => $product->id]), [
            'comment' => $longComment,
        ]);

        // バリデーションエラーがセッションに含まれていること
        $response->assertSessionHasErrors(['comment']);

        // 元の詳細ページにリダイレクトされていること
        $response->assertRedirect(route('product.show', ['item_id' => $product->id]));

        // DBに保存されていないこと
        $this->assertDatabaseMissing('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'body' => $longComment,
        ]);
    }
}
