@extends('layouts.master')
@section('content')


<div class="col-100 grid-margin">
    <div class="card">
      <div class="card-body">
        
        <form class="form-sample">
          
         
         
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Changement de période</h4>
                <p class="card-description">
                  Le <code>chargement de période</code>par ce menu s'impose à toute nouvelle connexion d'un poste quelconque du réseau
                </p>
                <form class="form-inline">
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="form-check mx-sm-2">Période</label>
                            <div class="col-sm-9">
                              <select class="form-control">
                                <option _msttexthash="125775" _msthash="231">Période 1</option>
                                <option _msttexthash="125970" _msthash="232">Période 2</option>
                                <option _msttexthash="126165" _msthash="233">Période 3</option>
                                <option _msttexthash="126360" _msthash="234">Période 4</option>
                                <option _msttexthash="126360" _msthash="235">Période 5</option>
                                <option _msttexthash="126360" _msthash="236">Période 6</option>
                                <option _msttexthash="126360" _msthash="237">Période 7</option>
                              </select>
                            </div>
                          </div>
                        </div>            
                    </div>           
                  <button type="submit" class="btn btn-success mb-2">Enrégistré</button>
                  <button type="submit" class="btn btn-danger mb-2">Suprimé</button>
                </form>
              </div>
            </div>
          </div> 
        </form>
      </div>
    </div>
</div>

@endsection