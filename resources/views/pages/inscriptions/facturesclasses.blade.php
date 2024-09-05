@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Mise à jour des classes</h4>
    <div class="table-container">
        <table id="myTable">
            <thead>
                <tr>
                    <th>Groupe Pédagogique</th>
                    <th>Libellé</th>
                    <th>Enseignement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($factures as $facture)
                <tr>
                    <td data-label="Groupe Pédagogique">{{$facture->CODECLAS}}</td>
                    <td data-label="Libellé">{{$facture->LIBELCLAS}}</td>
                    <td data-label="Enseignement">{{$facture->typeenseigne_type}}</td>
                    <td data-label="Actions">
                        <a class="btn btn-primary" href="{{ url('detailfacturesclasses/'.$facture->CODECLAS) }}">Facture</a>
{{-- 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$facture->CODECLAS}}">
                            Facture
                        </button> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
  </div>
</div>

@endsection
  
  <!-- Modal -->
  @foreach ($factures as $facture)
  <!-- Modal -->
  <div class="modal fade" id="exampleModal{{ $facture->CODECLAS }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $facture->CODECLAS }}" aria-hidden="true">
      <div class="modal-dialog" style="max-width: 80%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel{{ $facture->CODECLAS }}">Enregistrement des classes</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <ul class="nav nav-tabs" id="myTab{{ $facture->CODECLAS }}" role="tablist">
                      <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="scolarite-tab{{ $facture->CODECLAS }}" data-bs-toggle="tab" data-bs-target="#scolarite{{ $facture->CODECLAS }}" type="button" role="tab" aria-controls="scolarite{{ $facture->CODECLAS }}" aria-selected="true">Scolarité</button>
                      </li>
                      <li class="nav-item" role="presentation">
                          <button class="nav-link" id="echeancier-tab{{ $facture->CODECLAS }}" data-bs-toggle="tab" data-bs-target="#echeancier{{ $facture->CODECLAS }}" type="button" role="tab" aria-controls="echeancier{{ $facture->CODECLAS }}" aria-selected="false">Échéancier de paiement</button>
                      </li>
                      <li class="nav-item" role="presentation">
                          <button class="nav-link" id="messages-tab{{ $facture->CODECLAS }}" data-bs-toggle="tab" data-bs-target="#messages{{ $facture->CODECLAS }}" type="button" role="tab" aria-controls="messages{{ $facture->CODECLAS }}" aria-selected="false">Messages</button>
                      </li>
                      <li class="nav-item" role="presentation">
                          <button class="nav-link" id="settings-tab{{ $facture->CODECLAS }}" data-bs-toggle="tab" data-bs-target="#settings{{ $facture->CODECLAS }}" type="button" role="tab" aria-controls="settings{{ $facture->CODECLAS }}" aria-selected="false">Paramètres</button>
                      </li>
                  </ul>
                  <div class="tab-content mt-3" id="myTabContent{{ $facture->CODECLAS }}">
                      <div class="tab-pane fade show active" id="scolarite{{ $facture->CODECLAS }}" role="tabpanel" aria-labelledby="scolarite-tab{{ $facture->CODECLAS }}">
                          <!-- Contenu de l'onglet Scolarité -->
                          <div class="row">
                              <div class="col">
                                  <div class="row">
                                      <div class="col-3">
                                          <p>Nom de la classe</p>
                                          <input type="text" class="form-control" name="CODECLAS" value="{{ $facture->CODECLAS }}" placeholder="CE1A">
                                      </div>
                                      <div class="col-3">
                                          <p>Libellé de la classe</p>
                                          <input type="text" class="form-control" name="LIBELCLAS" value="{{ $facture->LIBELCLAS }}" placeholder="CE1A">
                                      </div>
                                  </div>
                              </div>
                              <div class="col">
                                  <div class="row">
                                      <div class="col-4">
                                          <p>Scolarité Nouveau élève</p>
                                          <input type="text" class="form-control" style="border: 0.1px solid;" name="APAYER" value="{{ $facture->APAYER }}">
                                          <p>Frais 1</p>
                                          <input type="text" class="form-control" style="border: 0.1px solid;" name="FRAIS1" value="{{ $facture->FRAIS1 }}">
                                          <p>Frais 2</p>
                                          <input type="text" class="form-control" style="border: 0.1px solid;" name="FRAIS2" value="{{ $facture->FRAIS2 }}">
                                          <p>Frais 3</p>
                                          <input type="text" class="form-control" style="border: 0.1px solid;" name="FRAIS3" value="{{ $facture->FRAIS3 }}">
                                          <p>Frais 4</p>
                                          <input type="text" class="form-control" style="border: 0.1px solid;" name="FRAIS4" value="{{ $facture->FRAIS4 }}">
                                      </div>
                                      <div class=" col-4">
                                        <p>Scolarité Ancien élève</p>
                                        <div class=" mb">
                                            <input type="text" class="form-control" style="border: 0.1px solid;" placeholder="" name="APAYER2" value="{{$facture->APAYER2}}">
                                        </div>
                                        <div class=" mb" style="margin-top: 21px;">
                                            <input type="text" class="form-control" style="border: 0.1px solid;" placeholder="" name="FRAIS1_A" value="{{$facture->FRAIS1_A}}">
                                        </div>
                                        <div class=" mb" style="margin-top: 21px;">
                                            <input type="text" class="form-control" style="border: 0.1px solid;" placeholder="" name="FRAIS2_A" value="{{$facture->FRAIS2_A}}">
                                        </div>
                                        <div class=" mb" style="margin-top: 21px;">
                                            <input type="text" class="form-control" style="border: 0.1px solid;" placeholder="" name="FRAIS3_A" value="{{$facture->FRAIS3_A}}">
                                        </div>
                                        <div class=" mb" style="margin-top: 21px;">
                                            <input type="text" class="form-control" style="border: 0.1px solid;" placeholder="" name="FRAIS4_A" value="{{$facture->FRAIS4_A}}">
                                        </div>
                                    </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="tab-pane fade" id="echeancier{{ $facture->CODECLAS }}" role="tabpanel" aria-labelledby="echeancier-tab{{ $facture->CODECLAS }}">
                          <!-- Contenu de l'onglet Échéancier de paiement -->
                      </div>
                      <div class="tab-pane fade" id="messages{{ $facture->CODECLAS }}" role="tabpanel" aria-labelledby="messages-tab{{ $facture->CODECLAS }}">
                          <!-- Contenu de l'onglet Messages -->
                      </div>
                      <div class="tab-pane fade" id="settings{{ $facture->CODECLAS }}" role="tabpanel" aria-labelledby="settings-tab{{ $facture->CODECLAS }}">
                          <!-- Contenu de l'onglet Paramètres -->
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Valider & Fermer</button>
              </div>
          </div>
      </div>
  </div>
  @endforeach

  
  <style>
    body {
        font-family: Arial, sans-serif;
    }
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    th, td {
        padding: 8px 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    @media (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        th {
            top: auto;
            background-color: transparent;
        }
        tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }
        td {
            text-align: right;
            position: relative;
            padding-left: 50%;
        }
        td::before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 12px;
            font-weight: bold;
            text-align: left;
            background-color: #f4f4f4;
            border-right: 1px solid #ddd;
        }
    }
</style>
