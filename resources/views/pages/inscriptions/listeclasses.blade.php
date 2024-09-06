{{-- @extends('layouts.master')
@section('content') --}}
<body onload="window.print()">
    <div class="container-fluid d-flex  align-items-center justify-content-center" >
        <div id="contenu">
            <h2>Liste des Classes</h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Classes</th>
                    <th scope="col">Libellé Classe</th>
                    <th scope="col">Effectif</th>
                    <th scope="col">Promotion</th>
                    <th scope="col">Scolarité</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>MAT1</td>
                    <td>Section Petits</td>
                    <td>13</td>
                    <td>Section Petits</td>
                    <td>10000</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>MAT2</td>
                    <td>Section Moyens</td>
                    <td>23</td>
                    <td>Section Moyens</td>
                    <td>10000</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td scope="row">MAT2II</td>
                    <td>Section Moyens</td>
                    <td>20</td>
                    <td>Section Moyens</td>
                    <td>10000</td>
                    </tr>
                </tbody>
            </table>
            </div>
            </div>
            </div>
            </div>
            
        </div>
    </div>
</body>
{{-- @endsection --}}

<style>
    .card-body {
        padding: 2rem;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    table {
        width: 100%; /* Utiliser toute la largeur disponible */
        border-collapse: collapse;   
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

/*     th {
        background-color: #6f42c1;
        color: white;
    } */

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    
    tr:hover {
        background-color: #ddd;
    }

</style>
