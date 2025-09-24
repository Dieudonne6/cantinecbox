@extends('layouts.master')

@section('content')

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
