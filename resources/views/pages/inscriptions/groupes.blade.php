@extends('layouts.master')
@section('content')

<div class="main-panel-10">
    <div class="card">
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #f4f4f4;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
        </style>
    
        <h3 style="text-align: center">Liste des Groupes</h3>
    
        <table id="classTable">
        <thead>
            <tr>
                <th>Groupes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ens. Géneral</td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifgroup">
                        Modifier
                      </button>
                    <button type="button" class="btn btn-danger">Supprimer</button>
                </td>
            </tr>
            <tr>
                <td>Ens. Primaire</td>
                <td>
                    <button type="button" class="btn btn-primary">Modifier</button>
                    <button type="button" class="btn btn-danger">Supprimer</button>
                </td>
            </tr>
            <tr>
                <td>Ens. Maternelle</td>
                <td>
                    <button type="button" class="btn btn-primary">Modifier</button>
                    <button type="button" class="btn btn-danger">Supprimer</button>
                </td>
            </tr>
        </tbody>
        </table>
    
        <script>
       function filterTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("classTable");
        tr = table.getElementsByTagName("tr");
    
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none"; // Hide all rows initially
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = ""; // Show the row if a match is found
                        break;
                    }
                }
            }
        }
       }
        </script>
    </div>
    
</div>

<!-- Button trigger modal -->

  
  <!-- Modal modification groupes-->
  <div class="modal fade" id="modifgroup" tabindex="-1" aria-labelledby="modifgroupLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close p-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="row">
                        <div class="form-group w-50 mb-6">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">
                            <select class="js-example-basic-multiple w-100 select2-hidden-accessible" id="classSelect" name="classes" tabindex="-1" aria-hidden="true">
                                <option value="">Sélectionner la classe</option>
                                @foreach(['CE1A', 'CE1B', 'CE1C', 'CE1S', 'CE2A', 'CE2B', 'CE2C', 'CE2S', 'CIA', 'CIB', 'CIC', 'CIS', 'CM1A', 'CM1B', 'CM1C', 'CM1S', 'CM2A', 'CM2B', 'CM2C', 'CM2S', 'CPA', 'CPB', 'CPC', 'CPS', 'MAT1', 'MAT2', 'MAT2II', 'MAT3', 'MAT3II', 'PREMATER'] as $classe)
                                    <option value="{{ $classe }}">{{ $classe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group w-50 mb-6">
                            <button type="button" class="btn btn-success">Ajouter une classe</button>
                        </div>
                    </div>

                    <table id="classTable">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['CE1A', 'CE1B', 'CE1C', 'CE1S', 'CE2A'] as $classe)
                            <tr>
                                <td>{{ $classe }}</td>
                                <td><button type="button" class="btn btn-danger">Supprimer</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection