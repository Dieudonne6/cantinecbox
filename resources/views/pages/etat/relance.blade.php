@extends('layouts.master')
@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Résultats de la Relance</title>
    <style>
        .page {
            page-break-after: always;
        }
        .header, .content {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    @if (count($results) > 0)
        @foreach ($results as $result)
            <div class="page">
                <div class="header">
                    <h2>Élève Matricule: {{ $result['MATRICULE'] }}</h2>
                </div>
                <div class="content">
                    <p>Mois impayés: {{ implode(', ', $result['mois_impayes']) }}</p>
                </div>
            </div>
        @endforeach
    @else
        <p>Aucun élève avec des contrats impayés pour les mois précédents la date sélectionnée.</p>
    @endif

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

@endsection

