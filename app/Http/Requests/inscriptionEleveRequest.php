<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class inscriptionEleveRequest extends FormRequest
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
         'classe' => 'required',
         'classeEntre' => 'required',
         'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
         'reduction' => 'required',
         'nom' => 'required',
         'prenom' => 'required',
         'aptituteSport' => 'nullable',
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'classe.required' => 'Le classe est obligatoire.',
            'classeEntre.required' => 'La classe d\'entree college est obligatoire.',
            'photo.required' => 'La photo est obligatoire',
            'photo.mimes' => 'La photo doit etre de type: jpeg, png, jpg, gif.',
            'photo.max' => 'La taille de la photo ne doit pas depasser 2 Mo',
            'photo.image' => 'Le fichier doit etre une image',
            'nom.required' => 'Le nom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
        ];
    }
}
