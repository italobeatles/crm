<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OpportunityRequest extends FormRequest
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
            'id_cliente' => ['required', 'exists:tbcustomers,id'],
            'id_lead' => ['nullable', 'exists:tbleads,id'],
            'titulo' => ['required', 'string', 'max:150'],
            'valor' => ['required', 'numeric', 'min:0'],
            'etapa' => ['required', 'string', 'max:30'],
            'probabilidade' => ['required', 'integer', 'min:0', 'max:100'],
            'data_prevista_fechamento' => ['nullable', 'date'],
            'status' => ['required', 'in:aberta,ganha,perdida'],
            'observacoes' => ['nullable', 'string'],
            'id_usuario_responsavel' => ['required', 'exists:tbusers,id'],
        ];
    }
}
