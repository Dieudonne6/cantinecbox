@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edition des Bulletins de notes</h4>
        <div class="row">
          <div class="col-md-3" style="border-right: 1px solid #000000 !important; width: 50% !important;">
            <!-- Contenu de la première colonne -->
            <div class="row" style="background-color: #ffa600; border-radius: 8px; width: 75%;">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paramOption" id="calculMoyennes">
                    <label class="form-check-label" for="calculMoyennes">
                        Calcul des moyennes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paramOption" id="editionBulletin">
                    <label class="form-check-label" for="editionBulletin">
                        Édition Bulletin
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paramOption" id="listeMerite">
                    <label class="form-check-label" for="listeMerite">
                        Liste par ordre de mérite
                    </label>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>Sélection</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paramselection" id="parclasse" checked>
                    <label class="form-check-label" for="parclasse">
                        Par classe
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paramselection" id="pareleve">
                    <label class="form-check-label" for="pareleve">
                        Par élève
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="archive" id="archive">
                    <label class="form-check-label" for="archive">
                        Archive pdf automatique
                    </label>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>Signature</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="signature" id="ccc" checked>
                    <label class="form-check-label" for="ccc">
                        CCC
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="signature" id="directeur">
                    <label class="form-check-label" for="directeur">
                        Directeur 2
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="signature" id="cc">
                    <label class="form-check-label" for="cc">
                        CC
                    </label>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>Bonus</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="bonus" id="tous" checked>
                    <label class="form-check-label" for="tous">
                        Tous
                    </label>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>Système de bonification</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bonification" id="integral">
                    <label class="form-check-label" for="integral">
                        Intégral
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bonification" id="intervalle" checked>
                    <label class="form-check-label" for="intervalle">
                        Intervalle
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="bonification" id="none">
                    <label class="form-check-label" for="none">
                        Aucun
                    </label>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="range0" name="range0" value="0">
                    <p style="margin-top: 10px;">--</p>
                    <input type="text" class="form-control" id="range1" name="range1" value="11">
                    <p style="margin-top: 10px;">--></p>
                    <input type="number" class="form-control" id="value1" name="value1" value="1.00" step="1">
                </div>
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="range2" name="range2" value="11">
                    <p style="margin-top: 10px;">--</p>
                    <input type="number" class="form-control" id="value2" name="value2" value="12">
                    <p style="margin-top: 10px;">--></p>
                    <input type="number" class="form-control" id="value2" name="value2" value="2.00" step="1">
                </div>
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="range3" name="range3" value="12">
                    <p style="margin-top: 10px;">--</p>
                    <input type="number" class="form-control" id="value3" name="value3" value="14">
                    <p style="margin-top: 10px;">--></p>
                    <input type="number" class="form-control" id="value3" name="value3" value="2.00" step="1">
                </div>
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="range4" name="range4" value="14">
                    <p style="margin-top: 10px;">--</p>
                    <input type="number" class="form-control" id="value4" name="value4" value="3.00" step="1">
                    <p style="margin-top: 10px;">--></p>
                    <input type="number" class="form-control" id="value4" name="value4" value="3.00" step="1">
                </div>
                <div class="form-group d-flex">
                    <input type="text" class="form-control" id="range5" name="range5" value="16">
                    <p style="margin-top: 10px;">--</p>
                    <input type="number" class="form-control" id="value5" name="value5" value="4.00" step="1">
                    <p style="margin-top: 10px;">--></p>
                    <input type="number" class="form-control" id="value5" name="value5" value="4.00" step="1">
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="effectif">Effectif en cours</label>
                    <input type="number" class="form-control" id="effectif" name="effectif" value="25" readonly>
                </div>
            </div>
          </div>
          <div class="col-md-4" style="border-right: 1px solid #000000 !important;">
            <!-- Contenu de la deuxième colonne -->
          </div>
          <div class="col-md-4">
            <!-- Contenu de la troisième colonne -->
          </div>
        </div>
      </div>
    </div>
</div>

<style>
  .form-check {
    margin-left: 45px !important;
  }
  .footer {
    display: none;
  }
  .form-control {
    width: 30% !important;
  }
</style>
@endsection