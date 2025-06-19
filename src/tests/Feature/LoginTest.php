<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function メールアドレスが未入力の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login', [
            // メールアドレスは入力しない
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $this->assertEquals(
            'メールアドレス を入力して下さい。',
            session('errors')->first('email')
        );

        $response->assertRedirect('/login');
    }

    /** @test */
    public function パスワードが未入力の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => '', // パスワードは入力しない
        ]);

        // エラーがある場合、元の画面に戻る
        $response->assertRedirect('/login');

        $response->assertRedirect('/login');
        $this->assertEquals(
            'パスワード を入力して下さい。',
            session('errors')->first('password')
        );
    }

    /** @test */
    public function 入力情報が間違っている場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        // ログインページにリダイレクトされること
        $response->assertRedirect('/login');

        $response->assertRedirect('/login');
        $this->assertEquals(
            'ログイン情報が登録されていません。',
            session('errors')->first('email')
        );
    }

    /** @test */
    public function 正しい情報が入力された場合にログイン処理が実行される()
    {
        // 事前にユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // ハッシュ化されたパスワード
            'email_verified_at' => now(), // Fortifyでログインにはメール認証が必要なため
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/redirect-after-login');

        // 認証されているか確認
        $this->assertAuthenticatedAs($user);
    }
}
