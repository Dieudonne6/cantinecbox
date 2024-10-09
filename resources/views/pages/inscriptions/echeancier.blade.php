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
              <div class="col-4" style="margin-top:3rem">
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
                        <label class="mr-2">Frais 1</label>
                        <div id="the-basics">
                          <input class="form-control" type="text" id="frais1" name="frais1" value="{{ $eleve->FRAIS1 }}" readonly>
                          
                        </div>
                      </div>
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">Frais 2</label>
                        <div id="bloodhound">
                          <input class="form-control" id="frais2" name="frais2" type="text"  value="{{ $eleve->FRAIS2}}" readonly>
                          
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group row">
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">Frais 3</label>
                        <div id="the-basics">
                          <input class="form-control" id="frais3" type="text" name="frais3" value="{{ $eleve->FRAIS3 }}" readonly>
                        </div>
                      </div>
                      <div class="col d-flex align-items-center">
                        <label class="mr-2">Frais 4</label>
                        <div id="bloodhound">
                          <input class="form-control" id="frais4" type="text" name="frais4" value="{{ $eleve->FRAIS4 }}" readonly>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group row">
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
                      <div class="col">
                        <button type="submit" class="btn btn-primary mr-2">Valider</button>
                      </div>
                    </div>
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
                        <input type="date" class="form-control" id="dateDebut" value="{{$classis->DATEDEB}}"  style="width: 10rem"/>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-4">
                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Periodicite</label>
                      <div class="col-sm-3">
                        <input type="text" id="periodicite" class="form-control" style="width: 3.9rem !important" value="{{$classis->PERIODICITE}}" />
                      </div>
                      <label class="col-sm-3 col-form-label">Mois</label>
                    </div>
                  </div>
                  
                  <div class="col-3">
                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Duree</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control"  id="duree" style="width: 3.9rem !important" value="{{$classis->DUREE}}" />
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
                  <table class="table table-bordered"  id="paymentTable">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Scolarite</th>
                        <th class="d-none"></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Total</th>
                        <th></th>
                        <th id="totalMontantNouveauEleve"></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <br>
              </div>
            </div>
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
                  <button type="button" id="validerbtn" class="btn btn-primary" style="margin-top: 10px; width:8rem;">Regenerer</button>
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
  
  document.addEventListener("DOMContentLoaded", function () {
    // Liste des inputs pour le calcul du montant total
    const inputs = [
    document.getElementById("apayer"),
    document.getElementById("arriere"),
    document.getElementById("frais1"),
    document.getElementById("frais2"),
    document.getElementById("frais3"),
    document.getElementById("frais4")
    ];
    const arrie = document.getElementById("arriere").value;
    
    // Inputs pour la durée, la date de début et la périodicité
    const dureeInput = document.getElementById("duree");
    const dateDebutInput = document.getElementById("dateDebut");
    const periodiciteInput = document.getElementById("periodicite");
    const paymentTable = document.getElementById("paymentTable").querySelector("tbody");
    function mettreAJourTotaux() {
      let totalMontantNouveau = 0;
      document.querySelectorAll('.montant').forEach(function(input) {
        totalMontantNouveau += parseFloat(input.value) || 0;
        document.getElementById('totalMontantNouveauEleve').innerText = totalMontantNouveau;
        
      });
    }
    function updateTable() {
      // Calcul du total des montants
      let total = inputs.reduce((sum, input) => sum + parseFloat(input.value) || 0, 0);
      let duree = parseInt(dureeInput.value) || 1;
      let periodicite = parseInt(periodiciteInput.value) || 1;
      let dateDebut = new Date(dateDebutInput.value);
      let montantParEcheance = parseInt(total / duree);
      
      // Réinitialiser le tableau
      paymentTable.innerHTML = "";
      
      // Remplir le tableau avec les nouvelles données
      for (let i = 1; i <= duree; i++) {
        const datePaiement = new Date(dateDebut);
        // Calcul de la date en fonction de la périodicité
        if (periodicite < 7) {
          datePaiement.setMonth(datePaiement.getMonth() + i * periodicite);
        } else {
          datePaiement.setDate(datePaiement.getDate() + i * periodicite);
        }
        const datePaiementFormat = datePaiement.toLocaleDateString('fr-FR');
        
        let row = paymentTable.insertRow();
        row.classList.add('echeance-row');
        row.innerHTML = `
                <tr class="echeance-row">
                <td>${i}</td>
                <td class="d-none" id="arrie-${i}">${arrie}</td>
                <td id="datepaie-${i}">${datePaiementFormat}</td>
                <td><input type="text" id="montantpaye-${i}" class="form-control montant" name="montant_${i}" value="${montantParEcheance}" /></td>
                </tr>
            `;
        document.querySelectorAll('.montant').forEach(function(input) {
          input.addEventListener('input', mettreAJourTotaux);
        });
        
        mettreAJourTotaux();
      }
    }
    
    dureeInput.addEventListener("input", updateTable);
    dateDebutInput.addEventListener("input", updateTable);
    periodiciteInput.addEventListener("input", updateTable);
    
    updateTable();
    
    paymentTable.addEventListener("input", function (e) {
      if (e.target && e.target.classList.contains("montant")) {
        console.log("Montant modifié:", e.target.value);
        mettreAJourTotaux();
        
      }
    });
    
    
    document.getElementById('validerbtn').addEventListener('click', function(event) {
      // Empêcher la soumission du formulaire si nécessaire
      event.preventDefault();
      let total = inputs.reduce((sum, input) => sum + parseFloat(input.value) || 0, 0);
      // Récupérer le total des montants dans le tableau
      let totalMontantNouveau = parseFloat(document.getElementById('totalMontantNouveauEleve').innerText);
      
      // Vérifier si les totaux correspondent
      if (total !== totalMontantNouveau) {
        // Mettre à jour le message dans le modal
        document.getElementById('alertModalMessage').textContent = "Le total des montants ne correspond pas. Veuillez vérifier les montants saisis.";
        // Afficher le modal
        let alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {});
        alertModal.show();
        
        event.preventDefault(); // Empêcher la soumission du formulaire
      }  else {
        let echeances = [];
        
        // Pour chaque ligne d'échéance
        document.querySelectorAll('.echeance-row').forEach(function(row, index) {
          const tranche = index + 1;
          const datepaye = document.getElementById(`datepaie-${tranche}`).textContent;
          const arrie = document.getElementById(`arrie-${tranche}`).textContent;
          const montantpaye = document.getElementById(`montantpaye-${tranche}`).value;
          
          echeances.push(
          {
            tranche: tranche,
            datepaye: datepaye,
            montantpaye: montantpaye,
            arrie: arrie
            
          });
        });
        document.getElementById('echeancesData').value = JSON.stringify(echeances);
        
        // Soumettre le formulaire si la validation est réussie
        document.getElementById('validationForm').submit();  
      }
    });
  });
  // Fonction pour lancer l'impression
function imprimerPage() {
    // Cloner le tableau pour éviter de modifier l'original
    var table = document.getElementById("paymentTable").cloneNode(true);
    
    let noms = $('#noms').val();
    let prenoms = $('#prenoms').val();
    let apayer = $('#apayer').val();
    let arriere = $('#arriere').val();
    let annescolaire = $('#annescolaire').val();
    let classe = $('#classe').val();
    
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
            <p><strong>Montant à payer: </strong>${apayer} FCFA</p>
            <p><strong>Arriéré: </strong>${arriere} FCFA</p>
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
</script>

@endsection

{{-- 

<div class="table-responsive pt-3">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>
          #
        </th>
        <th>
          First name
        </th>
        <th>
          Progress
        </th>
        <th>
          Amount
        </th>
        <th>
          Deadline
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          1
        </td>
        <td>
          Herman Beck
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 77.99
        </td>
        <td>
          May 15, 2015
        </td>
      </tr>
      <tr>
        <td>
          2
        </td>
        <td>
          Messsy Adam
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $245.30
        </td>
        <td>
          July 1, 2015
        </td>
      </tr>
      <tr>
        <td>
          3
        </td>
        <td>
          John Richards
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $138.00
        </td>
        <td>
          Apr 12, 2015
        </td>
      </tr>
      <tr>
        <td>
          4
        </td>
        <td>
          Peter Meggik
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 77.99
        </td>
        <td>
          May 15, 2015
        </td>
      </tr>
      <tr>
        <td>
          5
        </td>
        <td>
          Edward
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 160.25
        </td>
        <td>
          May 03, 2015
        </td>
      </tr>
      <tr>
        <td>
          6
        </td>
        <td>
          John Doe
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 123.21
        </td>
        <td>
          April 05, 2015
        </td>
      </tr>
      <tr>
        <td>
          7
        </td>
        <td>
          Henry Tom
        </td>
        <td>
          <div class="progress">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </td>
        <td>
          $ 150.00
        </td>
        <td>
          June 16, 2015
        </td>
      </tr>
    </tbody>
  </table>
</div> --}}