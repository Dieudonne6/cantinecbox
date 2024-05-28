@extends('layouts.master')
@section('content')

<div class="main-panel-16">        
  <div class="content-wrapper">
    @if(Session::has('status'))
      <div id="statusAlert" class="alert alert-succes btn-primary">
      {{ Session::get('status')}}
      </div>
    @endif
    @if($param)
      <form action="{{url('modifierfrais/'.$param->id_paramcontrat)}}" method="POST">
        {{csrf_field()}}
        @method('PUT')
        <input type="hidden" value="{{$param->id_paramcontrat}}" name="id_paramcontrat">
        <div class="row">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title" _msttexthash="323960" _msthash="106">Frais mensuel Maternel</h4>      
                <div class="form-group row">
                  <label for="exampleInputUsername" class="col-sm-3 col-form-label" _msttexthash="202930" _msthash="116"></label>
                  <div class="col-sm-9">
                    <input type="text" name="fraisinscription2_paramcontrat" value="{{$param->fraisinscription2_paramcontrat}}"  class="form-control" id="exampleInputUserannée"  _mstplaceholder="117572" _msthash="115">
                  </div>
                  <div class="form-group">
                    <label for="exampleTextarea1">Textarea</label>
                    <textarea class="form-control" id="exampleTextarea1" rows="4"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary w-100" _msttexthash="98280" _msthash="118">Modifier</button>
        </div>
      </form>
  </div>
</div>


@endsection