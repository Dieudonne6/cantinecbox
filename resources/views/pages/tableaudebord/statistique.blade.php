@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-lg-5 grid-margin stretch-card">
      <div class="card">
        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
          <h4 class="card-title" _msttexthash="381212" _msthash="93">Evolution de l'éffectif sur 5 ans</h4>
          <canvas id="lineChart" width="452" height="226" style="display: block; width: 452px; height: 226px;" class="chartjs-render-monitor"></canvas>
          <button class="btn btn-light" onclick="window.print()">Imprimer</button>
        </div>
      </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
          <h4 class="card-title" _msttexthash="344773" _msthash="94">Evolution de l'éffectif par promotion sur 5 ans</h4>
          <canvas id="barChart" width="52" height="26" style="display: block; width: 52px; height: 26px;" class="chartjs-render-monitor"></canvas>
          <div class="col-5 lg-1 grid-margin stretch-card">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>
                            #
                          </th>
                          <th _msttexthash="93574" _msthash="182"> Prénom </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td _msttexthash="4459" _msthash="186">
                            1
                          </td>
                          <td _msttexthash="146692" _msthash="187">
                            Herman Beck
                          </td>
                        </tr>
                        <tr>
                          <td _msttexthash="4550" _msthash="190">
                            2
                          </td>
                          <td _msttexthash="152009" _msthash="191">
                            Messsy Adam
                          </td>
                        </tr>
                        <tr>
                          <td _msttexthash="4823" _msthash="202">
                            5
                          </td>
                          <td _msttexthash="75114" _msthash="203">
                            Edward
                          </td>
                        </tr>
                        <tr>
                          <td _msttexthash="4914" _msthash="206">
                            6
                          </td>
                          <td _msttexthash="88855" _msthash="207">
                            John Doe
                          </td>
                        </tr>
                        <tr>
                          <td _msttexthash="5005" _msthash="210">
                            7
                          </td>
                          <td _msttexthash="113750" _msthash="211">
                            Henry Tom
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
          <button class="btn btn-light" onclick="window.print()">Imprimer</button>
        </div>
    </div>
</div>



@endsection