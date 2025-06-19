<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function ログアウトができる()
    {
        // ログイン済みのユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'logout@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ログイン状態にする
        $this->actingAs($user);

        // ログアウト処理を実行（POSTメソッド）
        $response = $this->post('/logout');

        // トップページにリダイレクトされることを確認
        $response->assertRedirect('/');

        // 認証されていない状態になっていることを確認
        $this->assertGuest();
    }
}
