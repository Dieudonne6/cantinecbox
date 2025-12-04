@extends('layouts.master')
@section('content')
<style>
  .form-control{
    padding: 0.875rem 1rem;
    background-color: #e9ecef57 !important;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div>
          <style>
              .btn-arrow {
                  position: absolute;
                  top: 0px;
                  /* Ajustez la position verticale */
                  left: 0px;
                  /* Positionnez à gauche */
                  background-color: transparent !important;
                  border: 1px !important;
                  text-transform: uppercase !important;
                  font-weight: bold !important;
                  cursor: pointer !important;
                  font-size: 17px !important;
                  /* Taille de l'icône */
                  color: #b51818 !important;
                  /* Couleur de l'icône */
              }
      
              .btn-arrow:hover {
                  color: #b700ff !important;
                  /* Couleur au survol */
              }
        
               table {
                  width: 100%;
                  border-collapse: separate;
                  border-spacing: 0 10px; /* <-- espace entre les lignes */
                  font-family: "Segoe UI", sans-serif;
                  font-size: 14px;
                }

                thead {
                  background: linear-gradient(90deg, #007bff, #00bcd4);
                  color: white;
                }

                th {
                  text-align: center;
                  padding: 10px;
                  border: none;
                  border-radius: 5px 5px 0 0;
                }

                tbody tr {
                  background: #ffffff;
                  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
                  border-radius: 10px;
                  transition: all 0.2s ease;
                }

                tbody tr:hover {
                  transform: scale(1.01);
                  box-shadow: 0 3px 8px rgba(0,0,0,0.15);
                  background: #f9fcff;
                }

                td {
                  text-align: center;
                  padding: 12px 8px;
                  border: none;
                }

                td:nth-child(3) {
                  font-weight: 600;
                }

                tbody tr td:first-child {
                  border-radius: 10px 0 0 10px;
                }

                tbody tr td:last-child {
                  border-radius: 0 10px 10px 0;
                }
          </style>
          <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
              <i class="fas fa-arrow-left"></i> Retour
          </button>  
          <br>
          <br>                                    
      </div>
        <div class="card-body">
          <h4 class="card-title">Mise a jour echeancier eleve</h4>
          @if (Session::has('status'))
          <div id="statusAlert" class="alert alert-success btn-primary">
            {{ Session::get('status') }}
          </div>
          @endif
          <input type="hidden" value="{{ $annescolaire }}" id="annescolaire">
          <form action="{{ url('modifieecheancier/'.$eleve->MATRICULE) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-4" style="">
                <div class="card">
                  <div class="card-body p-2">
                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputUsername1" class="mr-2">Matricule</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" value="{{ $eleve->MATRICULEX }}" readonly>
                    </div>

                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputEmail1" class="mr-2">Nom</label>
                      <input type="text" class="form-control" id="noms" value="{{ $eleve->NOM }}" readonly>
                    </div>

                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputPassword1" class="mr-2">Prenom</label>
                      <input type="text" class="form-control" id="prenoms" value="{{ $eleve->PRENOM }}" readonly>
                    </div>

                    {{-- <div class="form-group d-flex align-items-center">
                      <label for="exampleInputPassword1" class="mr-2">Classe</label>
                      <input type="text" class="form-control" id="prenoms" value="{{ $eleve->CODECLAS }}" readonly>
                    </div> --}}

                    <div class="form-group d-flex align-items-center">
                      <label for="exampleInputPassword1" class="mr-2">Type Eleve</label>
                      <input type="text" class="form-control" id="prenoms" value="{{ $eleve->STATUTG == 2 ? 'Ancien' : 'Nouveau' }}" readonly>
                    </div>

                  </div>
                </div>
              </div>
              
              <div class="d-none">
                <div class="d-flex mb-2">
                  <label class="w-100">Scolarité</label>
                  <input type="" id="classesco" class="form-control" value="{{ $elev->STATUTG == 1 ? $elev->classe->APAYER : $elev->classe->APAYER2 }}" placeholder="" readonly>
                </div>
                
                <div class="mb-2">
                  <input type="" class="form-control" id="classe" placeholder="" name="classe" value="{{ $eleve->CODECLAS}}" readonly>
                </div>
                <div class="mb-2">
                  <select class="js-example-basic-multiple w-100" id="exampleSelectGender" name="type" readonly>
                    <option value="1" {{ $eleve->STATUTG == 1 ? 'selected' : '' }}>Nouveau</option>
                    <option value="2" {{ $eleve->STATUTG  == 2 ? 'selected' : '' }}>Ancien</option>
                    <option value="3" {{ $eleve->STATUTG  == 3 ? 'selected' : '' }}>Transferer</option>
                  </select>
                </div>
                <div class="col-lg-4">
                  <input id="arriereinitial" type="" class="form-control"  placeholder="" value="{{$eleve->ARRIERE_INITIAL}}" readonly>
                </div>
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF1 ?? '' }}</label>
                  <input type="text" id="fraisclasse1" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS1 ?? 0) : ($elev->classe->FRAIS1_A ?? 0) }}" readonly>
                </div>
                
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF2 ?? '' }}</label>
                  <input type="text" id="fraisclasse2" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS2 ?? 0) : ($elev->classe->FRAIS2_A ?? 0) }}" readonly>
                </div>
                <div class="mb-2 d-flex">
                  <label class="w-100">{{ $libel->LIBELF3 ?? '' }}</label>
                  <input type="text" id="fraisclasse3" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS3 ?? 0) : ($elev->classe->FRAIS3_A ?? 0) }}" readonly>
                </div>
                
                <div class="d-flex mb-2">
                  <label class="w-100">{{ $libel->LIBELF4 ?? '' }}</label>
                  <input type="text" id="fraisclasse4" class="form-control" 
                  value="{{ $elev->STATUTG == 1 ? ($elev->classe->FRAIS4 ?? 0) : ($elev->classe->FRAIS4_A ?? 0) }}" readonly>
                </div>
              </div>
              
              <div class="col-8">
                <div class="card">
                  <div class="card-body">
                    <div class="form-group row">
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">Scolarite</label>
                        <div id="the-basics">
                          <input class="form-control" id="apayer" name="sco" type="text"  value="{{ $eleve->APAYER }}" readonly>
                        </div>
                      </div>
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">Arriere</label>
                        <div id="bloodhound">
                          <input class="form-control" id="arriere" name="arriere" type="text" value="{{ $eleve->ARRIERE }}" readonly>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">{{$libel->LIBELF1}}</label>
                        <div id="the-basics">
                          <input class="form-control" type="text" id="frais1" name="frais1" value="{{ $eleve->FRAIS1 }}" readonly>
                          
                        </div>
                      </div>
                      <div class="col d-flex align-items-center">
                        <label class="mr-2"> {{$libel->LIBELF2}}</label>
                        <div id="bloodhound">
                          <input class="form-control" id="frais2" name="frais2" type="text"  value="{{ $eleve->FRAIS2}}" readonly>
                          
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">{{$libel->LIBELF3}}</label>
                        <div id="the-basics">
                          <input class="form-control" id="frais3" type="text" name="frais3" value="{{ $eleve->FRAIS3 }}" readonly>
                        </div>
                      </div>
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">{{$libel->LIBELF4}}</label>
                        <div id="bloodhound">
                          <input class="form-control" id="frais4" type="text" name="frais4" value="{{ $eleve->FRAIS4 }}" readonly>
                        </div>
                      </div>
                    </div>
                    
                    {{-- <div class="form-group row">
                      <label class="col-12">Modifier le profil de reduction</label>
                      <div class="col">
                        <select id="class-select" name="reduction" class="js-example-basic-multiple mb-3">
                          <option value="">Sélectionnez une réduction</option>
                          @foreach ($reduction as $reductions)
                          <option value="{{$reductions->CodeReduction}}" data-type="{{$reductions->typereduction}}" data-sco="{{$reductions->Reduction_scolarite}}" data-arrie="{{$reductions->Reduction_arriere}}"
                            data-frais1="{{$reductions->Reduction_frais1}}" data-frais2="{{$reductions->Reduction_frais2}}" data-frais3="{{$reductions->Reduction_frais3}}" data-frais4="{{$reductions->Reduction_frais4}}" data-fixesco="{{$reductions->Reduction_fixe_sco}}" data-fixefrais1="{{$reductions->Reduction_fixe_frais1}}" data-fixefrais2="{{$reductions->Reduction_fixe_frais2}}" data-fixefrais3="{{$reductions->Reduction_fixe_frais3}}" data-fixefrais4="{{$reductions->Reduction_fixe_frais4}}" 
                            data-fixearriere="{{$reductions->Reduction_fixe_arriere}}"  @if ($reductions->CodeReduction == $eleve->CodeReduction) selected @endif>
                            {{$reductions->LibelleReduction}}
                          </option>
                          @endforeach
                        </select>
                        
                      </div>
                      <!-- <div class="col">
                        <button type="submit" class="btn btn-primary mr-2">Valider</button>
                      </div> -->
                    </div> --}}
                  </div>
                </div>
              </div>
            </div>
          </br>
        </form>
        <div class="row">
          
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-5">
                    <div class="form-group row">
                      <label class="col-sm-5 col-form-label">Debut echeance</label>
                      <div class="col-sm-3">
                        <input type="date" class="form-control" id="dateDebut" value="{{$dateDebut}}"  style="width: 10rem"/>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-4">
                    <div class="form-group row">

                      <input type="number" id="typeEcheance" class="d-none" value="{{ $typeEcheance }}">

                      <label class="form-check-label d-none" for="flexRadioDefault2">
                          L'échéancier prend en compte tous les frais [<span id="totalFrais"></span>] et [<span
                              id="totalFraisAnciens"></span>]
                      </label>


                      <label class="col-sm-4 col-form-label">Periodicite</label>
                      <div class="col-sm-3">
                        <input type="number" id="periodicite" class="form-control" style="width: 3.9rem !important" value="{{$classis->PERIODICITE}}" />
                      </div>
                      <label class="col-sm-3 col-form-label">Mois</label>
                    </div>
                  </div>
                  
                  <div class="col-3">
                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Duree</label>
                      <div class="col-sm-3">
                        <input type="number" class="form-control"  id="nbEcheances" style="width: 3.9rem !important" value="{{$nbEcheance}}" />
                      </div>
                      <label class="col-sm-5 col-form-label">echeance</label>
                    </div>
                  </div>
                  {{-- <div class="col-2">
                    <div >
                      <input class="form-control" type="text" value="229" style="text-align: center; color:black;" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div >
                      <input class="form-control" type="text" placeholder="numero">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
          
        </div><br>
        <div class="row">
          <div class="col-8">
            <div class="card">
              <div class="card-body">
                {{-- <h5 style="text-align: center; color: rgb(188, 64, 64)">Scolarite</h5> --}}
                <div class="table-responsive pt-3">

                  {{-- <table class="table table-bordered"  id="paymentTable">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th class="d-none"></th>
                      </tr>
                    </thead>

                    <tbody>
                      @foreach ($modifiecheances as $index => $echeance)
                          @php
                            $totalApayer = $modifiecheances->sum(function ($echeance) {
                                return $echeance->APAYER + $echeance->ARRIERE;
                            });
                          @endphp
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ \Carbon\Carbon::parse($echeance->DATEOP)->format('d/m/Y') }} </td>
                             <td> {{ number_format($echeance->APAYER + $echeance->ARRIERE, 0, ',', ' ') }}</td>
                          </tr>
                      @endforeach
                    </tbody>

                    <tfoot>
                      <tr style="background:#f1f1f1;font-weight:600;">
                        <td colspan="2" style="text-align:center;">Total :</td>
                        <td>{{ number_format($totalApayer, 0, ',', ' ') }}</td>
                      </tr>
                    </tfoot>

                  </table> --}}

                  <table class="table table-striped" style="font-size: 10px;" id="echeancierNouveau"
                      data-initial='@json($modifiecheances)'>
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Date paie</th>
                              <th>Montant</th>
                          </tr>
                      </thead>
                      <tbody id="tbodyNouveaux">
                          <!-- Contenu dynamique -->
                      </tbody>
                      <tfoot>
                          <tr>
                              <th style="font-size: 15px">Total</th>
                              <th></th>
                              <th id="totalMontantNouveauEleve" style="font-size: 15px"></th>
                          </tr>
                      </tfoot>
                  </table>

                </div>
                <br>
              </div>
            </div>
          </div>


          <div class="table-responsive d-none" style="overflow: auto;">
              <table class="table table-striped" style="font-size: 10px;" id="echeancierTable"
                  data-initial='@json($modifiecheances)'>
                  <thead>
                      <tr>
                          <th class="text-center">Tranche</th>
                          <th class="text-center">% nouveau</th>
                          <th class="text-center">% ancien</th>
                          <th class="text-center">Montant Nouv</th>
                          <th class="text-center">Montant Anc</th>
                          <th class="text-center" style="display: none;">Date paie</th>
                      </tr>
                  </thead>
                  <tbody id="tableBody">
                      <!-- Contenu dynamique -->
                      {{-- @foreach ($donneEcheancc as $echeance)
                      <tr>
                          <td class="text-center">{{ $echeance->NUMERO }}</td>
                          <td class="text-center">{{ $echeance->FRACTION1 * 100 }}%</td>
                          <td class="text-center">{{ $echeance->FRACTION2 * 100 }}%</td>
                          <td class="text-center">{{ number_format($echeance->APAYER, 0, ',', ' ') }}</td>
                          <td class="text-center">{{ number_format($echeance->APAYER2, 0, ',', ' ') }}</td>
                          <td class="text-center" style="display: none;">{{ $echeance->DATEOP }}</td>
                      </tr>
                  @endforeach --}}

                  <tfoot>
                      <tr>
                          <th>Total</th>
                          <th id="totalPourcentageNouveau"></th>
                          <th id="totalPourcentageAncien"></th>
                          <th id="totalMontantNouveau"></th>
                          <th id="totalMontantAncien"></th>
                          {{-- <th id="datepaie" ></th>    --}}
                      </tr>
                  </tfoot>
              </table>
          </div>
          
          <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="alertModalLabel">Message</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <strong id="alertModalMessage"></strong>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-4" style="margin-top:2.5rem">
            <div class="row">
              <form id="validationForm" action="{{ url('regenererecheance/'.$eleve->MATRICULE) }}" method="post">
                @csrf
                <div class="col-5">
                  <input type="hidden" id="echeancesData" name="echeancesData">
                  
                  <button onclick="imprimerPage()" type="button" class="btn btn-primary" style=" width:8rem ;">
                    Imprimer
                  </button>
                  @if(Session::has('account') && Session::get('account')->groupe && strtoupper(Session::get('account')->groupe->nomgroupe) === 'ADMINISTRATEUR')
                    <button type="button" id="validerbtn" class="btn btn-primary" style="margin-top: 10px; width:8rem;">Regenerer</button> 
                  @endif
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // Sélection du bouton
    const btn = document.getElementById('validerbtn');
    if (!btn) return; // sécurité si le bouton n'est pas affiché pour un non-admin
    
    // Le bouton est caché au départ
    btn.style.display = 'none';

    // Champs à surveiller
    const fields = [
        document.getElementById('dateDebut'),
        document.getElementById('periodicite'),
        document.getElementById('nbEcheances'),
        document.getElementById('typeEcheance')
    ];

    // Ajouter événement sur chaque champ
    fields.forEach(field => {
        if (field) {
            field.addEventListener('input', function () {
                btn.style.display = 'inline-block';
            });
        }
    });

});
</script>


<script>

   // Fonction pour lancer l'impression
  function imprimerPage() {

      // Cloner le tableau pour éviter de modifier l'original
      var table = document.getElementById("echeancierNouveau").cloneNode(true);
      
      let noms = $('#noms').val();
      let prenoms = $('#prenoms').val();
      let apayer = parseFloat($('#apayer').val()) || 0;
      let frais1 = parseFloat($('#frais1').val()) || 0;
      let frais2 = parseFloat($('#frais2').val()) || 0;
      let frais3 = parseFloat($('#frais3').val()) || 0;
      let frais4 = parseFloat($('#frais4').val()) || 0;
      let arriere = parseFloat($('#arriere').val()) || 0;
      let annescolaire = $('#annescolaire').val();
      let classe = $('#classe').val();
      
      // Calcul du total
      let totalApayer = apayer + frais1 + frais2 + frais3 + frais4;

      // Formatage séparateur milliers (style français)
      let totalApayerFormatted = totalApayer.toLocaleString('fr-FR');
      let arriererFormatted = arriere.toLocaleString('fr-FR');


      // Ajouter des styles pour le tableau
      table.style.width = "80%";
      table.style.margin = "auto";
      table.style.padding = "10px";
      
      // Ajouter des styles pour l'en-tête du tableau
      var thead = table.getElementsByTagName("thead")[0];
      thead.style.backgroundColor = "#4CAF50"; // Exemple de couleur de fond (vert)
      thead.style.color = "white";
      
      // Créer la div avec les informations personnelles
      var infoDiv = document.createElement('div');
      infoDiv.innerHTML = `
          <h2 style="text-align: center; margin-bottom: 20px;">FICHE D'ENGAGEMENT</h2>
          <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div>
              <p><strong>Nom: </strong>${noms}</p>
              <p><strong>Prénom: </strong>${prenoms}</p>
              <p><strong>Classe: </strong>${classe}</p>
            </div>
            <div>
              <p><strong>Montant à payer: </strong>${totalApayerFormatted} FCFA</p>
              <p><strong>Arriéré: </strong>${arriererFormatted} FCFA</p>
              <p><strong>Année scolaire: </strong>${annescolaire}</p>
            </div>
          </div>
          <div style="width: 80%; margin: auto; padding: 10px 0; margin-bottom: 20px;">
            Chargé du suivi de l'élève:  ..........................................................................................................................................
            <div style="display: inline-block; margin-right: 10px;">
              <input type="checkbox" id="inlineCheckbox1" value="option1">
              <label for="inlineCheckbox1">Père/Mère</label>
            </div>
            <div style="display: inline-block;">
              <input type="checkbox" id="inlineCheckbox2" value="option2">
              <label for="inlineCheckbox2">Tuteur</label>
            </div>
          </div>
      `;
      
      // Créer une div pour les observations et les signatures
      var today = new Date();
      var day = String(today.getDate()).padStart(2, '0');
      var month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
      var year = today.getFullYear();
      var formattedDate = `${day}/${month}/${year}`;
      
      var areaDiv = document.createElement('div');
      areaDiv.innerHTML = `
          <div style="padding-top: 10px; height: 100px; width: 700px; margin: 40px auto; border: 1px solid #000;">
            <h6><u><em>Observations particulières</u></em></h6>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 20px 40px;">
            <div>La Direction</div>
            <div>Le parent ou le tuteur</div>
            <div>Fait à CCC, le ${formattedDate}
              <p>L'élève</p>
            </div>
          </div>
      `;
      
      // Modifier les th pour l'impression
      var ths = table.getElementsByTagName("th");
      ths[2].textContent = "Montant Écheance"; // Changer "Scolarité" en "Échéancier"
      
      // Ajouter une nouvelle colonne "Observation" dans l'en-tête
      var newTh = document.createElement('th');
      newTh.textContent = "Observation";
      table.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(newTh);
      
      // Remplacer les inputs par leur valeur dans le tableau cloné
      var inputs = table.querySelectorAll("input");
      inputs.forEach(function(input) {
        var cell = input.parentNode;
        cell.textContent = input.value; // Remplacer l'input par sa valeur
      });
      
      // Ajouter une nouvelle colonne "Observation" dans chaque ligne du tableau
      var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
      for (var i = 0; i < rows.length; i++) {
        var newTd = document.createElement('td');
        newTd.textContent = ""; // Vous pouvez modifier cette valeur ou la remplir dynamiquement
        rows[i].appendChild(newTd);
      }
      
      // Ajouter une colonne vide dans le tfoot pour l'Observation
      var footRow = table.getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0];
      var newFootTd = document.createElement('td');
      newFootTd.textContent = ""; // Cellule d'observation dans le pied de page
      footRow.appendChild(newFootTd);
      
      // Créer le div qui contiendra le contenu à imprimer
      var printDiv = document.createElement('div');
      printDiv.setAttribute("id", "printDiv");
      printDiv.appendChild(infoDiv);
      printDiv.appendChild(table);
      printDiv.appendChild(areaDiv);
      
      // Ajouter des styles directement dans la page
      var style = document.createElement('style');
      style.textContent = `
        body * { visibility: hidden; }
                        #printDiv, #printDiv * { visibility: visible; }
                  #printDiv { position: absolute; top: 0; left: 0; width: 100%; }
          table { width: 80%; margin: auto; padding: 10px; border-collapse: collapse; }
          th, td { border: 1px solid black; padding: 8px; text-align: left; }
          th { background-color: #4CAF50; color: white; }
          h2 { text-align: center; }
          p { margin: 0; }
          .flex-container { display: flex; justify-content: space-between; }
      `;
      
      // Ajouter le div et le style au document pour l'impression
      document.head.appendChild(style);
      document.body.appendChild(printDiv);

      // Lancer l'impression
      window.print();
      window.location.reload();

      // Supprimer le div et les styles après l'impression pour restaurer l'état original
      // document.body.removeChild(printDiv);
      // document.head.removeChild(style);
  }
  
  // document.addEventListener("DOMContentLoaded", function () {
  //   // Liste des inputs pour le calcul du montant total
  //   const inputs = [
  //   document.getElementById("apayer"),
  //   document.getElementById("arriere"),
  //   document.getElementById("frais1"),
  //   document.getElementById("frais2"),
  //   document.getElementById("frais3"),
  //   document.getElementById("frais4")
  //   ];
  //   const arrie = document.getElementById("arriere").value;
    
  //   // Inputs pour la durée, la date de début et la périodicité
  //   const dureeInput = document.getElementById("duree");
  //   const dateDebutInput = document.getElementById("dateDebut");
  //   const periodiciteInput = document.getElementById("periodicite");
  //   const paymentTable = document.getElementById("paymentTable").querySelector("tbody");
  //   function mettreAJourTotaux() {
  //     let totalMontantNouveau = 0;
  //     document.querySelectorAll('.montant').forEach(function(input) {
  //       totalMontantNouveau += parseFloat(input.value) || 0;
  //       document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
        
  //     });
  //   }
  //   function updateTable() {
  //     // Calcul du total des montants
  //     let total = inputs.reduce((sum, input) => sum + parseFloat(input.value) || 0, 0);
  //     let duree = parseInt(dureeInput.value) || 1;
  //     let periodicite = parseInt(periodiciteInput.value) || 1;
  //     let dateDebut = new Date(dateDebutInput.value);
  //     let montantParEcheance = parseInt(total / duree);
      
  //     // Réinitialiser le tableau
  //     paymentTable.innerHTML = "";
      
  //     // Remplir le tableau avec les nouvelles données
  //     for (let i = 1; i <= duree; i++) {
  //       const datePaiement = new Date(dateDebut);
  //       // Calcul de la date en fonction de la périodicité
  //       if (periodicite < 7) {
  //         datePaiement.setMonth(datePaiement.getMonth() + (i - 1) * periodicite);
  //       } else {
  //         datePaiement.setDate(datePaiement.getDate() + (i - 1) * periodicite);
  //       }
  //       const datePaiementFormat = datePaiement.toLocaleDateString('fr-FR');
        
  //       let row = paymentTable.insertRow();
  //       row.classList.add('echeance-row');
  //       row.innerHTML = `
  //               <tr class="echeance-row">
  //               <td>${i}</td>
  //               <td class="d-none" id="arrie-${i}">${arrie}</td>
  //               <td id="datepaie-${i}">${datePaiementFormat}</td>
  //               <td><input type="text" id="montantpaye-${i}" class="form-control montant" name="montant_${i}" value="${montantParEcheance}" /></td>
  //               </tr>
  //           `;
  //       document.querySelectorAll('.montant').forEach(function(input) {
  //         input.addEventListener('input', mettreAJourTotaux);
  //       });
        
  //       mettreAJourTotaux();
  //     }
  //   }
    
  //   dureeInput.addEventListener("input", updateTable);
  //   dateDebutInput.addEventListener("input", updateTable);
  //   periodiciteInput.addEventListener("input", updateTable);
    
  //   updateTable();
    
  //   paymentTable.addEventListener("input", function (e) {
  //     if (e.target && e.target.classList.contains("montant")) {
  //       console.log("Montant modifié:", e.target.value);
  //       mettreAJourTotaux();
        
  //     }
  //   });
    
    
  //   document.getElementById('validerbtn').addEventListener('click', function(event) {
  //     // Empêcher la soumission du formulaire si nécessaire
  //     event.preventDefault();
  //     let total = inputs.reduce((sum, input) => sum + parseFloat(input.value) || 0, 0);
  //     // Récupérer le total des montants dans le tableau
  //     let totalMontantNouveau = parseFloat(document.getElementById('totalMontantNouveauEleve').innerText);
      
  //     // Vérifier si les totaux correspondent
  //     if (total !== totalMontantNouveau) {
  //       // Mettre à jour le message dans le modal
  //       document.getElementById('alertModalMessage').textContent = "Le total des montants ne correspond pas. Veuillez vérifier les montants saisis.";
  //       // Afficher le modal
  //       let alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {});
  //       alertModal.show();
        
  //       event.preventDefault(); // Empêcher la soumission du formulaire
  //     }  else {
  //       let echeances = [];
        
  //       // Pour chaque ligne d'échéance
  //       document.querySelectorAll('.echeance-row').forEach(function(row, index) {
  //         const tranche = index + 1;
  //         const datepaye = document.getElementById(`datepaie-${tranche}`).textContent;
  //         const arrie = document.getElementById(`arrie-${tranche}`).textContent;
  //         const montantpaye = document.getElementById(`montantpaye-${tranche}`).value;
          
  //         echeances.push(
  //         {
  //           tranche: tranche,
  //           datepaye: datepaye,
  //           montantpaye: montantpaye,
  //           arrie: arrie
            
  //         });
  //       });
  //       document.getElementById('echeancesData').value = JSON.stringify(echeances);
        
  //       // Soumettre le formulaire si la validation est réussie
  //       document.getElementById('validationForm').submit();  
  //     }
  //   });
  // });
  
</script>





    <script>
        const tableBodys = document.getElementById('tbodyNouveaux');
        const initialDatas = JSON.parse(document.getElementById('echeancierNouveau').dataset.initial);

        initialDatas.forEach(echeance => {
            const tr = document.createElement('tr');
                const total = (Number(echeance.APAYER) + Number(echeance.ARRIERE));

            tr.innerHTML =
            `
                <td class="text-center">${echeance.NUMERO}</td>
                <td class="text-center">${echeance.DATEOP}</td>
                <td class="text-center"><input type="number" readonly class="form-control text-center montant-nouveau" id="inputNou" value="${total}" data-tranche-nouveau="${echeance.NUMERO}" /></td>
            `;
            tableBodys.appendChild(tr);
        });

         // function mettreAJourTotaux12() {
        let totalMontantNouveau = 0;
        // let totalMontantAncien = 0;

        // Calcul du total des nouveaux élèves
        document.querySelectorAll('.montant-nouveau').forEach(function(input) {
            totalMontantNouveau += parseFloat(input.value) || 0;
        });

        // Mise à jour des totaux dans le tableau
        document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau.toLocaleString('fr-FR');
        // document.getElementById('totalMontantAncienEleve').innerText = totalMontantAncien;
        // Mettre à jour les totaux dans le tfoot

        document.getElementById('totalMontantNouveau').innerText = totalMontantNouveau.toLocaleString('fr-FR');
        // document.getElementById('totalMontantAncien').innerText = totalMontantAncien;

    </script>  


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour gerer le boutton defaut
            // const defaultBtn = document.getElementById('default');
            // const mts = document.getElementById('mts').value;
            // const mt1 = document.getElementById('mt1').value;
            // const mt2 = document.getElementById('mt2').value;
            // const mt3 = document.getElementById('mt3').value;
            // const mt4 = document.getElementById('mt4').value;

            // defaultBtn.addEventListener('click', setDefaults);

            // function setDefaults() {
            //     console.log(mts);
            //     document.getElementById('scolaritee').value = mts;
            //     document.getElementById('scolarite_a').value = mts;

            //     document.getElementById('frais1').value = mt1;
            //     document.getElementById('frais1_a').value = mt1;

            //     document.getElementById('frais2').value = mt2;
            //     document.getElementById('frais2_a').value = mt2;

            //     document.getElementById('frais3').value = mt3;
            //     document.getElementById('frais3_a').value = mt3;

            //     document.getElementById('frais4').value = mt4;
            //     document.getElementById('frais4_a').value = mt4;

            // };




            // Fonction pour mettre en readonly les champs si le label est vide
            // function setReadonlyIfEmpty(labelId, inputId1, inputId2) {
            //     const label = document.getElementById(labelId);
            //     if (label && label.textContent.trim() === '') {
            //         document.getElementById(inputId1).setAttribute('readonly', true);
            //         document.getElementById(inputId2).setAttribute('readonly', true);
            //     }
            // }

            // Vérification pour chaque couple label/input
            // setReadonlyIfEmpty('Scolarite', 'scolaritee', 'scolarite_a');
            // setReadonlyIfEmpty('labelFrais1', 'frais1', 'frais1_a');
            // setReadonlyIfEmpty('labelFrais2', 'frais2', 'frais2_a');
            // setReadonlyIfEmpty('labelFrais3', 'frais3', 'frais3_a');
            // setReadonlyIfEmpty('labelFrais4', 'frais4', 'frais4_a');



            // Fonction pour calculer la somme des frais et mettre à jour le label
            function calculerSommeFrais() {

                // nouveau eleves
                const scolarite = parseFloat(document.getElementById('apayer').value) || 0;
                const arriere = parseFloat(document.getElementById('arriere').value) || 0;
                const frais1 = parseFloat(document.getElementById('frais1').value) || 0;
                const frais2 = parseFloat(document.getElementById('frais2').value) || 0;
                const frais3 = parseFloat(document.getElementById('frais3').value) || 0;
                const frais4 = parseFloat(document.getElementById('frais4').value) || 0;

                const total = frais1 + scolarite + arriere + frais2 + frais3 + frais4;

                // // anciens eleves
                // const scolarite_a = parseFloat(document.getElementById('scolarite_a').value) || 0;
                // const frais1_a = parseFloat(document.getElementById('frais1_a').value) || 0;
                // const frais2_a = parseFloat(document.getElementById('frais2_a').value) || 0;
                // const frais3_a = parseFloat(document.getElementById('frais3_a').value) || 0;
                // const frais4_a = parseFloat(document.getElementById('frais4_a').value) || 0;

                // const total_a = scolarite_a + frais1_a + frais2_a + frais3_a + frais4_a;

                // Mettre à jour la somme dans le label
                document.getElementById('totalFrais').textContent = total;
                // document.getElementById('totalFraisAnciens').textContent = total_a;
            }

            calculerSommeFrais();


            // Mettre à jour la somme chaque fois qu'une valeur de frais change pour les nouveau
            // document.getElementById('scolaritee').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais1').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais2').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais3').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais4').addEventListener('input', calculerSommeFrais);

            // Mettre à jour la somme chaque fois qu'une valeur de frais change pour les anciens
            // document.getElementById('scolarite_a').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais1_a').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais2_a').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais3_a').addEventListener('input', calculerSommeFrais);
            // document.getElementById('frais4_a').addEventListener('input', calculerSommeFrais);

            // Fonction pour recalculer les totaux et mettre à jour les échéances
            function mettreAJourTotaux() {
                let totalMontantNouveau = 0;
                // let totalMontantAncien = 0;

                // Calcul du total des nouveaux élèves
                document.querySelectorAll('.montant-nouveau').forEach(function(input) {
                    totalMontantNouveau += parseFloat(input.value) || 0;
                });

                // Calcul du total des anciens élèves
                // document.querySelectorAll('.montant-ancien').forEach(function(input) {
                //     totalMontantAncien += parseFloat(input.value) || 0;
                // });

                // Mise à jour des totaux dans le tableau
                document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
                // document.getElementById('totalMontantAncienEleve').innerText = totalMontantAncien;
                // Mettre à jour les totaux dans le tfoot

                // document.getElementById('totalMontantNouveau').innerText = totalMontantNouveau;
                // document.getElementById('totalMontantAncien').innerText = totalMontantAncien;


                // Mise à jour des pourcentages dans le tableau des échéances
                // recalculerPourcentages(totalMontantNouveau, totalMontantAncien);
            }

            // Fonction pour recalculer les pourcentages dans le tableau des échéances
            // function recalculerPourcentages(totalMontantNouveau, totalMontantAncien) {
            //     let totalPourcentageNouveau = 0;
            //     let totalPourcentageAncien = 0;

            //     // Recalculer les pourcentages pour chaque tranche de nouveaux élèves
            //     document.querySelectorAll('.montant-nouveau').forEach(function(input, index) {
            //         const montant = parseFloat(input.value) || 0;
            //         const pourcentage = totalMontantNouveau > 0 ? (montant / totalMontantNouveau * 100)
            //             .toFixed(2) : 0;

            //         // Mettre à jour le pourcentage pour chaque tranche avec le symbole %
            //         document.getElementById(`pourcentageTrancheNouveau-${index + 1}`).innerText =
            //             pourcentage + '%';

            //         // Ajouter le pourcentage calculé au total sans le symbole %
            //         totalPourcentageNouveau += parseFloat(pourcentage);
            //     });

            //     // Recalculer les pourcentages pour chaque tranche d'anciens élèves
            //     document.querySelectorAll('.montant-ancien').forEach(function(input, index) {
            //         const montant = parseFloat(input.value) || 0;
            //         const pourcentage = totalMontantAncien > 0 ? (montant / totalMontantAncien * 100)
            //             .toFixed(4) : 0;

            //         // Mettre à jour le pourcentage pour chaque tranche avec le symbole %
            //         document.getElementById(`pourcentageTrancheAncien-${index + 1}`).innerText =
            //             pourcentage + '%';

            //         // Ajouter le pourcentage calculé au total sans le symbole %
            //         totalPourcentageAncien += parseFloat(pourcentage);
            //     });

            //     // Mettre à jour le total des pourcentages avec le symbole % dans le tfoot
            //     document.getElementById('totalPourcentageNouveau').innerText = totalPourcentageNouveau.toFixed(4) +
            //         '%';
            //     document.getElementById('totalPourcentageAncien').innerText = totalPourcentageAncien.toFixed(4) +
            //         '%';
            // }

            // Fonction pour générer le tableau des échéances
            function genererEcheancier() {

              const typeEcheance = parseInt(document.getElementById('typeEcheance').value);

                const nbEcheances = parseInt(document.getElementById('nbEcheances').value) || 1;
                const dateDebut = new Date(document.getElementById('dateDebut').value);
                const periodicite = parseInt(document.getElementById('periodicite').value) || 1;

                let  montantNouveau;


                if (typeEcheance == 1) {
                    montantNouveau = parseFloat(document.getElementById('apayer').value) + parseFloat(document.getElementById('arriere').value);
                    // montantAncien = parseFloat(document.getElementById('scolarite_a').value);
                } else {
                    montantNouveau = parseFloat(document.getElementById('totalFrais').textContent);
                    // montantAncien = parseFloat(document.getElementById('totalFraisAnciens').textContent);
                }

                const tbody = document.getElementById('tableBody');
                const tbodyNouveaux = document.getElementById('tbodyNouveaux');
                // const tbodyAnciens = document.getElementById('tbodyAnciens');

                // Vider les tableaux
                tbody.innerHTML = '';
                tbodyNouveaux.innerHTML = '';
                // tbodyAnciens.innerHTML = '';

                for (let i = 1; i <= nbEcheances; i++) {
                    const datePaiement = new Date(dateDebut);
                    if (periodicite < 7) {
                        datePaiement.setMonth(datePaiement.getMonth() + (i - 1) * periodicite);
                    } else {
                        datePaiement.setDate(datePaiement.getDate() + (i - 1) * periodicite);
                    }

                    const datePaiementFormat = datePaiement.toLocaleDateString('fr-FR');

                    const montantTrancheNouveau = Math.ceil(montantNouveau / nbEcheances);
                    // const montantTrancheAncien = Math.ceil(montantAncien / nbEcheances);

                    // Créer une nouvelle ligne pour chaque échéance
                    const row = `
                    <tr class="echeance-row">
                        <td>${i}</td>
                        <td id="pourcentageTrancheNouveau-${i}">0%</td>
                        <td id="montantTrancheNouveau-${i}">${montantTrancheNouveau}</td>
                        <td id="datepaie-${i}" classe=".date-paiement" style="display: none;">${datePaiementFormat}</td>
                    </tr>
                `;
                    tbody.insertAdjacentHTML('beforeend', row);

                    // Créer et insérer les lignes dans le tableau des nouveaux élèves
                    const rowNouveau = `
                    <tr>
                        <td>${i}</td>
                        <td>${datePaiementFormat}</td>
                        <td><input type="number" class="form-control text-center montant-nouveau" value="${montantTrancheNouveau}" data-tranche-nouveau="${i}" /></td>
                    </tr>
                `;
                    tbodyNouveaux.insertAdjacentHTML('beforeend', rowNouveau);

                    // Créer et insérer les lignes dans le tableau des anciens élèves
                //     const rowAncien = `
                //     <tr>
                //         <td>${i}</td>
                //         <td>${datePaiementFormat}</td>
                //         <td><input type="number" class="form-control montant-ancien" value="${montantTrancheAncien}" data-tranche-ancien="${i}" /></td>
                //     </tr>
                // `;
                //     tbodyAnciens.insertAdjacentHTML('beforeend', rowAncien);
                }




                // Réattacher les événements sur les champs de montants modifiables
                document.querySelectorAll('.montant-nouveau').forEach(function(input) {
                    input.addEventListener('input', function() {
                        const trancheIndex = input.getAttribute('data-tranche-nouveau');
                        document.getElementById(`montantTrancheNouveau-${trancheIndex}`).innerText =
                            input.value;
                        mettreAJourTotaux(); // Met à jour les totaux après chaque modification
                    });
                });

                // document.querySelectorAll('.montant-ancien').forEach(function(input) {
                //     input.addEventListener('input', function() {
                //         const trancheIndex = input.getAttribute('data-tranche-ancien');
                //         document.getElementById(`montantTrancheAncien-${trancheIndex}`).innerText =
                //             input.value;
                //         mettreAJourTotaux(); // Met à jour les totaux après chaque modification
                //     });
                // });

                // Calculer les pourcentages initiaux
                mettreAJourTotaux();
            }




            // Attacher un événement au champ Nb. échéance pour recalculer à chaque changement
            document.getElementById('nbEcheances').addEventListener('input', genererEcheancier);
            // document.getElementById('scolaritee').addEventListener('input', genererEcheancier);
            // document.getElementById('scolarite_a').addEventListener('input', genererEcheancier);
            // document.getElementById('frais1').addEventListener('input', genererEcheancier);
            // document.getElementById('frais1_a').addEventListener('input', genererEcheancier);
            // document.getElementById('frais2').addEventListener('input', genererEcheancier);
            // document.getElementById('frais2_a').addEventListener('input', genererEcheancier);
            // document.getElementById('frais3').addEventListener('input', genererEcheancier);
            // document.getElementById('frais3_a').addEventListener('input', genererEcheancier);
            // document.getElementById('frais4').addEventListener('input', genererEcheancier);
            // document.getElementById('frais4_a').addEventListener('input', genererEcheancier);
            document.getElementById('dateDebut').addEventListener('change', genererEcheancier);
            document.getElementById('periodicite').addEventListener('input', genererEcheancier);




            // Ajouter des écouteurs aux boutons radio pour recalculer à chaque changement d'option
            // document.getElementById('flexRadioDefault1').addEventListener('change', genererEcheancier);
            // document.getElementById('flexRadioDefault2').addEventListener('change', genererEcheancier);

            // Générer l'échéancier initialement
            // genererEcheancier();



            document.getElementById('validerbtn').addEventListener('click', function(event) {
                // Empêcher la soumission du formulaire si nécessaire
                event.preventDefault();

                const typeEcheance = parseInt(document.getElementById('typeEcheance').value);

                // Calculer le total des montants dans le tableau des nouveaux élèves
                let totalMontantNouveauTableau = 0;
                // let totalMontantAncienTableau = 0;
                document.querySelectorAll('.montant-nouveau').forEach(function(input) {
                    totalMontantNouveauTableau += parseFloat(input.value) || 0;
                });

                // document.querySelectorAll('.montant-ancien').forEach(function(input) {
                //     totalMontantAncienTableau += parseFloat(input.value) || 0;
                // });

                // console.log(document.getElementById('scolaritee').value);




                if (typeEcheance == 1) {
                      montantNouveauUtilise = parseFloat(document.getElementById('apayer').value) + parseFloat(document.getElementById('arriere').value);
                } else {
                    montantNouveauUtilise = parseFloat(document.getElementById('totalFrais').textContent);
                }

                // Récupérer le montant des nouveaux à utiliser
                //  montantNouveauUtilise = parseFloat(document.getElementById('montantNouveauUtilise').value) || 0;

                // Vérifier si les montants correspondent pour les nouveaux élèves
                if (totalMontantNouveauTableau !== montantNouveauUtilise) {
                    showModalMessage(
                        'Le total des montants dans le tableau ne correspond pas au montant total à utiliser.'
                    );
                    return; // Ne pas soumettre le formulaire
                }

                // Vérifier si les montants correspondent pour les anciens élèves
                // if (totalMontantAncienTableau !== montantAncienUtilise) {
                //     showModalMessage(
                //         'Le total des montants dans le tableau des anciens élèves ne correspond pas au montant des anciens à utiliser.'
                //     );
                //     return; // Ne pas soumettre le formulaire
                // }

                // Vérifier si les champs requis sont remplis
                const nbEcheances = document.getElementById('nbEcheances').value.trim();
                const dateDebut = document.getElementById('dateDebut').value.trim();
                // const periodicite = document.getElementById('periodicite').value.trim();

                let errors = [];

                if (!nbEcheances) {
                    errors.push('Le nombre d\'échéances est requis.');
                }

                if (!dateDebut) {
                    errors.push('La date de début de paiement est requise.');
                }

                // if (!periodicite) {
                //     errors.push('La périodicité est requise.');
                // }

                // Afficher les messages d'erreur s'il y en a
                if (errors.length > 0) {
                    showModalMessage(errors.join('\n'));
                    return; // Ne pas soumettre le formulaire
                }

                // Récupérer la valeur de la classe
                const classe = document.getElementById('classe').value;

                // Collecter les données des échéances
                let echeances = [];

                // Pour chaque ligne d'échéance
                document.querySelectorAll('.echeance-row').forEach(function(row, index) {
                    const tranche = index + 1;
                    // Récupérer les pourcentages de la ligne (nouveaux et anciens)
                    const pourcentageNouveau = document.getElementById(
                        `pourcentageTrancheNouveau-${tranche}`).textContent.replace('%', '');

                    // const pourcentageTrancheNouveau = document.getElementById(`pourcentageTrancheNouveau-${tranche}`).textContent;
                    // const pourcentageTrancheAncien = document.getElementById(`pourcentageTrancheAncien-${tranche}`).textContent;
                    const montantTrancheNouveau = document.getElementById(
                        `montantTrancheNouveau-${tranche}`).textContent;

                    const datePaiement = document.getElementById(`datepaie-${tranche}`).textContent;

                    // Convertir en nombre
                    const nombreNouveau = parseFloat(pourcentageNouveau);

                    // Convertir en fractions
                    const fractionNouveau = nombreNouveau / 100;

                    // console.log(fractionNouveau);


                    // Ajouter ces données dans la collection
                    echeances.push({
                        tranche: tranche,
                        pourcentage_nouveau: fractionNouveau,
                        montant_nouveau: montantTrancheNouveau,
                        date_paiement: datePaiement,
                        classe: classe
                    });
                });

                // Convertir les données en JSON et les stocker dans un champ caché
                document.getElementById('echeancesData').value = JSON.stringify(echeances);
                // Soumettre le formulaire si la validation est réussie
                document.getElementById('validationForm').submit();

            });

            // les pourcentage sous forme de fraction
            // function pourcentageEnFraction(pourcentage) {
            //     // Convertir le pourcentage en fraction
            //     const fractionNumerator = Math.round(pourcentage *
            //         100); // Multiplier par 100 pour avoir une fraction entière
            //     const fractionDenominator = 10000; // Dénominateur fixe
            //     return `${fractionNumerator}/${fractionDenominator}`;
            // }

            // Fonction pour afficher le message dans le modal
            function showModalMessage(message) {
                // Convertir les sauts de ligne (\n) en <br> pour une bonne mise en forme dans le HTML
                const formattedMessage = message.replace(/\n/g, '<br>');

                // Mettre à jour le contenu du modal avec le message formaté
                document.getElementById('alertModalMessage').innerHTML = formattedMessage;

                // Afficher le modal
                let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();
            }
        });
    </script>
      

@endsection

