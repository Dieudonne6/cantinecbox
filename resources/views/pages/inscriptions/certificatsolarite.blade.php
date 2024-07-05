@extends('layouts.master')
@section('content')

<style>
    table {
        table-layout: fixed;
    }
    .fixed-size {
        width: 200px;
        height: 50px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        border: 1px solid black; /* Pour voir les limites de la cellule */
    }
</style>

<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Certificat de scolarite</h4>
      @if(Session::has('status'))
        <div id="statusAlert" class="alert alert-succes btn-primary">
          {{ Session::get('status')}}
        </div>
      @endif
            @if(Session::has('erreur'))
        <div id="statusAlert" class="alert alert-danger btn-primary">
          {{ Session::get('erreur')}}
        </div>
      @endif
      <div class="form-group row">
        <div class="col-3">
          <select class="js-example-basic-multiple w-100" onchange="window.location.href=this.value">
            <option value="">Sélectionnez une classe</option>
            {{-- @foreach ($classe as $classes)
              <option value="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
            @endforeach --}}
          </select>
        </div>
        {{-- <div class="col-3">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nouveau Contrat
          </button>
        </div> --}}

        {{-- <div class="col-3">
          <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleInscrire">
            Inscriptions mensuelles
          </button>
        </div> --}}

      </div>
      <div class="table-responsive mb-4">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>
                Matricule
              </th>
             
              <th>Nom </th>

              <th>
                Prenom
              </th>

              <th>
                Date nais
              </th>

              <th>
                Assidute
              </th>

              <th>
                Conduite
              </th>
              <th>
                Travail
              </th>
              <th >
                Observation
              </th>
              <th>
                Action
              </th>
            </tr>
          </thead>
          <tbody id="eleve-details">
            <tr class="something">
                <td>
                    001
                </td>
                <td>
                    coco
                </td>
                <td>
                    jojo la ligue 1
                </td>
                <td>
                    01/01/2010
                </td>
                <td>
                    15
                </td>
                <td>
                    10
                </td>
                <td>
                    16
                </td>
                <td style="width: 150px; overflow: hidden;">
                    un eleve bipolaire  un eleve bipolaire  un eleve bipolaire 
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <!-- Button trigger modal -->
                        <button class="btn btn-danger p-2 btn-sm dropdown" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="typcn typcn-th-list btn-icon-append"></i>  
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3" style="">
                            <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#modifCertificatModal">Modifier</a></li>
                            <li><a class="dropdown-item" href="#">Imprimer</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            {{-- @foreach ($eleve as $index => $eleves)
                <tr class="eleve" data-id="{{ $eleves->id }}" data-nom="{{ $eleves->NOM }}" data-prenom="{{ $eleves->PRENOM }}" data-codeclas="{{ $eleves->CODECLAS }}">
                    <td>
                        {{$eleves->CODECLAS}}
                    </td>
                    <td>
                        {{$eleves->NOM}} {{$eleves->PRENOM}}
                    </td>
                    <td>
                      <a href='/paiementcontrat/{{$eleves->CODECLAS}}/{{$eleves->MATRICULE}}' class='btn btn-primary w-40'>Paiement</a>
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Suspendre</button> 
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              Êtes-vous sûr de vouloir supprimer ce contrat ?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                              <form action="{{ url('supprimercontrat/'.$eleves->MATRICULE)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger" value="Confirmer">
                              </form>  
                            </div>
                          </div>
                        </div>
                      </div>

                    </td>
                </tr>
            @endforeach --}}

        </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Button trigger modal -->

<div class="modal fade" id="modifCertificatModal" tabindex="-1" aria-labelledby="modifCertificatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifCertificatModalLabel">Modifier Certificat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenu du formulaire de modification -->
                <form id="modifCertificatForm">
                    <div class="form-group">
                        <label for="certificatNom">Assidute</label>
                        <input type="text" class="form-control" id="certificatNom" name="certificatNom" required>
                    </div>
                    <div class="form-group">
                        <label for="certificatNom">Conduite</label>
                        <input type="text" class="form-control" id="certificatNom" name="certificatNom" required>
                    </div>
                    <div class="form-group">
                        <label for="certificatNom">Travail</label>
                        <input type="text" class="form-control" id="certificatNom" name="certificatNom" required>
                    </div>
                    <div class="form-group">
                        <label for="certificatNom">Observation</label>
                        <input type="text" class="form-control" id="certificatNom" name="certificatNom" required>
                    </div>
                    <!-- Ajoutez d'autres champs ici selon vos besoins -->
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}


