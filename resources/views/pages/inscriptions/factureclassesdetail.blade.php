@extends('layouts.master')
@section('content')


<style>

    a:hover {
        text-decoration: none !important;
    }

    .form-control{
        padding: 0 !important;
        height: 2rem;
        margin-left: 1rem;
    }

    .form-check .form-check-input {
    margin-left: 0  !important;
}
</style>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        @if (Session::has('status'))
        <div id="statusAlert" class="alert alert-success btn-primary">
          {{ Session::get('status') }}
        </div>
        @endif
        <h4 class="card-title">Enregistrement des classes</h4>

        <div class="col-12">

            <div class="row">

                <div class="col-6">
                    <div class="row">

                        {{-- <div class="form-group row mt-3">
                            <div class="col-md-6">
                            <label for="adresses-parents">Adresses parents</label>
                            <input type="text" id="adresses-parents" name="adressesParents" class="form-control">
                            </div>
                            <div class="col-md-6">
                            <label for="autres-renseignements">Autres renseignements</label>
                            <input type="text" id="autres-renseignements" name="autresRenseignements" class="form-control">
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                <label for="classe" class="mr-2">Nom classe</label>
                                <input class="form-control" type="text" name="classe" id="classe" value="{{ $CODECLAS }}" style="width: 110px" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                {{-- <label for="prenom_mere" class="mr-2">Prénom</label> --}}
                                <input class="form-control" type="text" name="classe" id="classe" value="{{ $CODECLAS }}" style="width: 150px" readonly>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <form action="{{ url('detailfacclasse/'.$CODECLAS) }}" method="post">
                        @csrf
                    <div class="row">
                        <h5 style="margin-left: 0.5rem;">Nouveaux</h5>
                        <h5 style="margin-top: -1.8rem; margin-left: 15rem;">Anciens</h5>
                            
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="APAYER" id="classe"  style="width: 40%;" value="{{ $donneClasse->APAYER }}">
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="APAYER2" id="classe"  style="width: 40%;" value="{{ $donneClasse->APAYER2 }}">

                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS1" id="classe"  style="width: 40%" value="{{ $donneClasse->FRAIS1 }}">
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS1_A" id="classe"  style="width: 40%;" value="{{ $donneClasse->FRAIS1_A }}">

                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS2" id="classe"  style="width: 40%" value="{{ $donneClasse->FRAIS2 }}">
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS2_A" id="classe"  style="width: 40%;" value="{{ $donneClasse->FRAIS2_A }}">

                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS3" id="classe"  style="width: 40%" value="{{ $donneClasse->FRAIS3 }}">
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS3_A" id="classe"  style="width: 40%;" value="{{ $donneClasse->FRAIS3_A }}">

                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS4" id="classe"  style="width: 40%" value="{{ $donneClasse->FRAIS4 }}">
                        <input class="form-control mb-2" type="number"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="FRAIS4_A" id="classe"  style="width: 40%;" value="{{ $donneClasse->FRAIS4_A }}">

                    </div></br>
                    <input class="btn-sm btn-primary" type="submit" style="margin-left: 22.4rem;" value="Valider">

                    </form>

                </div>
                <div class="col-6">
                    <h5>Tableau de l'echeancier de paiement</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                        L'echeancier prend en compte les frais de scolarite seulement
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                        L'echeancier prend en compte tous les frais[] et []
                        </label>
                    </div>
                

                    <div class="form-group">
                        <div class="row">
                        {{-- <div class="col"> --}}
                            <div class="d-flex align-items-center">
                            <label for="classe" class="mr-2">Nb. echeancie</label>
                            <input class="form-control" type="text" name="classe" id="classe" value="" style="width: 90px">
                            </div>
                        {{-- </div> --}}
                        {{-- <div class="col"> --}}
                            <div class="d-flex align-items-center">
                            <label for="prenom_mere" class="mr-2">Date de debut de paiement</label>
                            <input class="form-control" type="date" name="classe" id="classe" value="" style="width: 150px">
                            </div>
                        {{-- </div> --}}
                        <div class="d-flex align-items-center">
                            <label for="classe" class="mr-2">Periodicite</label>
                            <input class="form-control" type="text" name="classe" id="classe" value="" style="width: 28px">
                            <p class="ml-2">Mois <span class="ml-3" > >= 7 pour exprimer en jours</span></p>
                        </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow: auto;">
                    <table class="table table-striped" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th class="text-center">Tranche</th>
                                <th class="text-center">% nouveau</th>
                                <th class="text-center">% ancien</th>                    
                                <th class="text-center">Montant</th>
                                <th class="text-center">Montant2</th>                    
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>10.56%</td>
                                <td>19.30%</td>
                                <td>60 000</td>
                                <td>90 000</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>10.56%</td>
                                <td>19.30%</td>
                                <td>60 000</td>
                                <td>90 000</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                </div>

            </div><br>

            <hr>

            <div class="row">
                <div class="col-5">
                    <h5 style=" margin-left:2rem;">Nouveau eleves</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th >No</th>
                                    <th >Date paie</th>
                                    <th >Montant</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>24/10/2023</td>
                                    <td>90 000</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>24/10/2023</td>
                                    <td>90 000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-5" style="margin-left: 8rem">
                    <h5 style="margin-left:2rem;">Ancien eleves</h5>
                    <div class="table-responsive mb-4">
                    <table class="table table-striped" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th >No</th>
                                <th >Date paie</th>
                                <th >Montant</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>24/10/2023</td>
                                <td>90 000</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>24/10/2023</td>
                                <td>90 000</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            {{-- <div class="row"> --}}

                <a class="btn-sm btn-primary" href="" style="margin-bottom: 2rem; float: right">Creer</a>
            {{-- </div> --}}






        </div>
    </div>
    </div>
</div>

@endsection
