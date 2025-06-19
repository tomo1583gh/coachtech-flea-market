<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function 名前が未入力の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/register')->post('/register', [
            // 'name' を未入力にする
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $this->assertEquals(
            'お名前を入力してください',
            session('errors')->first('name')
        );
    }

    /** @test */
    public function メールアドレスが未入力の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            // 'email' を未入力にする
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $this->assertEquals(
            'メールアドレスを入力してください',
            session('errors')->first('email')
        );
    }

    /** @test */
    public function パスワードが未入力の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            // 'password' を未入力にする
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $this->assertEquals(
            'パスワードを入力してください',
            session('errors')->first('password')
        );
    }

    /** @test */
    public function パスワードが7文字以下の場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass123', // 7文字
            'password_confirmation' => 'pass123',
        ]);

        $response->assertRedirect('/register');
        $this->assertEquals(
            'パスワードは8文字以上で入力してください',
            session('errors')->first('password')
        );
    }

    /** @test */
    public function パスワードが確認用パスワードと一致しない場合にバリデーションエラーメッセージが表示される()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123', // 確認用が異なる
        ]);

        $response->assertRedirect('/register');
        $this->assertEquals(
            'パスワードと一致しません',
            session('errors')->first('password')
        );
    }

    /** @test */
    public function 全ての項目が入力されている場合に登録されてログイン画面にリダイレクトされる()
    {
        // テスト実行前にセッションリセット
        $this->withoutExceptionHandling();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ユーザーがDBに登録されているか確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/redirect-after-login');
    }
}
