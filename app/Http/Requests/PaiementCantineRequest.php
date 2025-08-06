<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementCantineRequest extends FormRequest
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
            'moiscontrat' => 'required|array',
            'moiscontrat.*' => 'required', // Si l'ID est une chaîne de caractères
            'date' => 'required',
            'montantcontrat' => 'required',
            // 'moiscontrat[]' => 'accepted',
            'montanttotal' => 'required',
            // Ajoutez d'autres règles selon vos besoins
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'moiscontrat.required' => 'Vous devez sélectionner au moins un mois.',
            'montantcontrat.required' => 'Le montant a payer est obligatoire.',
            'date.required' => 'La date est obligatoire.',
            'montanttotal.required' => 'Le montant total est obligatoire.',
        ];
    }
}
