@extends('layouts.master')
@section('content')

<style>
    .container {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        width: 100%;
        max-width: 5000px;
    }
    button {
        padding: 10px;
        font-size: 14px;
        background-color: #7c83a8;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    button:hover {
        background-color: #AC32E4E6;
    }
    button i {
        margin-right: 8px;
    }
    a {
        color: white;
        text-decoration: none;
    }
</style>

<div class="card">
    <div class="container">
        <button><a href="{{ url('/eleveparclasse') }}"><i class="fas fa-users"></i> Liste des élèves par classe</a></button>
        <button><a href="{{ url('/listedeseleves') }}"><i class="fas fa-user-graduate"></i> Liste générale des élèves</a></button>
        <button><a href="{{ url('/listeselective') }}"><i class="fas fa-user-check"></i> Liste sélective des élèves</a></button>
        <button data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fas fa-book"></i> Registre des élèves</button>
       
          
        <button><a href="{{ url('/listedesclasses') }}"><i class="fas fa-list"></i> Liste des classes</a></button>
        <button><a href="{{ url('/certificat') }}"><i class="fas fa-certificate"></i> Certificats de scolarité</a></button>
        <button><a href="{{ url('/attestationsdescolarite') }}"><i class="fas fa-file-alt"></i> Attestations de scolarité</a></button>
        <button><a href="{{ url('/enquetesstatistiques') }}"><i class="fas fa-chart-bar"></i> Enquêtes statistiques</a></button>
        <button><a href="{{ url('/etatdelacaisse') }}"><i class="fas fa-cash-register"></i> État de la caisse</a></button>
        <button><a href="{{ url('/pointderecouvrement') }}"><i class="fas fa-chalkboard-teacher"></i> Point de recouvrement par enseignement</a></button>
        <button><a href="{{ url('/etatdesrecouvrements') }}"><i class="fas fa-file-invoice-dollar"></i> État des recouvrements</a></button>
        <button><a href="{{ url('/droitconstate') }}"><i class="fas fa-file-signature"></i> État des droits constatés par classe</a></button>
        <button><a href="{{ url('/situationfinanciere') }}"><i class="fas fa-calendar-alt"></i> Situation financière selon l'échéancier</a></button>
        <button><a href="{{ url('/listedesretardsdepaiement') }}"><i class="fas fa-exclamation-circle"></i> Liste des retards de paiement</a></button>
        <button><a href="{{ url('/lettresderelance') }}"><i class="fas fa-envelope"></i> Lettres de relance</a></button>
        <button><a href="{{ url('/situationfinanciereglobale') }}"><i class="fas fa-balance-scale"></i> Situation financière globale</a></button>
        <button><a href="{{ url('/listedereductions') }}"><i class="fas fa-percentage"></i> Liste des réductions accordées</a></button>
        <button><a href="{{ url('/listedeselèvesparprofil') }}"><i class="fas fa-id-card"></i> Liste des élèves par profil</a></button>
        <button><a href="{{ url('/listedeselèvesechéancierpersonnalisé') }}"><i class="fas fa-calendar-check"></i> Liste des élèves ayant un échéancier personnalisé</a></button>
        <button><a href="{{ url('/etatdesarriérésinscrits') }}"><i class="fas fa-exclamation-triangle"></i> État général des arriérés (élèves inscrits)</a></button>
        <button><a href="{{ url('/etatdesarriérésmoissoldés') }}"><i class="fas fa-minus-circle"></i> État général des arriérés moins ceux qui sont soldés</a></button>
        <button><a href="{{ url('/etatdesarriérésconstatés') }}"><i class="fas fa-file-invoice"></i> État des arriérés constatés (élèves inscrits)</a></button>
        <button><a href="{{ url('/etatdesarriérés') }}"><i class="fas fa-exclamation"></i> État général des arriérés</a></button>
    </div>
</div>
<!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Choix du type de registre</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="">
                 Registre par fiche
                </label>
                <p>Le reigistre sait creer sur le nom</p>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" checked>
                 Registre par tableau
                </label>
                <p>Le reigistre sait creer sur le matricule</p>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-primary">Imprimer</button>
        </div>
      </div>
    </div>
  </div>
@endsection
