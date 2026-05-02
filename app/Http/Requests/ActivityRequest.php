<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
        return [
            'relacionado_tipo' => ['nullable', 'in:lead,customer,opportunity'],
            'relacionado_id' => ['nullable', 'integer'],
            'tipo' => ['required', 'string', 'max:30'],
            'titulo' => ['required', 'string', 'max:150'],
            'descricao' => ['nullable', 'string'],
            'data_vencimento' => ['nullable', 'date'],
            'status' => ['required', 'in:pendente,concluida,cancelada'],
            'id_usuario_responsavel' => ['required', 'exists:tbusers,id'],
        ];
    }
}
