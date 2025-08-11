<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'login' => 'required|string',
            'nom' => 'required', 
            'prenom' => 'required',
            'password' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Ajoutez d'autres règles selon vos besoins
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'login.required' => 'Le nom d\'utilisateur est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prenom est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'image.required' => 'La photo est obligatoire',
            'image.mimes' => 'La photo doit etre de type: jpeg, png, jpg, gif.',
            'image.max' => 'La taille de la photo ne doit pas depasser 2 Mo',
            'image.image' => 'Le fichier doit etre une image',
        ];
    }
}
