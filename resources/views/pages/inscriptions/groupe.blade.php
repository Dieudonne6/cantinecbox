@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                    <div class="card-body">
                    @if (session('success'))
                        <div id="statusAlert" class="alert alert-success btn-primary">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div id="statusAlert" class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <h4 class="card-title">Gestion des groupes</h4>

                    <form action="{{ url('/ajoutergroupe')}}" method="POST">
                        @csrf
                        <div class="form-group row"><br>
                            {{-- <p>Ajout d'un nouveau groupe</p> --}}
                            <label for="exampleInputUsername2" class="col-sm-2 col-form-label">Nouveau groupe</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="nomgroupe" name="nomgroupe" placeholder="Nom groupe">
                            </div>
                            <div class="col-sm-3">
                                {{-- <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Libelle"> --}}
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </div>
                    </form>


                    <div class="row justify-content-center">
                        <div class="col-8" style="text-align: center;">

                            <div class="table-responsive pt-3">
    
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Groupe</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                        @foreach ($allgroupes as $allgroupe)
                                            
                                        <tr>
                                            <td>{{ $allgroupe->LibelleGroupe }}</td>
                                            <td>
                                                <form action="/suppgroupe/{{ $allgroupe->id }}" method="POST"  onsubmit="return confirmDelete()" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
    
                        </div>
                    </div>
 


                </div>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete()
         {
            return confirm('Êtes-vous sûr de vouloir supprimer ce groupe?');
         }
    </script>
@endsection