@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      <div class="form-group">
        <select class="js-example-basic-single w-100" id="select-classe">
          <option value="">Sélectionnez une classe</option>
          @foreach ($classe as $classes)
            <option value="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</option>
          @endforeach
        </select>
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
          <tbody id="eleve-details">
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

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $('#select-classe').on('change', function() {
      var classeCode = $(this).val();
      if (classeCode) {
        $.ajax({
          url: '/eleve/'+classeCode,
          type: 'GET',
          success: function(data) {
            $('#eleve-details').html(data);
          }
        });
      } else {
        $('#eleve-details').html('');
      }
      return false; // Empêcher la soumission du formulaire
    });
  });
</script>
@endpush