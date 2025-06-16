<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::first();

        $products = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'storage/products/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'category_names' => ['ファッション', 'メンズ'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'storage/products/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'category_names' => ['家電'],
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束セット',
                'image_path' => 'storage/products/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'category_names' => ['食品'],
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'storage/products/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'category_names' => ['ファッション', 'メンズ'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image_path' => 'storage/products/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'category_names' => ['家電'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'storage/products/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'category_names' => ['家電'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'storage/products/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'category_names' => ['ファッション', 'レディース'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image_path' => 'storage/products/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'category_names' => ['キッチン'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image_path' => 'storage/products/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'category_names' => ['キッチン'],
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image_path' => 'storage/products/外出メイクアップセット.jpg',
                'condition' => '目立った傷や汚れなし',
                'category_names' => ['コスメ', 'レディース'],
            ],
        ];

        foreach ($products as $data) {
            $categoryNames = $data['category_names'];
            unset($data['category_names']);

            $product = \App\Models\Product::create($data);

            // カテゴリ名からIDを取得して紐づけ
            $categoryIds = Category::whereIn('name', $categoryNames)->pluck('id')->toArray();
            $product->categories()->sync($categoryIds);
        }
    }
}