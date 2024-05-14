@extends('layouts.master')
@section('content')
<style>
    /* Masquer la colonne lors de l'impression */
    @media print {
        .hide-on-print {
            /* display: none !important; */
            visibility: hidden !important; 
        }
    
    }
</style>
    <div class="container">

        <form action="{{ url('/traitementetatpaiement') }}" method="POST">
            @csrf
            <div class="form-group row">
                <div class="col">
                    <label for="debut">Du</label>
                    <input name="debut" id="debut" type="date" class="typeaheads">
                </div>
                <div class="col">
                    <label for="fin">Au</label>
                    <input name="fin" id="fin" type="date" class="typeaheads">
                </div>

                <div class="col">
                    <!-- Bouton de soumission de formulaire -->
                    <label for="debut" style="visibility: hidden">supprimer paiememtn</label>
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
                    <button onclick="imprimerPage()" type="button" class="btn btn-primary w-100">
                        Imprimer Etat
                    </button>
                </div>
                {{-- <div class="col">
                        <label for="debut" style="visibility: hidden">Du</label>
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Imprimer fiche
                        </button>
                    </div> --}}
            </div>
        </form>

        {{-- @if (Session::has('status'))
    <div id="statusAlert" class="alert alert-succes btn-primary">
    {{ Session::get('status')}}
    </div>
    @endif --}}

        @if (Session::has('statuspaiement'))
            <div class="alert alert-success">
                {{ Session::get('statuspaiement') }}
            </div>
        @endif
        {{-- @elseif (Session::has('statuspaiement'))
    @if (Session::has('statuspaiement'))
    <div id="statusAlert" class="alert alert-succes btn-primary">
        {{ Session::get('statuspaiement')}}
        {{ Session::get('statuspaiement')}}
    </div>
    @endif
    @else

    <div id="statusAlert" class="alert alert-succes btn-primary">
        {{ Session::get('statuspaiement')}}
        <p>lolllllllllllllllllllllll</p>
    </div>
    @endif --}}

    <div id="contenu">

    <div>
        <h5 class="card-title" style="text-align: center;">Liste des paiements de la periode du {{$dateFormateedebut}} au {{$dateFormateefin}} </h5>
    </div><br>
        <div class="table-responsive">
            <table id="myTable" class="table">
                <thead>
                    <tr>

                        <th>
                            Date
                        </th>
                        <th>
                            Reference
                        </th>
                        <th>
                            Montant
                        </th>
                        <th>
                            Mois paye(s)
                        </th>
                        <th>
                            Eleve
                        </th>
                        <th>
                            Classe
                        </th>
                        <th>
                            Caissier
                        </th>
                        <th class="hide-on-print">
                            Action a effectuee
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($paiementsAvecEleves as $resultatsIndividuel)
                        <tr>

                            <td>
                                {{ $resultatsIndividuel['date_paiement'] }}
                            </td>

                            <td>
                                {{ $resultatsIndividuel['reference'] }}
                            </td>

                            <td>
                                {{ $resultatsIndividuel['montant'] }}
                            </td>

                            <td style="width: 20px;">
                                {{ $resultatsIndividuel['mois'] }}
                            </td>

                            <td>
                                {{ $resultatsIndividuel['nomcomplet_eleve'] }}
                            </td>

                            <td>
                                {{ $resultatsIndividuel['classe_eleve'] }}
                            </td>

                            <td>
                                {{ $resultatsIndividuel['user'] }}
                            </td>

                            <td class="hide-on-print">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-primary w-50 me-1" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        Imprimer fiche
                                    </button>
                                    <form
                                        action="{{ url('supprimerpaiement/' . $resultatsIndividuel['id_paiementcontrat']) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-danger w-100" value="Supprimer">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


        {{-- @endif --}}
    </div>
@endsection

<script>
    
    function imprimerPage() {
        // Supprimer l'ID myTable avant l'impression
        // document.getElementById('myTable').removeAttribute('id');
        var table = document.getElementById('myTable');
        table.classList.remove('DataTable');
        var page = window.open();
        page.document.write('<html><head><title>Imprimer</title>');
        page.document.write('<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />');

        page.document.write('<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >');
        page.document.write('<style>@media print { .hide-on-print { visibility: hidden !important; } }</style>');

        page.document.write('</head><body>');
        // page.document.write(document.getElementById('contenu').innerHTML);
        // var contenu = document.getElementById('contenu').innerHTML;
        // var table = document.getElementById('myTable').innerHTML;
        // table = table.replace('id="myTable"', '');
        // console.log(table);
        // page.document.write(contenu);

        page.document.write(document.querySelector('.dt-layout-table').innerHTML);
            page.document.write(document.getElementById('contenu').innerHTML);

        page.document.write('</body></html>');
        page.document.close();
        page.print();
    }
    
  </script>

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
