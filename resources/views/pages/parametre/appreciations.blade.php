@extends('layouts.master')
@section('content')

<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h5>Grille des appréciations</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('appreciations.update') }}" method="POST">
        @csrf
        @if(session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

          @for ($i = 1; $i <= 8; $i++)
            <div class="col-md-6">
              <div class="border p-3 rounded">
                <h6 class="text-primary mb-3">Moyenne &lt; Borne {{ $i }}</h6>

                <div class="mb-2 d-flex align-items-center">
                  <label class="me-2 text-end" style="width: 180px;">Borne {{ $i }} :</label>
                  <input type="text" class="form-control" name="Borne{{ $i }}" value="{{ $appreciations->{'Borne' . $i} }}">
                </div>

                <div class="mb-2 d-flex align-items-center">
                  <label class="me-2 text-end" style="width: 180px;">Appréciation Professeur :</label>
                  <input type="text" class="form-control" name="Mention{{ $i }}p" value="{{ $appreciations->{'Mention' . $i . 'p'} }}">
                </div>

                <div class="d-flex align-items-center">
                  <label class="me-2 text-end" style="width: 180px;">Appréciation Directeur :</label>
                  <input type="text" class="form-control" name="Mention{{ $i }}d" value="{{ $appreciations->{'Mention' . $i . 'd'} }}">
                </div>
              </div>
            </div>
          @endfor

          <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Valider</button>
            <a href="{{ route('parametre.tables') }}" class="btn btn-secondary">Annuler</a>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>
</br>
</br>

@endsection
