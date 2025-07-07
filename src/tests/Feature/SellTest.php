<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;


class SellTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 商品出荷画面にて必要な情報が保存出来ること()
    {
        Storage::fake('public');

        // ユーザー作成 & ログイン
        $user = \App\Models\User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        // 入力情報（複数カテゴリ対応・状態は整数IDを想定）
        $formData = [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'price' => 3000,
            'state' => '2', // 商品の状態
            'category_ids' => ['ファッション', 'メンズ'], // カテゴリ（中間テーブル用）
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        // POST送信
        $response = $this->post('/sell', $formData);
        $response->dump();

        // DB保存確認
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'price' => 3000,
            'state' => 2,
            'user_id' => $user->id,
        ]);

        // リダイレクト先の確認
        $response->assertRedirect('/');
    }
}
