@extends('layouts.master')
@section('content')
<div class="main-panel">        
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-10 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            @if(Session::has('status'))
            <div id="statusAlert" class="alert alert-succes btn-primary">
            {{ Session::get('status')}}
            </div>
            @endif
            <h4 class="card-title">Enregistrement d'un utilisateur</h4>
          
            <form action="{{ url('/enregistreruser') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="exampleInputUsername1">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="login" id="exampleInputUsername1" placeholder="Nom d'utilisateur">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Nom</label>
                <input type="text" class="form-control" name="nom" id="exampleInputEmail1" placeholder="Nom">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Prenom</label>
                <input type="text" class="form-control" name="prenom" id="exampleInputPassword1" placeholder="Prenom">
              </div>
              <div class="form-group">
                <label for="exampleInputConfirmPassword1">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="exampleInputConfirmPassword1" placeholder="Mot de passe">
              </div>
            
              <button type="submit" class="btn btn-primary mr-2">Envoyer</button>
              <button class="btn btn-light">Annuler</button>
            </form>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
@endsection