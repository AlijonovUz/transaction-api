<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        'category_id' => ['required', 'integer',
            Rule::exists('categories', 'id')
                ->where('user_id', auth()->id())
        ],
        'amount' => ['required', 'integer'],
        'type' => ['required', 'in:income,expense'],
        'note' => ['required', 'string'],
        'transaction_date' => ['required', 'date', 'before_or_equal:today']
    ];
    }
}
