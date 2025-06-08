<?php

namespace App\Http\Requests;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Foundation\Http\FormRequest;

class PuchaseRequest extends FormRequest
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
    public function rules()
    {
        return [
            'Payment_method' => 'required|in:convenience,card',
        ];
    }

    public function messages()
    {
        return [
            'Payment_method.required' => '支払い方法を選択してください。',
            'Patment_method.in' => '選択された支払い方法が正しくありません。',
        ];
    }
}
