@extends('layouts.master')
@section('content')

 <style>
  .btn.btn-outline-dark.btn-icon-text:hover {
      background-color: #844fc1; /* Change the background color to violet */
      border-color: violet; /* Change the border color to violet */
      color: white; /* Change the text color to white */
  }
  </style>

  <script src="{{ asset('js/chart.js') }}"></script>
  <script src="{{ asset('js/apexcharts.js') }}"></script>

<div class="main-panel-10">
  
  
  <div class="container">

    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
            <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
              <div>
                <p class="mb-2 text-md-center text-lg-left" _msttexthash="348296" _msthash="161">Total des Elèves</p>
                <h1 class="mb-0" _msttexthash="23400" _msthash="162">{{$totaleleve}}</h1>
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
                <h1 class="mb-0" _msttexthash="37804" _msthash="164">{{$totalcantineinscritactif}}</h1>
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
                <h1 class="mb-0" _msttexthash="28067" _msthash="166">{{$totalcantineinscritinactif}}</h1>
              </div>
              <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="row">
        <div class="col-md-4">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-auto">
                  <div class="page-title">Performance <br> Académique</div>
                </div>
                <div class="col text-right">
                  <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Taux de redoublement</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Taux d'exclusion</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Réussite par types d'examen</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div id="chart1"></div>
              {{-- <div class="performance-metrics">
                <div>Taux de passage: <span id="pass-rate">85%</span></div>
                <div>Taux de redoublement: <span id="repeat-rate">10%</span></div>
                <div>Taux d'exclusion académique: <span id="exclusion-rate">5%</span></div>
                <div>Taux de passage en classe supérieure: <span id="promotion-rate">90%</span></div>
              </div> --}}
            </div>
          </div>
          <!-- Script JavaScript pour configurer et afficher le graphique -->
          <script>
            document.addEventListener("DOMContentLoaded", function(event) {
              // Données de l'exemple
              var options = {
                series: [{
                  name: 'Notes',
                  data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
                }],
                chart: {
                  type: 'line',
                  height: 350,
                  toolbar: {
                    show: false
                  }
                },
                plotOptions: {
                  bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                  },
                },
                dataLabels: {
                  enabled: false
                },
                stroke: {
                  show: true,
                  width: 2,
                  colors: ['red']
                },
                xaxis: {
                  categories: ['Maths', 'Physique', 'Chimie', 'Biologie', 'Histoire', 'Géographie', 'Anglais', 'Français', 'Arts']
                },
                yaxis: {
                  title: {
                    text: 'Notes'
                  }
                },
                fill: {
                  opacity: 1
                },
                tooltip: {
                  y: {
                    formatter: function(val) {
                      return val + " points"
                    }
                  }
                }
              };
        
              // Initialiser le graphique avec les options
              var chart = new ApexCharts(document.querySelector("#chart1"), options);
        
              // Afficher le graphique
              chart.render();
        
              // Exemple de données de taux
              var passRate = 85;
              var repeatRate = 10;
              var exclusionRate = 5;
              var promotionRate = 90; // Taux de passage en classe supérieure
        
              // Mettre à jour les taux affichés
              document.getElementById('pass-rate').textContent = passRate + '%';
              document.getElementById('repeat-rate').textContent = repeatRate + '%';
              document.getElementById('exclusion-rate').textContent = exclusionRate + '%';
              document.getElementById('promotion-rate').textContent = promotionRate + '%'; // Mise à jour du taux de passage en classe supérieure
            });
          </script>
        </div>
        
        

        <div class="col-md-8  stretch-card">
          <div class="row">
            <div class="col-md-8">
              <div class="col-md-12 stretch-card">
                  <div class="card">
                      <div class="card-body">
                          <div class="chartjs-size-monitor">
                              <div class="chartjs-size-monitor-expand">
                                  <div class=""></div>
                              </div>
                              <div class="chartjs-size-monitor-shrink">
                                  <div class=""></div>
                              </div>
                          </div>
                          <div class="d-flex justify-content-between align-items-start flex-wrap">
                              <div>
                                  <p class="mb-3" _msttexthash="497549" _msthash="117">Recouvrement</p>
                              </div>
                              <div class="col text-right">
                                  <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fas fa-ellipsis-h"></i>
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="#">Recouvrement mensuels</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="#">Comparaison taux de recouvrement</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="#">Comparaison chiffre d'affaire</a>
                                  </div>
                              </div>
                          </div>
                          <canvas id="income-chart" width="456" height="228" style="display: block; width: 456px; height: 228px;" class="chartjs-render-monitor"></canvas>
                      </div>
                  </div>
              </div>
              <script>
                  // Supposons que Chart.js soit déjà inclus dans le projet
                  var ctx = document.getElementById('income-chart').getContext('2d');
                  var incomeChart = new Chart(ctx, {
                      type: 'pie', // ou 'bar', 'pie', etc.
                      data: {
                          labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'], // Labels des mois
                          datasets: [
                              {
                                  label: 'Taux de recouvrement',
                                  backgroundColor: '#a43cda',
                                  borderColor: '#ffffff',
                                  borderWidth: 2.5,
                                  data: [85, 90, 75, 80, 95, 70, 65, 60, 78, 88, 92, 85] // Exemples de taux de recouvrement mensuels
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
                                  beginAtZero: true,
                                  max: 100 // Taux de recouvrement en pourcentage
                              }
                          }
                      }
                  });
              </script>
            </div>
          
            <div class="card col-md-4">
              <div class="template-demo mt-2">
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-trash btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Historique des </small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Suppressions & Paiements </font></span>
                </button><br><br>
                <button class="btn btn-outline-dark btn-icon-text ">
                  <i class="typcn typcn-edit btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Historique des </small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                    Modifications de paiements
                  </font></span>
                </button><br><br>
                           
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-user-delete btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Historique des </small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Suppressions d'élèves </font></span>
                </button><br><br>
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-user btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Historique des </small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                    Modifications de profiles
                  </font></span>
                </button><br><br> 
              </div>
            </div>
          </div> 
        </div>

      </div>
    </div>
  </div>

      <style>
        #chart1 {
          min-height: 365px;
        }
      </style>
      <div class="container mt-4">
        <div class="col-12">
          <div class="row">

            <div class="col-6"> 
              
                  {{-- <div class="container">
                      <!-- Autres sections de ta page -->
              
                      <!-- Ajout de la nouvelle carte -->
                      <div class="card">
                          <div class="card-body d-flex flex-column justify-content-between">
                              <div class="chartjs-size-monitor">
                                  <div class="chartjs-size-monitor-expand">
                                      <div class=""></div>
                                  </div>
                                  <div class="chartjs-size-monitor-shrink">
                                      <div class=""> Rentabilité</div>
                                  </div>
                              </div>
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                  <div>
                                      <p class="mb-2 text-muted">Ventes</p>
                                      <h6 class="mb-0">563</h6>
                                  </div>
                                  <div>
                                      <p class="mb-2 text-muted">Ordres</p>
                                      <h6 class="mb-0">720</h6>
                                  </div>
                                  <div>
                                      <p class="mb-2 text-muted">Revenu</p>
                                      <h6 class="mb-0">5900</h6>
                                  </div>
                              </div>
                              <canvas id="sales-chart-a" class="mt-auto chartjs-render-monitor" height="66" width="188"></canvas>
                          </div>
                      </div>
                      <!-- Autres sections de ta page -->
                      <!-- Lien vers les scripts JavaScript de Bootstrap et Chart.js -->
                      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
              
                      <!-- Script pour initialiser le graphique Chart.js -->
                      <script>
                      var ctx = document.getElementById('sales-chart-a').getContext('2d');
                      var myChart = new Chart(ctx, {
                          type: 'line', // ou 'bar', 'pie', etc.
                          data: {
                              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                              datasets: [{
                                  label: 'Ventes',
                                  data: [12, 19, 3, 5, 2, 3, 7],
                                  backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                  borderColor: 'rgba(75, 192, 192, 1)',
                                  borderWidth: 1
                              }]
                          },
                          options: {
                              scales: {
                                  y: {
                                      beginAtZero: true
                                  }
                              }
                          }
                      });
                      </script>
                  </div> --}}
                  
                  <div class="container">
                    <div class="card">
                       
                      <div class="card-body border-bottom">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                              <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Budgétaire</h6>
                                              <div class="dropdown">
                                                <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">
                                                  <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                  <div class="dropdown-menu dropdown-menu-right">
                                                      <a class="dropdown-item" href="javascript:;">Evolution du Budget</a>
                                                      <a class="dropdown-item" href="javascript:;">Evolution par rubrique</a>
                                                   </div>
                                              </div>
                        </div>
                      </div>
                      <div class="card-body">
                                          <canvas id="sales-chart-c" class="mt-2"></canvas>
                                          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 mt-4">
                                              <div class="d-flex flex-column justify-content-center align-items-center">
                                                  <p class="text-muted">Ventes brutes</p>
                                                  <h5>492</h5>
                                                  <div class="d-flex align-items-baseline">
                                                      <p class="text-success mb-0">0.5%</p>
                                                      <i class="typcn typcn-arrow-up-thick text-success"></i>
                                                  </div>
                                              </div>
                                              <div class="d-flex flex-column justify-content-center align-items-center">
                                                  <p class="text-muted">Achats</p>
                                                  <h5>87k</h5>
                                                  <div class="d-flex align-items-baseline">
                                                      <p class="text-success mb-0">0.8%</p>
                                                      <i class="typcn typcn-arrow-up-thick text-success"></i>
                                                  </div>
                                              </div>
                                              <div class="d-flex flex-column justify-content-center align-items-center">
                                                  <p class="text-muted">Déclaration de revenus</p>
                                                  <h5>882</h5>
                                                  <div class="d-flex align-items-baseline">
                                                      <p class="text-danger mb-0">-0.4%</p>
                                                      <i class="typcn typcn-arrow-down-thick text-danger"></i>
                                                  </div>
                                              </div>
                                          </div>
                                          
                              
                      </div>
                      <!-- Script pour initialiser le graphique -->
                      <script>
                          var ctx = document.getElementById('sales-chart-c').getContext('2d');
                          var salesChart = new Chart(ctx, {
                              type: 'line',
                              data: {
                                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                                  datasets: [{
                                      label: 'Ventes',
                                      data: [12, 19, 3, 5, 2, 3, 7],
                                      borderColor: 'rgba(75, 192, 192, 1)',
                                      borderWidth: 1,
                                      fill: false
                                  }]
                              },
                              options: {
                                  responsive: true,
                                  scales: {
                                      y: {
                                          beginAtZero: true
                                      }
                                  }
                              }
                          });
                      </script>

                    </div>
                  </div>

            </div>

            <div class="col-6">
              <div class="card flex-fill">
                  <div class="card-header">
                      <div class="row align-items-center">
                          <div class="col-auto">
                              <div class="page-title">Évolution des Effectifs</div>
                          </div>
                          <div class="col text-right">
                              <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Sur 5 ans</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Par promotion sur 5 ans</a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div id="chart2"></div>
                  </div>
              </div>
              <!-- Script JavaScript pour configurer et afficher le graphique -->
              <script>
                  document.addEventListener("DOMContentLoaded", function (event) {
                      // Données d'exemple pour l'évolution des effectifs en fonction des années
                      var options = {
                          series: [{
                              name: 'Effectifs',
                              data: [100, 120, 150, 180, 200, 220, 230] // Ajoutez les effectifs pour chaque année ici
                          }],
                          chart: {
                              type: 'bar',
                              height: 350,
                              toolbar: {
                                  show: false
                              }
                          },
                          plotOptions: {
                              line: {
                                  horizontal: false,
                                  endingShape: 'rounded'
                              },
                          },
                          dataLabels: {
                              enabled: false
                          },
                          stroke: {
                              show: true,
                              width: 2,
                              colors: ['transparent']
                          },
                          xaxis: {
                              categories: ['2017', '2018', '2019', '2020', '2021', '2022', '2023'] // Années correspondantes
                          },
                          yaxis: {
                              title: {
                                  text: 'Effectifs'
                              }
                          },
                          fill: {
                              opacity: 1
                          },
                          tooltip: {
                              y: {
                                  formatter: function (val) {
                                      return val + " étudiants"
                                  }
                              }
                          }
                      };
          
                      // Initialiser le graphique avec les options
                      var chart = new ApexCharts(document.querySelector("#chart2"), options);
          
                      // Afficher le graphique
                      chart.render();
                  });
              </script>
            </div>
          
          </div>
        </div>
      </div>

  </div>

@endsection