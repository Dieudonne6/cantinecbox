@extends('layouts.master')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card">
        <div class="card-body">
          <form class="accordion-body">
            <div class="row"> 
              <div class="col-lg-4">
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Nom">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Prenom">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control" id="lieu" placeholder="Classe">
                </div>
                <div class="mb-2">
                  <select class="form-control">
                    <option>Type d'eleve</option>
                    <option>Ancien</option>
                    <option>Nouveau</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <h5>Donnée classe</h5>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>

              </div>
              <div class="col-lg-4">
                <h5>Donnée eleve</h5>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
                <div class="mb-2">
                  <input type="" class="form-control"  placeholder="900">
                </div>
               
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4">
                <h6>Arriere (initial a payer)</h6>
              </div>
              <div class="col-lg-4">
                <input type="" class="form-control"  placeholder="900">
              </div>
              <div class="col-lg-4">
                <input type="" class="form-control"  placeholder="900">
              </div>
            </div>
            <div class="row my-3">
              <div class="col-lg-8">
                <select class="form-control">
                  <option><div>Plein</div><div>90</div><div>78</div><div>56</div></option>
                  <option><div>Plein</div><div>90</div><div>78</div><div>56</div></option>

                </select>
                <a href="Cree">Creer un nouveau profil de reduction</a>
              </div>
              <div class="col-lg-4">
                <button type="submit" class="btn btn-primary mb-2">Sauvegarde</button>
                <button type="submit" class="btn btn-primary">Aider</button>

              </div>

            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
</div>

@endsection