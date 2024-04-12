@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      <div class="form-group">
        {{-- <select class="js-example-basic-single w-100">
          @foreach ($classe as $classes)
            <option value="{{$classes->CODECLAS}}"><a href="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</a></option>
          @endforeach
        </select> --}}
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
            @foreach ($filterEleve as $filterEleves)
              <tr>
                <td>
                  {{$filterEleves->CODECLAS}}
                </td>
                <td>
                  {{$filterEleves->NOM}} {{$filterEleves->PRENOM}}
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
