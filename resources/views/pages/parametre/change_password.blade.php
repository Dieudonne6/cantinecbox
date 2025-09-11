@extends('layouts.master')

@section('content')

<div class="col-md-5 container d-flex justify-content-center align-items-center">
    <div class="card w-100">
      <div class="card-body">
        <h4 class="card-title mb-3">Modifier le mot de passe</h4>
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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" required>
                @error('current_password')
                    <div id="statusAlert" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input id="new_password" name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" required>
                @error('new_password')
                    <div id="statusAlert" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control" required>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Annuler</a>
                <button type="submit" class="btn btn-primary btn-sm px-3">Enregistrer et se déconnecter</button>
            </div>

        </form>

      </div>
    </div>
</div>
<br>
@endsection
