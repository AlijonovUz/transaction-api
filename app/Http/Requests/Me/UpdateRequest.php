<?php

namespace App\Http\Requests\Me;

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

        $authUser = auth()->user();
        $targetUser = $this->route('user');

        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email',
                Rule::unique('users', 'email')
                    ->ignore(($targetUser ?? $authUser)->id)
            ],
        ];

        if ($authUser?->isAdmin() && $targetUser && $authUser->id !== $targetUser->id) {
            $rules['is_active'] = ['sometimes', 'boolean'];
            $rules['role'] = ['sometimes', 'in:user,admin'];
        }

        return $rules;
    }
}
