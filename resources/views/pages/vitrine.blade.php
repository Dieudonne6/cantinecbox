@extends('layouts.master')
@section('content')



<div class="main-panel-10">
  <style>
    .btn.btn-outline-dark.btn-icon-text:hover {
        background-color: #844fc1; /* Change the background color to violet */
        border-color: violet; /* Change the border color to violet */
        color: white; /* Change the text color to white */
    }
  </style>
  
  <div class="container">

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

    <div class="col-12">
      <div class="row">

        <div class="col-md-4">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-auto">
                  <div class="page-title">Performance Académique</div>
                </div>
                <div class="col text-right">
                  <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div id="chart1"></div>
            </div>
          </div>
          <!-- Inclure jQuery, Popper.js, et Bootstrap JS -->
          <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
          <!-- Inclure ApexCharts JS -->
          <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  
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
            });
          </script>
        </div>

        <div class="col-md-8  stretch-card">
          <div class="row">
            <div class="col-md-8">
              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
              <script>
                // Supposons que Chart.js soit déjà inclus dans le projet
                var ctx = document.getElementById('income-chart').getContext('2d');
                var incomeChart = new Chart(ctx, {
                    type: 'pie', // ou 'bar', 'pie', etc.
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
            <div class="card col-md-4">
              <div class="template-demo">
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-trash btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Historique des </small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Suppressions & Paiements </font></span>
                </button>
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-edit btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Historique des </small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                    Modifications de paiements
                  </font></span>
                </button>
                {{-- <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-vendor-apple btn-icon-prepend"></i>
                  <span class="d-inline-block text-left">
                    <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191"></small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Budgétaire </font></span>
                </button> --}}
              </div>
              <div class="template-demo mt-2">              
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-user-delete btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Historique des </small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Suppressions d'élèves </font></span>
                </button>
                <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-user btn-icon-prepend"></i>
                  <span class="d-inline-block text-center">
                    <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Historique des </small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                    Modifications de profiles
                  </font></span>
                </button>
                {{-- <button class="btn btn-outline-dark btn-icon-text">
                  <i class="typcn typcn-vendor-android btn-icon-prepend"></i>
                  <span class="d-inline-block text-left">
                    <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193"></small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                    Rentabilité
                  </font></span>
                </button> --}}
                
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
              
              
                  
                  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
                       
                     
                          <!-- Inclure Bootstrap CSS -->
                          <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
                          <!-- Inclure Chart.js -->
                          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                      
                      
                                      <div class="card-body border-bottom">
                                          <div class="d-flex justify-content-between align-items-center flex-wrap">
                                              <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Budgétaire</h6>
                                              <div class="dropdown">
                                                  <button class="btn bg-white p-0 pb-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 30 derniers jours </button>
                                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                                                      <h6 class="dropdown-header">Settings</h6>
                                                      <a class="dropdown-item" href="javascript:;">Action</a>
                                                      <a class="dropdown-item" href="javascript:;">Another action</a>
                                                      <a class="dropdown-item" href="javascript:;">Something else here</a>
                                                      <div class="dropdown-divider"></div>
                                                      <a class="dropdown-item" href="javascript:;">Separated link</a>
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
                                          <div class="d-flex justify-content-between align-items-center">
                                              <div class="dropdown">
                                                  <button class="btn bg-white p-0 pb-1 pt-1 text-muted btn-sm dropdown-toggle" type="button" id="dropdownMenuSizeButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 7 derniers jours </button>
                                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton3">
                                                      <h6 class="dropdown-header">Settings</h6>
                                                      <a class="dropdown-item" href="javascript:;">Action</a>
                                                      <a class="dropdown-item" href="javascript:;">Another action</a>
                                                      <a class="dropdown-item" href="javascript:;">Something else here</a>
                                                      <div class="dropdown-divider"></div>
                                                      <a class="dropdown-item" href="javascript:;">Separated link</a>
                                                  </div>
                                              </div>
                                              <p class="mb-0">aperçu</p>
                                          </div>
                              
                      </div>
                      
                      <!-- Inclure jQuery et Bootstrap JS -->
                      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
                      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                      
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
                              <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true"
                                  aria-expanded="false">
                                  <i class="fas fa-ellipsis-h"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" href="#">Action</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Something else here</a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div id="chart2"></div>
                  </div>
              </div>
              <!-- Inclure jQuery, Popper.js, et Bootstrap JS -->
              <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
              <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
              <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
              <!-- Inclure ApexCharts JS -->
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
          
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