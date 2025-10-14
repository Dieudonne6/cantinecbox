@extends('layouts.master')

@section('content')
<div class="col-12">
    <div class="container-fluid" style="background-color: #b3e6ff; padding: 20px; font-family: 'Arial', sans-serif;">

        <!-- Titre principal avec fond vert clair et bordure rouge -->
        <div style="background-color: #90ee90; border: 3px solid #cc0000; padding: 10px; margin-bottom: 20px; text-align: center; font-weight: bold; font-size: 18px; color: #cc0000;">
            Paramétrage pour la confection automatique des emploi du temps
        </div>

        <!-- Liste des 12 étapes -->
        <div style="display: flex; flex-direction: column; gap: 10px; max-width: 600px; margin: 0 auto;">

            <!-- Étape 1 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                1. Enregistrer les classes
            </button>

            <!-- Étape 2 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                2. Enregistrer les matières, préciser les couleurs et les noms courts
            </button>

            <!-- Étape 3 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                3. Mettre à jour la table des salles disponibles
            </button>

            <!-- Étape 4 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                4. Enregistrer les quotas horaires par classe et par matière
            </button>

            <!-- Étape 5 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                5. Enregistrer les professeurs et préciser les matières enseignées
            </button>

            <!-- Étape 6 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                6. Préciser la disponibilité de chaque professeur
            </button>

            <!-- Étape 7 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                7. Configurer les classes volantes et leurs matières fixes
            </button>

            <!-- Étape 8 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                8. Attribuer les classes aux professeurs
            </button>

            <!-- Étape 9 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                9. Préciser les plages horaires interdites par matière
            </button>

            <!-- Étape 10 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                10. Préciser les heures de démarrage et de fin de cours par promotion et les plages horaires matinée et soirée
            </button>

            <!-- Étape 11 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                11. Préciser le code de la matière EPS et le nombre de terrains de sports
            </button>

            <!-- Étape 12 -->
            <button style="background-color: #e6ffe6; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 14px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
                12. Quelles sont les classes à afficher sur l'emploi du temps ?
            </button>

            <!-- Bouton Final (violet) -->
            <button style="background-color: #dd99ff; border: 2px solid #cc0000; padding: 10px; text-align: center; font-weight: bold; font-size: 16px; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); margin-top: 20px;">
                Fermer et lancer l'écran panoramique
            </button>

        </div>

        <!-- Message final en bas -->
        <div style="background-color: #90ee90; border: 3px solid #cc0000; padding: 10px; margin-top: 30px; text-align: center; font-weight: bold; font-size: 16px; color: #cc0000;">
            Cliquer de 1 à 12 si nécessaire et configurer
        </div>

    </div>
</div>
@endsection