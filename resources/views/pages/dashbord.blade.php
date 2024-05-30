@extends('layouts.master')
@section('content')

  <div class="main-panel-10">


    <div class="container">
      <div class="col-12">
        <div class="row">
          <div class="col-6">
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

          <div class="col-md-6  stretch-card">
            <div class="template-demo mt-2">
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-apple btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Disponible sur le site Web de l'</small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Boutique d’applications </font></span>
              </button>
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-android btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Obtenez-le sur le</small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                  Google Play
                </font></span>
              </button>
            </div>
            <div class="template-demo mt-2">
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-apple btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Disponible sur le site Web de l'</small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Boutique d’applications </font></span>
              </button>
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-android btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Obtenez-le sur le</small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                  Google Play
                </font></span>
              </button>
            </div><div class="template-demo mt-2">
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-apple btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="663078" _msthash="191">Disponible sur le site Web de l'</small><font _mstmutation="1" _msttexthash="2223429" _msthash="192"> Boutique d’applications </font></span>
              </button>
              <button class="btn btn-outline-dark btn-icon-text">
                <i class="typcn typcn-vendor-android btn-icon-prepend"></i>
                <span class="d-inline-block text-left">
                  <small class="font-weight-light d-block" _msttexthash="283049" _msthash="193">Obtenez-le sur le</small><font _mstmutation="1" _msttexthash="152841" _msthash="194">
                  Google Play
                </font></span>
              </button>
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
              
              <div class="col-6 ">
                <div class="card flex-fill">
                  <div class="card-header">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <div class="page-title">Étudiants Survey</div>
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


   
   
    
    
    
   
    

      <div class="row mt-4">
        <div class="col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-center align-items-center flex-wrap">
                <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                    type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Effectif</h6>
                </button>
                  <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuSizeButton3">
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif <br> sur 5 ans</a>
                    <a class="dropdown-item" href="javascript:;">Evolution de l'éffectif <br> par promotion sur 5 ans</a>
                  </div>
                </div>
            </div>    
          </div>
        </div>
        
        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
          <div class="card">
            <div class="card-body border-bottom">
              <div class="d-flex justify-content-center align-items-center flex-wrap">
                <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <h6>Recouvrement</h6>
               </button>
                  <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuSizeButton4">
                    <a class="dropdown-item" href="javascript:;">Tableau analytique des <br> recouvrements mensuels</a>
                    <a class="dropdown-item" href="javascript:;">Comparaison taux de <br> recouvrement</a>
                    <a class="dropdown-item" href="javascript:;">Comparaison chiffre <br> d'affaire</a>
                  </div>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="dropdown d-flex justify-content-center align-items-center flex-wrap">
                    <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                     type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Académique</h6>
                    </button>
                    <div class="dropdown-menu text-center" aria-labelledby="dropdownMenuSizeButton4">
                      <a class="dropdown-item" href="javascript:;">Taux de passage en <br> classe supérieure</a>
                      <a class="dropdown-item" href="javascript:;">Taux de redoublement</a>
                      <a class="dropdown-item" href="javascript:;">Taux d'exclusion</a>
                      <a class="dropdown-item" href="javascript:;">Evolution Taux de <br> réussite par type d'examen</a>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="dropdown d-flex justify-content-center align-items-center flex-wrap">
                    <button class="btn btn-light dropdown-toggle" style="display: flex; justify-content: center; align-items: center; height:;"
                     type="button" id="dropdownMenuSizeButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <h6>Budgétaire</h6>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton4">
                      <h6 class="dropdown-header">Settings</h6>
                      <a class="dropdown-item" href="javascript:;">Action</a>
                      <a class="dropdown-item" href="javascript:;">Another action</a>
                      <a class="dropdown-item" href="javascript:;">Something else here</a>
                      <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-center align-items-center flex-wrap"> 
                    <button class="btn btn-light"> <h6>Rentabilité</h6></button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-center align-items-center flex-wrap"> 
                  <a class="btn btn-light " data-toggle="button"> <h6>Historique des suppressions et paiements</h6></a>    
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                    <a class="btn btn-light " data-toggle="button"> <h6>Historique des modifications de paiements</h6></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                  <a class="btn btn-light " data-toggle="button"> <h6>Historique des suppressions d'élèves</h6></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body border-bottom">
                <div class="d-flex justify-content-center align-items-center flex-wrap">
                    <a class="btn btn-light" data-toggle="button"> <h6>Historique des modifications de profiles</h6></a>
                </div>
              </div>
            </div>
          </div>

      </div>

    </div>

@endsection