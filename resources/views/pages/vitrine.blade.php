@extends('layouts.master')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="main-panel-10">
    <div class="content-wrapper">

        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left" _msttexthash="348296" _msthash="161">Total des Elèves</p>
                      <h1 class="mb-0" _msttexthash="23400" _msthash="162">8742</h1>
                    </div>
                    <i class="typcn typcn-group icon-xl text-secondary"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left" _msttexthash="175812" _msthash="163">Total d'inscriptions</p>
                      <h1 class="mb-0" _msttexthash="37804" _msthash="164">47,840</h1>
                    </div>
                    <i class="typcn typcn-user-add icon-xl text-secondary"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left" _msttexthash="158522" _msthash="165">Contrats Inactifs</p>
                      <h1 class="mb-0" _msttexthash="28067" _msthash="166">7 243 $</h1>
                    </div>
                    <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
                  </div>
                </div>
              </div>
            </div>
        </div>  

        <div class="row h-100">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                  <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                      <p class="mb-3" _msttexthash="497549" _msthash="117">Statistique mensuelle</p>
                      <h3 _msttexthash="31096" _msthash="118">67842</h3>
                    </div>
                    <div id="income-chart-legend" class="d-flex flex-wrap mt-1 mt-md-0">
                        <div class="d-flex align-items-center mr-3">
                            <div class="mr-2" style="width: 12px; border-radius: 50%; height: 12px; background-color: #a43cda "></div>
                            <p class="mb-0" _msttexthash="2632526" _msthash="119">Nombres d'inscrits</p>
                        </div>
                    </div>
                  </div>
                  <canvas id="income-chart" width="456" height="228" style="display: block; width: 456px; height: 228px;" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
            </div>
        </div>
        <script>
            // Supposons que Chart.js soit déjà inclus dans le projet
            var ctx = document.getElementById('income-chart').getContext('2d');
            var incomeChart = new Chart(ctx, {
                type: 'line', // ou 'bar', 'pie', etc.
                data: {
                    labels: ['Septembre', 'Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'], // Exemples de labels
                    datasets: [
                        {
                            label: 'Elèves Inscrits',
                            backgroundColor: '#a43cda',
                            data: [12000, 15000, 18000, 20000, 22000, 24000, 24500, 12000, 15000, 18000] // Exemples de données
                        },
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
          

    </div>
    <!-- content-wrapper ends -->

  </div>



@endsection