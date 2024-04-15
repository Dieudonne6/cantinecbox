@extends('layouts.master')
@section('content')

<div class="main-panel-16">        
  <div class="content-wrapper">
    <div class="row">

<div class="col-md-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title" _msttexthash="323960" _msthash="106">Connexion Scodelux</h4>
      <form class="forms-sample">
        <div class="form-group row">
          <label for="exampleInputServeur" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="108">Serveur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputServeur" placeholder="localhost" _mstplaceholder="113997" _msthash="109">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputDatabase" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="110">Name Base</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputDatabase" placeholder="Database" _mstplaceholder="58058" _msthash="111">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116">Utilisateur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputUsername" placeholder="Username" _mstplaceholder="117572" _msthash="115">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputPassword" class="col-sm-3 col-form-label" _msttexthash="157794" _msthash="114">Mot de passe</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" id="exampleInputPassword" placeholder="Mot de passe" _mstplaceholder="117572" _msthash="117">
          </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2" _msttexthash="98280" _msthash="118">Tester</button>
      </form>
    </div>
  </div>
</div>

<div class="col-md grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title" _msttexthash="323960" _msthash="106">Connexion Cantine</h4>
      <form class="forms-sample">
        <div class="form-group row">
          <label for="exampleInputServeur" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="108">Serveur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputServeur" placeholder="localhost" _mstplaceholder="113997" _msthash="109">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputDatabase" class="col-sm-3 col-form-label" _msttexthash="564538" _msthash="110">Name Base</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputDatabase" placeholder="Database" _mstplaceholder="58058" _msthash="111">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116">Utilisateur</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="exampleInputUsername" placeholder="Username" _mstplaceholder="117572" _msthash="115">
          </div>
        </div>

        <div class="form-group row">
          <label for="exampleInputPassword" class="col-sm-3 col-form-label" _msttexthash="157794" _msthash="114">Mot de passe</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" id="exampleInputPassword" placeholder="Mot de passe" _mstplaceholder="117572" _msthash="117">
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
<button type="submit" class="btn btn-primary mr-2" _msttexthash="98280" _msthash="118">Enregistrer</button>


@endsection