<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;


class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が取得できる()
    {
        Storage::fake('public');

        // ユーザー作成とダミー画像ファイル
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $file = UploadedFile::fake()->create('dummy.txt', 10); // 画像以外のダミーファイル

        // ユーザーにプロフィール画像を設定（Storageへ保存）
        $path = $file->store('profile_images', 'public');
        $user->image_path = $path;
        $user->save();

        // 出品商品
        $product1 = Product::factory()->create([
            'user_id' => $user->id,
            'is_sold' => false,
        ]);

        // 購入商品
        $product2 = Product::factory()->create([
            'is_sold' => true,
            'buyer_id' => $user->id,
        ]);

        // ログインしてプロフィールページへアクセス
        $this->actingAs($user);
        $response = $this->get('/mypage');

        // 表示確認
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($product1->name); // 出品商品名
        $response->assertSee($product2->name); // 購入商品名
        $response->assertSee($user->image_path); // パス文字列が表示に含まれているか
    }

    /** @test */
    public function 変更項目が初期値として過去設定されていること()
    {
        Storage::fake('public');

        // ダミーユーザー作成
        $user = \App\Models\User::factory()->create([
            'name' => '山田 太郎',
            'zip' => '123-4567',
            'address' => '東京都港区芝公園',
            'building' => '芝タワー',
            'image_path' => 'profile_images/dummy.jpg',
            'email_verified_at' => now(),
        ]);

        // ログイン状態でプロフィール編集ページへアクセス
        $this->actingAs($user);
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('山田 太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都港区芝公園');
        $response->assertSee('芝タワー');
        $response->assertSee('profile_images/dummy.jpg');
    }
}
