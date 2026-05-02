<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tbusers', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,manager,sales,support'],
            'status' => ['required', 'boolean'],
            'password' => [Rule::requiredIf($this->isMethod('post')), 'nullable', 'confirmed', 'min:8'],
        ];
    }
}
