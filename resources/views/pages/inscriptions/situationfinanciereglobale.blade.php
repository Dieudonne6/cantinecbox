@extends('layouts.master')

@section('content')

<div class="main-panel-10">
    <div class="content-wrapper">
        
      {{--  --}}
      <div class="row">          
        <div class="col-12">
          <div class="card mb-6">
            <div class="card-body">
              <h4 class="card-title">Situation globale</h4>
              <div class="row gy-3">
                <div class="demo-inline-spacing">

                  <button type="button" class="btn btn-primary">Créer</button>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Trier</button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Par nom</a></li>
                      <li><a class="dropdown-item" href="#">Par classe</a></li>
                    </ul>
                  <button type="button" class="btn btn-primary">Imprimer</button>    

                </div>
              </div>
            </div>
          </div>
        </div>       
      </div>

      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>MATRICULE</th>
                                        <th>NOM ET PRENOMS</th>
                                        <th>CLASSE</th>
                                        <th>APAYER</th>
                                        <th>PAYE</th>
                                        <th>RESTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>00000844</td>
                                            <td>ABOGOURIN Mardiath</td>
                                            <td>CE1</td>
                                            <td>190000</td>
                                            <td>187000</td>
                                            <td>3000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td>190000</td>
                                            <td>187000</td>
                                            <td>3000</td>
                                        </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>



@endsection