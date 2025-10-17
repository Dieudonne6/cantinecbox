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
        $rules = [];
        if ($this->routeIs('savetypeclasse')) {
            $rules = [
            'LibelleType' =>'required',
            'TYPECLASSE' =>'required',
            ];
        }

        if ($this->routeIs('saveserie')){
            $rules = [
                'SERIE' =>'required',
                'LIBELSERIE' =>'required',
            ];
        }

        if($this->routeIs('promotions.store')){
            $rules = [
                'codePromotion' =>'required',
                'libellePromotion' =>'required',
                'Niveau' =>'required',
            ];
        } 

        if ($this->routeIs('enregistrerclasse')) {
            $rules = [
            'nomclasse' =>'required',
            'libclasse' =>'required',
            'typclasse' =>'required',
            'typeensei' =>'required',
            'typepromo' =>'required',
            'numero' =>'required',
            'cycle' =>'nullable',
            'typeserie' =>'nullable',
            'typecours' =>'required',
            ];
        }
        if ($this->routeIs('nouveaueleve')) {
            $rules = [
            'classe' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nom' => 'required',
            'prenom' => 'required',
            'aptituteSport' => 'nullable',
            ];
        }
        return $rules;
    }

    public function messages() {
        return[

            // 'classe.required' => 'La classe de l\'élève est obligatoire.',
            'classe.required' => 'Le classe est obligatoire.',
            'classeEntre.required' => 'La classe d\'entree college est obligatoire.',
            // 'photo.required' => 'La photo est obligatoire',
            'photo.mimes' => 'La photo doit etre de type: jpeg, png, jpg, gif.',
            'photo.max' => 'La taille de la photo ne doit pas depasser 2 Mo',
            'photo.image' => 'Le fichier doit etre une image',
            'nom.required' => 'Le nom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
            'prenom.required' => 'Le prenom de l\'eleve est obligatoire.',
            'LibelleType.required' => 'Libellé groupe est obligatoire',
            'TYPECLASSE' => 'Code groupe est obligatoire',
            'nomclasse' =>'Nom classe est obligatoire',
            'libclasse' =>'Libelle est obligatoire',
            'typclasse' =>'Type Classe est obligatoire',
            'typeensei' =>'Enseignement est obligatoire',
            'typepromo' =>'Promotion est obligatoire',
            'numero' =>'No d\'ordre est obligatoire',
            'cycle' =>'Cycle est obligatoire',
            'typeserie' =>'Serie est obligatoire',
            'typecours' =>'Cours Jour/Soir est obligatoire',
            'SERIE' => 'Serie est obligatoire',
            'LIBELSERIE' =>'Libelle serie est obligatoire',
            'codePromotion.required' => 'Code Promotion est obligatoire',
            'libellePromotion.required' => 'Libelle Promotion est obligatoire',
            'Niveau.required' => 'Niveau hierachie est obligatoire',
        ];
    }
}