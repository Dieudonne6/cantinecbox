@extends('layouts.master')
@section('content')
<div class="main-panel-16">        
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-6 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="form-group d-flex">
              <div class="form-check pr-4">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" checked>
                  EMECEF
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1">
                  MCF
                </label>
              </div>
            </div>
            @if(Session::has('status'))
              <div id="statusAlert" class="alert alert-succes btn-primary">
                {{ Session::get('status')}}
              </div>
            @endif
            <form action="{{url('paramsemecef')}}" method="POST"  enctype="multipart/form-data" id="div1" class="d-block">
              {{csrf_field()}}
              <h4 class="card-title">Informations Emecef</h4>

              <div class="form-group">
                <label for="exampleInputUsername1">IFU</label>
                <input type="text" class="form-control" name="ifu" id="exampleInputUsername1" placeholder="IFU">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Token</label>
                <input type="text" class="form-control" name="token" id="exampleInputEmail1" placeholder="Token">
              </div>
              <div class="form-group row">
                <label for="exampleInputUsername1">Groupe de taxe</label>
                <input type="text" class="form-control" name="taxe"  id="exampleInputUsername1" placeholder="Groupe de taxe">
              </div>
              <div class="form-group row">
                <label for="exampleInputUsername1">Type de facture</label>
                <input type="text" class="form-control" name="type" id="exampleInputUsername1" placeholder="Type de facture">
              </div>
              <div class="form-group row">
                <label for="exampleInputUsername1">LOGO</label>
                <input type="file" class="form-control" name="logo" id="exampleInputUsername1" placeholder="Logo">
              </div>
              <button type="submit" class="btn btn-primary mr-2">Envoyer</button>
              <button class="btn btn-light">Annuler</button>
            </form>
            <form class="forms-sample d-none" id="div2">
              <h4 class="card-title">Informations Mcef</h4>
              <div class="form-group">
                <label for="exampleInputUsername1">IFU</label>
                <input type="text" class="form-control" id="exampleInputUsername1" placeholder="IFU">
              </div>
              <button type="submit" class="btn btn-primary mr-2">Envoyer</button>
              <button class="btn btn-light">Annuler</button>
            </form>
          </div>
        </div>
      </div>
     
      {{-- <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
           
          </div>
        </div>
      </div> --}}
    </div>
  </div>
</div>
@endsection