<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'zip' => ['nullable', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'zip.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像サイズは2MB以内にしてください。',

        ];
    }
}
