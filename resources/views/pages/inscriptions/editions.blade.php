@extends('layouts.master')
@section('content')

<style>
    .container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        width: 100%;
        max-width: 5000px;
    }

    /* Styles spécifiques aux boutons à l'intérieur de .container */
    .container button {
        padding: 10px;
        font-size: 14px;
        background-color: white;
        color: black;
        border: 1px solid black;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 250px;
        height: 70px;
    }

    .container button:hover {
        background-color: #844fc1;
    }

    .container button:hover a {
        color: white; /* Changement de couleur du texte */
    }

    .container button i {
        margin-right: 8px;
    }

    /* Styles spécifiques pour les boutons avec les classes registre-btn et profil-btn */
    .container .registre-btn:hover,
    .container .profil-btn:hover {
        color: white;
    }

    /* Styles pour les liens à l'intérieur des boutons */
    .container button a {
        color: black;
        text-decoration: none;
    }
</style>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Editions</h4>
    <div class="container">
      <button><a href="{{ url('/listedeseleves') }}"><i class="fas fa-list"></i> Liste générale des élèves</a></button>
      <button><a href="{{ url('/listedesclasses') }}"><i class="fas fa-list"></i> Liste des classes</a></button>
      <button><a href="{{ url('/listeselectiveeleve') }}"><i class="fas fa-list"></i> Liste selective des eleves</a></button>

      <button><a href="{{ url('/eleveparclasse') }}"><i class="fas fa-list"></i> Liste des eleves par classes</a></button>
      <button data-bs-toggle="modal" data-bs-target="#modalregistre"><i class="fas fa-list"></i> Registre des eleves</button>
      <button><a href="{{ url('/certificatsolarite') }}"><i class="fas fa-certificate"></i> Certificats de scolarité</a></button>
      <button><a href="{{ url('/attestationsdescolarite') }}"><i class="fas fa-file-alt"></i> Attestations de scolarité</a></button>
      <button><a href="{{ url('/enquetesstatistiques') }}"><i class="fas fa-chart-bar"></i> Enquêtes statistiques</a></button>
      <button><a href="{{ url('/etatdelacaisse') }}"><i class="fas fa-cash-register"></i> État de la caisse</a></button>
      <button><a href="{{ url('/pointderecouvrement') }}"><i class="fas fa-chalkboard-teacher"></i> Point de recouvrement par enseignement</a></button>
      <button><a href="{{ url('/etatdesrecouvrements') }}"><i class="fas fa-file-invoice-dollar"></i> État des recouvrements</a></button>
      <button><a href="{{ url('/etatdesdroits') }}"><i class="fas fa-file-signature"></i> État des droits constatés par classe</a></button>
      <button><a href="{{ url('/situationfinanciere') }}"><i class="fas fa-calendar-alt"></i> Situation financière selon l'échéancier</a></button>
      <button><a href="{{ url('/listedesretardsdepaiement') }}"><i class="fas fa-exclamation-circle"></i> Liste des retards de paiement</a></button>
      <button><a href="{{ url('/lettresderelance') }}"><i class="fas fa-envelope"></i> Lettres de relance</a></button>
      <button><a href="{{ url('/situationfinanciereglobale') }}"><i class="fas fa-balance-scale"></i> Situation financière globale</a></button>
      <button><a href="{{ url('/listedesreductions') }}"><i class="fas fa-percentage"></i> Liste des réductions accordées</a></button>
      <button class="profil-btn" type="button" id="" class="" data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="fas fa-id-card"></i> Liste des élèves par profil</button>

      <button><a href="{{ url('/listedeselèvesechéancierpersonnalisé') }}"><i class="fas fa-calendar-check"></i> Liste des élèves ayant un échéancier personnalisé</a></button>
      <button><a href="{{ url('/etatdesarrieresinscrits') }}"><i class="fas fa-exclamation-triangle"></i> État général des arriérés (élèves inscrits)</a></button>
      <button><a href="{{ url('/etatdesarriérésmoissoldés') }}"><i class="fas fa-minus-circle"></i> État général des arriérés moins ceux qui sont soldés</a></button>
      <button><a href="{{ url('/etatdesarriérésconstatés') }}"><i class="fas fa-file-invoice"></i> État des arriérés constatés (élèves inscrits)</a></button>
      <button><a href="{{ url('/etatdesarriérés') }}"><i class="fas fa-exclamation"></i> État général des arriérés</a></button>
    </div>
  </div>
</div>
<br><br>

<!-- Button trigger modal -->


<!-- Modal -->
{{-- <div class="modal fade" id="modalregistrebb" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printModalLabel">Choisir l'option d'impression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="printOption" id="optionFiche" value="fiche">
            <label class="form-check-label" for="optionFiche">
              Registre par fiche
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="printOption" id="optionTableau" value="tableau">
            <label class="form-check-label" for="optionTableau">
              Registre tableau
            </label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" id="btnImprimer">Imprimer</button>
      </div>
    </div>
  </div>
</div> --}}

<!-- Modal pour registre des eleves -->
<div class="modal fade" id="modalregistre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Choix du type de registre</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="1" checked>
                Registre par fiche
              </label>
              <p>Le registre est trié sur le nom</p>
          </div>
          <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="0">
                Registre par tableau
              </label>
              <p>Le registre est trié sur le matricule</p>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <a type="button" id="btnImprimerregistre" class="btn btn-primary">Imprimer</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Liste des élèves par profil</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="col-12 grid-margin">
              <div class="card">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-12">
                              <div class="form-group row">
                                  <!-- Contenu du modal -->
                                  @php
                                  use App\Models\Typeclasse;
                                  $typeclasse = Typeclasse::all();
                                  @endphp
                                  <select name="typeclasse" id="typeclasse">
                                    @foreach ($typeclasse as $type)
                                        <option value="{{ $type->TYPECLASSE }}">{{ $type->LibelleType }}</option>
                                    @endforeach
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="imprimerBtn" onclick="window.location.href='{{ route('impression.profil.type.classe') }}?typeclasse=' + document.getElementById('typeclasse').value;">Imprimer</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('btnImprimerregistre').addEventListener('click', function() {
    // Récupérer l'option sélectionnée (1 ou 0)
    const selectedOption = document.querySelector('input[name="optionsRadios"]:checked').value;
    
    // Modifier l'URL avec le paramètre d'option sélectionnée
    const url = "{{ url('/registreelev') }}?type=" + selectedOption;
    
    // Rediriger vers l'URL avec le paramètre
    // window.location.href = url;
    window.open(url, '_blank');
  });
</script>

@endsection
