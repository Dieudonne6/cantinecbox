@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Toutes les classes</h4>
      <div class="form-group">
        <select class="js-example-basic-single w-100">
          <option value="ABOKY">ABOKY</option>
          <option value="WY">Wyoming</option>
          <option value="AM">America</option>
          <option value="CA">Canada</option>
          <option value="RU">Russia</option>
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
          <tbody>
            <tr>
              <td>
               MAT3
              </td>
              <td>
                ABOKY Stefy Dora
              </td>
            </tr>
            <tr>
              <td>
               MAT3
              </td>
              <td>
                ABOKY Stefy Dora
              </td>
            </tr>
            <tr>
              <td>
               MAT3
              </td>
              <td>
                ABOKY Stefy Dora
              </td>
            </tr>
            <tr>
              <td>
               MAT3
              </td>
              <td>
                ABOKY Stefy Dora
              </td>
            </tr>
            <tr>
              <td>
               MAT3
              </td>
              <td>
                ABOKY Stefy Dora
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection