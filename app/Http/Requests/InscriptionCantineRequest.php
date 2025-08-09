<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscriptionCantineRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'classe' => 'required',
            'matricules' => 'required',
            'montant' => 'required',
            'date' => 'required',
            // Ajoutez d'autres règles selon vos besoins
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'matricules.required' => 'Le nom de l\'élève est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'date.required' => 'La date est obligatoire.',
        ];
    }
}
