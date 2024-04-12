@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      <div class="form-group">
        {{-- <select id="mySelect" class="js-example-basic-single w-100">
          
        </select> --}}
        @foreach ($classe as $classes)
          <a href="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</a>
        @endforeach
      </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>
                Classes
              </th>
              <th>
                Elève
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($eleve as $eleves)
              <tr>
                <td>
                  {{$eleves->CODECLAS}}
                </td>
                <td>
                  {{$eleves->NOM}} {{$eleves->PRENOM}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
