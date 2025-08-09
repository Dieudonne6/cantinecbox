<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactureRequest extends FormRequest
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
            'ifu' => 'required',
            'token' => 'required', 
            'taxe' => 'required',
            'type' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Ajoutez d'autres règles selon vos besoins
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'ifu.required' => 'Le numero IFU est obligatoire.',
            'token.required' => 'Le token est obligatoire.',
            'taxe.required' => 'Le Groupe de la taxe est obligatoire.',
            'type.required' => 'Le Type de la facture est obligatoire.',
            'logo.required' => 'Le logo est obligatoire',
            'logo.mimes' => 'Le logo doit etre de type: jpeg, png, jpg, gif.',
            'logo.max' => 'La taille du logo ne doit pas depasser 2 Mo',
            'logo.image' => 'Le fichier doit etre une image',
        ];
    }
}
