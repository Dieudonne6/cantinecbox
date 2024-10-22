@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h3>Mise à jour des matières</h3>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal">
        Nouveau      
      </button>
      <table class="table">
        <thead>
          <tr>
            <th>
              Code
            </th>
            <th>
              Libelle matiere
            </th>
            <th>Nom court</th>
            <th>Type matière</th>
            <th>Mat_ligne</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          
            @foreach ($matiere as $mat)
            <tr>
              <td>  {{$mat->CODEMAT}}</td>
              <td>  {{$mat->LIBELMAT}}</td>
              <td>  {{$mat->NOMCOURT}}</td>
              <td>  {{$mat->CODEMAT}}</td>
              <td>  {{$mat->CODEMAT_LIGNE}}</td>
              <td><!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Modifier      </button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        ...
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach

          
        </tbody>
      </table>
    </div>
    <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div>
                <label>Code matiere</label>
                <input type="text" placeholder="0" class="form-control">
              </div>
              <div>
                <label>Libelle matiere</label>
                <input type="text" placeholder="0"  class="form-control">
              </div>
              <div>
                <label> Nom court</label>
                <input type="text" placeholder="0"  class="form-control">
              </div>
              
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>
@endsection
