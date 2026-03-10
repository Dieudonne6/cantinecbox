@extends('layouts.master')
@section('content')

<div class="card">
  <div class="card-body">

    <h6>Prévisualisation import — Classe {{ $classe }} / Période {{ $periode }}</h6>
    <form action="{{ route('notes.saveImport') }}" method="POST">
        @csrf
        <input type="hidden" name="classe" value="{{ $classe }}">
        <input type="hidden" name="periode" value="{{ $periode }}">
        <input type="hidden" name="data" value="{{ base64_encode(json_encode($matieresData)) }}">

        <button type="submit" class="btn btn-primary mt-3">
            Valider l'importation
        </button>
    </form>
   
    <ul class="nav nav-tabs mb-3" id="matTab">
        @foreach($matieresData as $matiere => $rows)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                   data-toggle="tab"
                   href="#tab{{ $loop->index }}">
                    {{ $matiere }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($matieresData as $matiere => $rows)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
             id="tab{{ $loop->index }}">

         <h5 class="mb-3">Matière : {{ $matiere }}</h5> 

            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>MATRICULE</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Moyenne Interro</th>
                        <th>DEV1</th>
                        <th>DEV2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                    <tr>
                        <td>{{ $r['matricule'] }}</td>
                        <td>{{ $r['nom'] }}</td>
                        <td>{{ $r['prenom'] }}</td>
                        <td>{{ $r['moyenne'] }}</td>
                        <td>{{ $r['dev1'] }}</td>
                        <td>{{ $r['dev2'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

        </div>
        @endforeach
    </div>

  </div>
</div>

@endsection