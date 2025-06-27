@extends('layouts.master')

@section('content')

<div class="card">

    <div class="row ">
        <div class="col">
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
                    <h4 class="card-title">Duplicata reçu</h4>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-primary">Imprimer tout</button>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>N° reçu</th>
                                            <th>Date reçu</th>
                                            <th>Scolarité</th>
                                            <th>Arriéré</th>
                                            @foreach($params2 as $param)
                                            <th>{{ $param->LIBELF1 }}</th>
                                            <th>{{ $param->LIBELF2 }}</th>
                                            <th>{{ $param->LIBELF3 }}</th>
                                            <th>{{ $param->LIBELF4 }}</th>
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($factures as $facture)
                                          <tr>
                                            {{-- N° reçu --}}
                                            <td>{{ $facture->id }}</td>
                                      
                                            {{-- Date (sans l’heure) --}}
                                            <td>
                                              {{ \Illuminate\Support\Carbon::parse($facture->date_time)
                                                   ->toDateString() }}
                                            </td>
                                      
                                            {{-- Scolarité (colonne dédiée dans ta table FactureScolarit) --}}
                                            
                                            {{-- Pour chaque libellé défini dans Params2, on cherche dans le JSON Itemfacture --}}
                                            @php
                                              // Transformer le JSON en collection Laravel pour simplifier la recherche
                                              $items = collect(json_decode($facture->itemfacture, true));
                                              $scolarite = "Scolarité";
                                              $arriere = "Arriéré";
                                            @endphp
                                      
                                            <td>
                                                {{ optional(
                                                    $items->firstWhere('name', $scolarite)
                                                  )['price'] ?? 0 }}
                                            </td>
                                            <td>
                                                {{ optional(
                                                    $items->firstWhere('name', $arriere)
                                                  )['price'] ?? 0 }}
                                            </td>
                                            @foreach($params2 as $param)
                                              <td>
                                                {{-- LIBELF1 --}}
                                                {{ optional(
                                                    $items->firstWhere('name', $param->LIBELF1)
                                                  )['price'] ?? 0 }}
                                              </td>
                                              <td>
                                                {{-- LIBELF2 --}}
                                                {{ optional(
                                                    $items->firstWhere('name', $param->LIBELF2)
                                                  )['price'] ?? 0 }}
                                              </td>
                                              <td>
                                                {{-- LIBELF3 --}}
                                                {{ optional(
                                                    $items->firstWhere('name', $param->LIBELF3)
                                                  )['price'] ?? 0 }}
                                              </td>
                                              <td>
                                                {{-- LIBELF4 --}}
                                                {{ optional(
                                                    $items->firstWhere('name', $param->LIBELF4)
                                                  )['price'] ?? 0 }}
                                              </td>
                                            @endforeach
                                      
                                            {{-- Colonne Action --}}
                                              <td>
                                                  <a href="#" class="btn btn-secondary btn-sm mb-1">
                                                      <i class="">Imprimer</i> 
                                                  </a>
                                              </td>
                                          </tr>
                                        @endforeach
                                      </tbody>
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