<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'condition' => 'required|string',
            'categories' => 'required|array|min1',
            'categories.*' => 'exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は整数で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'categories.required' => 'カテゴリを1つ以上選択してください',
            'categories.*.exists' => '無効なカテゴリが含まれています',
            'image.required' => '商品画像を選択してください',
            'image.image' => '画像ファイルをアップロードしてください',
            'image.mimes' => '画像はjpeg, png, jpg, gif形式のみ対応しています',
            'image.max' => '画像サイズは2MB以内にしてください',
        ];
    }
}
