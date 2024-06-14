@extends('layouts.master')
@section('content')
    <div class="container">

        <form action="{{ url('/traitementetatpaiement') }}" method="POST">
            @csrf
            <div class="form-group row">
                <div class="col">
                    <label for="debut">Du</label>
                    <input name="debut" id="debut" type="date" class="typeaheads" required>
                </div>
                <div class="col">
                    <label for="fin">Au</label>
                    <input name="fin" id="fin" type="date" class="typeaheads" required>
                </div>

                <div class="col">
                    <!-- Bouton de soumission de formulaire -->
                    <label for="debut" style="visibility: hidden">supprimer paiement</label>
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
                {{-- <div class="col">
                        <label for="debut" style="visibility: hidden">supprimer paiememtn</label>
                        <button type="button" class="btn btn-danger w-200" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Supp paiement
                        </button>
                    </div> --}}
                <div class="col">
                    <label for="debut" style="visibility: hidden">Du</label>
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Imprimer Etat
                    </button>
                </div>
                {{-- <div class="col">
                    <label for="debut" style="visibility: hidden">Du</label>
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Imprimer fiche
                    </button>
                </div> --}}
            </div>
        </form>

        @if (Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
                <p>{{ Session::get('status') }}</p>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Classe
                        </th>
                        <th>
                            Eleve
                        </th>
                        <th>
                            Montant
                        </th>
                        <th>
                            Mois payé(s)
                        </th>
                        <th>
                            Date
                        </th>
                        <th>
                            Reference
                        </th>
                        
                        {{-- <th>
                            Caissier
                        </th> --}}
                        <th>
                            Action a effectuee
                        </th>

                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($resultatsIndividuels as $resultatsIndividuel)
                    <tr>

                        <td>
                            {{$resultatsIndividuel->date_paiementcontrat}}
                        </td>

                        <td>
                            {{$resultatsIndividuel->reference_paiementcontrat}}
                        </td>

                        <td>
                            {{$resultatsIndividuel->montant_paiementcontrat}}
                        </td>

                        <td style="width: 20px;">
                            {{$resultatsIndividuel->mois_paiementcontrat}}
                        </td>

                        <td>
                            {{$resultatsIndividuel->date_paiementcontrat}}
                        </td>

                        <td>
                            {{$resultatsIndividuel->date_paiementcontrat}}
                        </td>

                        <td>
                            {{$resultatsIndividuel->date_paiementcontrat}}
                        </td>

                        <td>

                            <form action="{{ url('supprimercontrat/')}}" method="post">
                                @csrf
                                @method('DELETE')
                                <a href='/admin/deletecashier/' class='btn btn-danger w-50'>Suspendre</a>
                                <input type="submit" class="btn btn-danger w-100" value="Supprimer" >
                            </form>                         
                        </td>
                    </tr>

                    @endforeach --}}
                </tbody>
            </table>
        </div>

    </div>
@endsection

<!-- Assurez-vous d'inclure jQuery avant d'utiliser les méthodes AJAX -->

{{-- <script>
document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector('#formulaire');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le formulaire de se soumettre normalement

        var debut = document.querySelector('#debut').value;
        var fin = document.querySelector('#fin').value;

                // Fonction pour formater les dates au format yyyy-mm-dd
                function formatDate(dateString) {
        var parts = dateString.split("-");
        return parts[0] + '-' + parts[1] + '-' + parts[2];
    }

        // Convertir les dates au format yyyy-mm-dd
        var debutFormatted = formatDate(debut);
        var finFormatted = formatDate(fin);

        console.log(finFormatted);

        // Envoie des données via AJAX
        $.ajax({
            type: 'POST',
            url: '{{ route('traitementetatpaiement') }}',
            data: {
                debut: debutFormatted,
                fin: finFormatted,
                _token: '{{ csrf_token() }}' // CSRF token Laravel
            },
success: function(data) {
    // Traitement des données de réponse
    console.log(data);

    var tbody = document.getElementById('eleve-details');
    tbody.innerHTML = ''; // Effacer le contenu actuel du tableau

    // Itérer sur chaque objet dans le tableau
    data.forEach(function(item) {
        var tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.date_paiementcontrat}</td>
            <td>${item.reference_paiementcontrat}</td>
            <td>${item.montant_paiementcontrat}</td>
            <td>${item.mois_paiementcontrat}</td>
            <!-- Insérer les autres colonnes ici -->
        `;
        tbody.appendChild(tr);
    });
},
            error: function(xhr, status, error) {
                // Gestion des erreurs
                console.error(error);
            }
        });
    });


});
</script> --}}
