@extends('layouts.master')
@section('content')

<div class="card">
  <div class="card-header position-relative">
    <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
      <i class="fas fa-arrow-left"></i> Retour
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="card-body">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col">
                <button class="btn btn-link text-danger btn-outline-danger fw-bold">Passer en classe supérieure</button>
            </div>
            <div class="col">
                <button class="btn btn-link text-danger btn-outline-danger fw-bold" >  Réinitialiser les classes</button>
            </div>
            <div class="col">
                <button class="btn btn-link text-danger btn-outline-danger fw-bold">Supprimer les sans classes</button>
            </div>
             <div class="col">
                <button class="btn btn-link text-danger btn-outline-danger fw-bold">Clôturer l'année</button>
            </div>

        </div>
    </div>
  </div>
</div>

<style>
  .btn-arrow {
    position: absolute;
    top: 5px;
    left: 5px;
    background-color: transparent !important;
    border: none !important;
    text-transform: uppercase !important;
    font-weight: bold !important;
    cursor: pointer !important;
    font-size: 17px !important;
    color: #b51818 !important;
  }

  .btn-arrow:hover {
    color: #b700ff !important;
  }
</style>

@endsection
