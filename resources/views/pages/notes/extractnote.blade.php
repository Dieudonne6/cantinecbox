@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Extraction des Notes</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>MATRICULE</th>
                <th>NOM et PRENOM</th>
                <th>Moyenne Interro</th>
                <th>DEV1</th>
                <th>DEV2</th>
                <th>DEV3</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes->groupBy('MATRICULE') as $matricule => $studentNotes)
                @php
                    // Calcul de la moyenne des interros pour cet élève
                    $totalInterro = $studentNotes->sum('interro');
                    $countInterro = $studentNotes->count();
                    $moyenneInterro = $countInterro ? number_format($totalInterro / $countInterro, 2) : 0;
                    // On suppose que DEV1, DEV2 et DEV3 sont identiques pour chaque enregistrement d'un élève
                    $firstNote = $studentNotes->first();
                @endphp
                <tr>
                    <td>{{ $matricule }}</td>
                    <td>{{ $firstNote->eleve->NOM .' '. $firstNote->eleve->PRENOM}}</td>
                    {{-- <td>{{ $moyenneInterro }}</td> --}}
                    <td>{{ $firstNote->MI }}</td>
                    <td>{{ $firstNote->DEV1 }}</td>
                    <td>{{ $firstNote->DEV2 }}</td>
                    <td>{{ $firstNote->DEV3 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
