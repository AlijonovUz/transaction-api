<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'integer',
                Rule::exists('categories', 'id')
                    ->where('user_id', auth()->id())
            ],
            'amount' => ['sometimes', 'integer'],
            'type' => ['sometimes', 'in:income,expense'],
            'note' => ['sometimes', 'string'],
            'transaction_date' => ['sometimes', 'date', 'before_or_equal:today']
        ];
    }
}
