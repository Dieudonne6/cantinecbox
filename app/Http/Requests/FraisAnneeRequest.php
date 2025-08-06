<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FraisAnneeRequest extends FormRequest
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
            'anneencours_paramcontrat' => 'required',
            'fraisinscription_paramcontrat' => 'required', 
            'fraisinscription_mat' => 'required',
            'fraisinscription2_paramcontrat' => 'required',
            'coutmensuel_paramcontrat' => 'required',
            // Ajoutez d'autres règles selon vos besoins
        ];
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'anneencours_paramcontrat.required' => 'L\'année académique est obligatoire.',
            'fraisinscription_paramcontrat.required' => 'Les Frais d\'inscriptions du Primaire sont obligatoire.',
            'fraisinscription_mat.required' => 'Les Frais d\'inscriptions du Maternel sont obligatoire.',
            'fraisinscription2_paramcontrat.required' => 'Les Frais mensuel du Maternel sont obligatoire.',
            'coutmensuel_paramcontrat.required' => 'Les Frais mensuel du Primaire sont obligatoire.',
        ];
    }
}
