<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConnexionBDRequest extends FormRequest
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
            'nameserveur' => 'required',
            'namebase' => 'required', 
            'user' => 'required',
           
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'nameserveur.required' => 'Precisez le nom du serveur.',
            'namebase.required' => 'Le nom de la base de donne est obligatoire.',
            'user.required' => 'L\'utilisateur est obligatoire.',
        ];
    }
}
