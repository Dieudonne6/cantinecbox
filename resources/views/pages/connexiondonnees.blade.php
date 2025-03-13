@extends('layouts.master')
@section('content')

<div class="main-panel-16">        
  <div class="content-wrapper">

    <div class="row">

<div class="col-md grid-margin stretch-card">
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
      </style>
      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
      </button>   
      <br>
      <br>                                   
  </div>
    <div class="card-body">

      @if(Session::has('status'))
      <div  id="statusAlert" class="alert alert-succes btn-primary mb-4">
      {{ Session::get('status')}}
      </div>
      @endif
      <h4 class="mb-5">Connexion à la base de donnée</h4>
      <form action="{{url('connexion')}}" method="POST">
        {{csrf_field()}}
        <div class="form-group row">
          <label for="exampleInputServeur" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="108">Serveur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputServeur" name="nameserveur" placeholder="localhost" _mstplaceholder="113997" _msthash="109">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputDatabase" class="col-sm-3 col-form-label" _msttexthash="564538"  _msthash="110">Base de donnée</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputDatabase" name="namebase" placeholder="Nom de la base de donnée" _mstplaceholder="58058" _msthash="111">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116">Utilisateur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputUsername" name="user"  placeholder="Utilisateur" _mstplaceholder="117572" _msthash="115">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputPassword" class="col-sm-3 col-form-label" _msttexthash="157794" _msthash="114">Mot de passe</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" id="exampleInputPassword" name="password" placeholder="Mot de passe" _mstplaceholder="117572" _msthash="117">
          </div>
        </div>
        <button type="submit" class="btn btn-primary mr-2" _msttexthash="98280" _msthash="118">Tester</button>
      </form>
    </div>
  </div>
</div>

    </div>
  </div>
</div>


@endsection