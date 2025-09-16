@extends('layouts.master')
@section('content')
    <style>
        .btn.btn-outline-dark.btn-icon-text:hover {
            background-color: #844fc1;
            /* Change the background color to violet */
            border-color: violet;
            /* Change the border color to violet */
            color: white;
            /* Change the text color to white */
        }

        /* Assure la même hauteur et un bon alignement */
.btn-icon-text {
  min-height: 92px;       /* hauteur identique pour tous */
  padding: 12px 16px;
  text-align: center;
  /* display: flex; */
  align-items: center;
}

/* Taille et espacement des icônes */
.btn-icon-text .typcn {
  font-size: 24px;
  width: 20px;            /* espace réservé pour icône */
  text-align: center;
}

/* Evite que le texte ne dépasse trop */
.btn-icon-text .text-left {
  flex: 1;
}

/* Optionnel: améliorer l'aspect sur petits écrans */
@media (max-width: 576px) {
  .btn-icon-text {
    min-height: 64px;
    padding: 10px;
  }
  .btn-icon-text .typcn { font-size: 20px; width: 36px; }
}

    </style>

    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/apexcharts.js') }}"></script>

    <div class="main-panel-10">


        <div class="container" style="margin-bottom: 5rem;">

            <div class="row">
                <div class="col-md-4 grid-margin stretch-card">
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
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left" _msttexthash="348296" _msthash="161">Total
                                        des Elèves</p>
                                    <h1 class="mb-0" _msttexthash="23400" _msthash="162">{{ $totaleleve }}</h1>
                                </div>
                                <i class="typcn typcn-group icon-xl text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
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
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left" _msttexthash="175812" _msthash="163">Total
                                        inscriptions cantine</p>
                                    <h1 class="mb-0" _msttexthash="37804" _msthash="164">{{ $totalcantineinscritactif }}
                                    </h1>
                                </div>
                                <i class="typcn typcn-user-add icon-xl text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
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
                            <div
                                class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                                <div>
                                    <p class="mb-2 text-md-center text-lg-left" _msttexthash="158522" _msthash="165">
                                        Contrats Inactifs</p>
                                    <h1 class="mb-0" _msttexthash="28067" _msthash="166">{{ $totalcantineinscritinactif }}
                                    </h1>
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
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-perf"></div>
                            </div>
                        </div>
                        <!-- Script JavaScript pour configurer et afficher le graphique -->
                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var passants = @json($passants);
                                var redoublants = @json($redoublants);
                                var exclusions = @json($exclusions);
                                var abandons = @json($abandons);

                                var series = [passants, redoublants, exclusions, abandons];
                                var labels = ['Passants', 'Redoublants', 'Exclusions', 'Abandons'];

                                var options = {
                                    chart: {
                                        type: 'donut',
                                        height: 350,
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    series: series,
                                    labels: labels,
                                    colors: [
                                        '#1f77b4', // passants - bleu
                                        '#ff7f0e', // redoublants - orange
                                        '#2ca02c', // exclusions - vert
                                        '#d62728' // abandons - rouge
                                    ],
                                    stroke: {
                                        show: true,
                                        width: 1,
                                        colors: ['#ffffff'] // séparation entre tranches
                                    },
                                    plotOptions: {
                                        pie: {
                                            expandOnClick: true, // cliqué/hover = met en avant
                                            offsetY: 0,
                                            donut: {
                                                size: '58%',
                                                labels: {
                                                    show: true,
                                                    name: {
                                                        show: true,
                                                        fontSize: '14px',
                                                        offsetY: -6
                                                    },
                                                    value: {
                                                        show: true,
                                                        fontSize: '18px',
                                                        offsetY: 6,
                                                        formatter: function(val) {
                                                            return val;
                                                        } // valeur brute
                                                    },
                                                    total: {
                                                        show: true,
                                                        label: 'Total',
                                                        formatter: function(w) {
                                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function(val, opts) {
                                            // val = pourcentage arrondi fourni par Apex, opts donne les détails
                                            var seriesIndex = opts.seriesIndex;
                                            var total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            var value = opts.w.globals.series[seriesIndex];
                                            var percent = total === 0 ? 0 : (value / total * 100);
                                            // N'affiche le label sur la tranche que si >= 3% (ajuste ce seuil si tu veux)
                                            if (percent < 3) return '';
                                            return percent.toFixed(1) + '%';
                                        },
                                        dropShadow: {
                                            enabled: false
                                        }
                                    },
                                    states: {
                                        hover: {
                                            filter: {
                                                type: 'darken',
                                                value: 0.12
                                            }
                                        }
                                    },
                                    legend: {
                                        show: true,
                                        position: 'bottom',
                                        formatter: function(seriesName, opts) {
                                            var idx = opts.seriesIndex;
                                            var total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            var val = opts.w.globals.series[idx];
                                            var percent = total === 0 ? 0 : (val / total * 100);
                                            return seriesName + " — " + val + " (" + percent.toFixed(1) + "%)";
                                        }
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: function(val) {
                                                return val + " élèves";
                                            }
                                        }
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector("#chart-perf"), options);
                                chart.render();
                            });
                        </script>

                    </div>



                    <div class="col-md-8  stretch-card">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="col-md-12 stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                <div>
                                                    <p class="mb-3">Recouvrement </p>
                                                </div>
                                                <div class="col text-right">
                                                    {{-- <button class="btn btn-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </button> --}}
                                                    {{-- <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#">Recouvrements mensuels</a>
                </div> --}}
                                                </div>
                                            </div>

                                            <canvas id="income-chart" style="width:100%; height:280px;"></canvas>
                                        </div>
                                    </div>
                                </div>

                                {{-- dépendances JS (si pas déjà incluses dans ton layout) --}}
                                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                <script>
                                    // Données passées depuis le contrôleur
                                    const monthLabels = @json($months);
                                    const monthlyTotals = @json($monthlyTotals);

                                    // palette simple (12 couleurs) — tu peux personnaliser
                                    const bgColors = [
                                        '#a43cda', '#6c5ce7', '#00b894', '#0984e3', '#fdcb6e', '#e17055',
                                        '#00cec9', '#fab1a0', '#74b9ff', '#55efc4', '#ffeaa7', '#fd79a8'
                                    ];

                                    const ctx = document.getElementById('income-chart').getContext('2d');
                                    const incomeChart = new Chart(ctx, {
                                        type: 'pie',
                                        data: {
                                            labels: monthLabels,
                                            datasets: [{
                                                label: 'Recouvrement',
                                                data: monthlyTotals,
                                                backgroundColor: bgColors,
                                                borderColor: '#ffffff',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top'
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(context) {
                                                            const value = context.raw;
                                                            return context.label + ': ' + new Intl.NumberFormat().format(value) + ' XAF';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>

                            <div class="card col-md-4">
                                <div class="card-body template-demo mt-2">

                                    <a href="{{ url('/Acceuil') }}"
                                        class="btn btn-outline-dark btn-block btn-icon-text d-flex align-items-center">
                                        <i class="typcn typcn-th-list mr-3" aria-hidden="true"></i>
                                        <div class="text-left">
                                            <small class="font-weight-light d-block">Inscription & Discipline</small>
                                            <span class="font-weight-bold">Liste des élèves</span>
                                        </div>
                                    </a>

                                    <a href="{{ route('listefacturescolarite') }}"
                                        class="btn btn-outline-dark btn-block btn-icon-text d-flex align-items-center mt-3">
                                        <i class="typcn typcn-credit-card mr-3" aria-hidden="true"></i>
                                        <div class="text-left">
                                            <small class="font-weight-light d-block">Scolarité</small>
                                            <span class="font-weight-bold">Mise à jour des paiements</span>
                                        </div>
                                    </a>

                                    <a href="{{ route('tableaudenotes') }}"
                                        class="btn btn-outline-dark btn-block btn-icon-text d-flex align-items-center mt-3">
                                        <i class="typcn typcn-chart-bar mr-3" aria-hidden="true"></i>
                                        <div class="text-left">
                                            <small class="font-weight-light d-block">Notes et Bulletin</small>
                                            <span class="font-weight-bold">Tableau de Notes</span>
                                        </div>
                                    </a>

                                    <a href="{{ route('rapportannuel') }}"
                                        class="btn btn-outline-dark btn-block btn-icon-text d-flex align-items-center mt-3">
                                        <i class="typcn typcn-document-text mr-3" aria-hidden="true"></i>
                                        <div class="text-left">
                                            <small class="font-weight-light d-block">Notes et Bulletin</small>
                                            <span class="font-weight-bold">Rapports Annuels</span>
                                        </div>
                                    </a>

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
        {{-- <div class="container mt-4">
            <div class="col-12">
                <div class="row">

                    <div class="col-6">

                        <div class="container">
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
                                    <canvas id="sales-chart-a" class="mt-auto chartjs-render-monitor" height="66"
                                        width="188"></canvas>
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
                        </div>

                        <div class="container">
                            <div class="card">

                                <div class="card-body border-bottom">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-2 mb-md-0 text-uppercase font-weight-medium">Budgétaire</h6>
                                        <div class="dropdown">
                                            <button class="btn btn-light" type="button" data-toggle="dropdown"
                                                aria-haspopup="true"aria-expanded="false">
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
                                    <div
                                        class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 mt-4">
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

                                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                                        <button class="btn btn-light" type="button" data-toggle="dropdown"
                                            aria-haspopup="true"aria-expanded="false">
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

                        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <!-- Script JavaScript pour configurer et afficher le graphique -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function(event) {
                                // Données d'exemple pour l'évolution des effectifs en fonction des années
                                var options = {
                                    series: [{
                                        name: 'Effectifs',
                                        data: [100, 120, 150, 180, 200, 220,
                                            230] // Ajoutez les effectifs pour chaque année ici
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
                                        categories: ['2017', '2018', '2019', '2020', '2021', '2022',
                                            '2023'] // Années correspondantes
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
                                            formatter: function(val) {
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
        </div> --}}

    </div>
@endsection
