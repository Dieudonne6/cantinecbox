@extends('layouts.master')

@section('content')

<style>
    /* Responsive styles */
    @media (max-width: 1200px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 0.5rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .card-title {
            font-size: 1rem;
        }
        
        .container {
            padding: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .col-md-5 {
            max-width: 100%;
            margin: 0 0.25rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .card-title {
            font-size: 0.9rem;
        }
        
        .container {
            padding: 0.25rem;
        }
        
        .form-control {
            font-size: 0.9rem;
            padding: 0.4rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            margin-bottom: 0.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="col-md-5 container d-flex justify-content-center align-items-center">
    <div class="card w-100">
      <div class="card-body">
        <h4 class="card-title mb-3">Modifier le profil</h4>
        {{-- Afficher erreurs de validation --}}
        @if($errors->any())
            <div id="statusAlert" class="alert alert-danger">
                <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input id="login" name="login" type="texte" class="form-control" required value="{{ Session::get('account')['login'] }}">
                {{-- @error('current_password')
                    <div id="statusAlert" class="invalid-feedback">{{ $message }}</div>
                @enderror --}}
            </div>

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input id="nom" name="nom" type="texte" class="form-control" value="{{ Session::get('account')['nomuser'] }}">
                {{-- @error('new_password')
                    <div id="statusAlert" class="invalid-feedback">{{ $message }}</div>
                @enderror --}}
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prenom</label>
                <input id="prenom" name="prenom" type="texte" class="form-control" value="{{ Session::get('account')['prenomuser'] }}">
            </div>

            <div>
                <button type="submit" class="btn btn-primary px-3">Modifier</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">Annuler</a>
            </div>

        </form>

      </div>
    </div>
</div>
<br>
@endsection
