<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="{{ asset('davidshimjs-qrcodejs-04f46c6/qrcode.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dataTables.css') }}" />
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        input::placeholder {
            color: #000 !important;
        }

        .profile-content .form-group {
            margin-bottom: 0 !important;
        }

        .profile-content input {
            text-align: right;
            padding: 0 0.3rem;
        }

        .tables th:nth-child(n+1),
        .tables td:nth-child(n+1) {
            /* width: 80px;
      max-width: 80px; */
            overflow: hidden;
        }

        .menu-item-has-children:hover::after {
            transform: translateY(-8rem) rotate(-90deg);
        }

        .menu-item-has-children {
            position: relative;
            padding: 0;
            /* display: flex;
align-items: center;  */
        }

        /* .menu-item-has-children > .nav-link {
    padding-right: 20px;
    display: inline-block;
    vertical-align: middle;
} */
        .menu-item-has-children::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            width: 10px;
            height: 9px;
            transform: translateY(-50%) rotate(90deg);
            background-size: 0.6rem;
            background-image: url(assets/images/arrow-left-bold.png);
            background-position: center;
            background-repeat: no-repeat;
            transition: transform 0.6s;
        }

        .sub-menus {
            position: absolute;
            left: 0;
            top: 100%;
            display: none;
            position: static;
            width: 13.5rem;
            background-color: #fff;
            padding: 0.8rem;
            margin: 0;
            z-index: 1;
            box-shadow: 0px 1px 15px 1px rgba(230, 234, 236, 0.35);
            border: 1px solid #f3f3f3;
        }

        .footer {
            position: absolute;
            width: 100%;
            bottom: 0;
            right: 0;
            left: 0;
            margin-top: auto;
            padding: 0;
            background-color: #fff;
        }

        .sub-menus li a {
            color: #000 !important;
        }

        .sub-menus li a:hover {
            text-decoration: none;
        }

        .sub-menus li {
            list-style: none;
            margin-left: -1.3rem;
            margin-bottom: 0.4rem;
        }

        .menu-item-has-children:hover .sub-menus {
            display: block;
        }

        .nav-logout {
            box-shadow: none;
            border: none;
            background-color: #fff;
            padding: 0;
            margin-left: 1.5rem;
        }

/* Style de la scrollbar */
#sidebar::-webkit-scrollbar {
    width: 2px; /* Largeur de la scrollbar */
}

/* Style de la piste de la scrollbar */
#sidebar::-webkit-scrollbar-track {
    background: #eee; /* Couleur de la piste */
    border-radius: 10px; /* Optionnel : arrondir les coins */
}

/* Style de la thumb (partie mobile de la scrollbar) */
#sidebar::-webkit-scrollbar-thumb {
    background-color: #eee; /* Couleur de la thumb */
    border-radius: 10px; /* Optionnel : arrondir les coins */
    border: 2px solid #eee; /* Optionnel : espace autour de la thumb */
}

/* Style de la thumb au survol */
#sidebar::-webkit-scrollbar-thumb:hover {
    background-color: #eee; /* Couleur de la thumb au survol */
}

        .sidebar {
            position: fixed;
            left: 0;
            right: 0;
            max-width: 240px;
            width: 100%;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    max-height: 400px; /* Hauteur maximale de la sidebar, par exemple, 100% de la hauteur de la fenêtre */
}
        

        .main-panel {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 0 20px 20px 20px 70px;
            position: relative;
            /* overflow-y: auto; */
            /* height: 100vh; */
            /* height: 100%; */
        }

        .rotate-after::after {
            transform: translateY(-8rem) rotate(-90deg) !important;
            transition: transform 0.3s ease !important;
        }

        .nav-link.active {
            color: #ffffff !important;
            /* Couleur du texte lorsque le lien est actif */
            background-color: #713dad !important;
            /* Couleur de fond lorsque le lien est actif */
        }

        .sidebar .nav.sub-menu .nav-item::before {
            content: none !important;
        }
        /* .sidebar .nav.sub-menu .nav-item .nav-link:hover {
            margin-left: 0.4rem;

        } */
        .sidebar .nav.sub-menu .nav-item {
                        padding: 0;
                        margin-left: -1.4rem;
        }
        .nav-link:hover {
            /* margin: 0.5rem 0; */
            background-color: #2c26341d;
        }

        .nav-tabs .nav-link:hover,
        .nav-tabs .nav-link:focus {
            color: #ffff;
        }
    </style>

</head>


<body>
    <div class="container-scroller">
        @include('layouts.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')
            <div class="main-panel">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="{{ asset('assets/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>

    <script src="{{ asset('assets/js/typeahead.js') }}"></script>
    {{-- <script src="{{asset('assets/js/select2.js')}}"></script> --}}


    <script src="{{ asset('assets/dataTables.js') }}"></script>
    <script>
        var statusAlert = document.getElementById('statusAlert');
        if (statusAlert) {
            setTimeout(function() {
                statusAlert.style.display = 'none';
            }, 6050);
        }
    </script>

    {{-- <script>
  $(document).ready(function() {
    $('#selectClasses').change(function() {
      var selectedValue = $(this).val();
      if (selectedValue === 'Maternelle') {
        $('.prima').addClass('d-none');
        $('.mater').removeClass('d-none');
      } else if (selectedValue === 'Primaire') {
        $('.mater').addClass('d-none');
        $('.prima').removeClass('d-none');
      }
    });
  });
</script> --}}

    <script>
        $(document).ready(function() {
            $('.sub-menu .nav-link').each(function() {
                if ($(this).hasClass('active')) {
                    var parentCollapse = $(this).closest('.collapse');
                    parentCollapse.addClass('show');
                    parentCollapse.prev('a.nav-link').removeClass('collapsed').attr('aria-expanded',
                        'true');
                }
                $('.sub-menus .nav-link').each(function() {
                    if ($(this).hasClass('active')) {
                        $(this).closest('.sub-menus').addClass('d-block');
                        $(this).closest('.menu-item-has-children').addClass('rotate-after');
                    }
                });
            });
            // $('.sub-menus .nav-link').each(function() {
            //     if ($(this).hasClass('active')) {
            //         var parentCollapse = $(this).closest('.collapse');
            //         parentCollapse.addClass('show');
            //         parentCollapse.prev('a.nav-link').removeClass('collapsed').attr('aria-expanded', 'true');         
            //     }
            // });
        });

        $(document).ready(function() {
            $('#myTable').DataTable({
                "language": {
                    "sProcessing": "Traitement en cours...",
                    "sSearch": "Rechercher&nbsp;:",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                    "sInfoEmpty": "Affichage de 0 à 0 sur 0 entrées",
                    "sInfoFiltered": "(filtré à partir de _MAX_ entrées au total)",
                    "sInfoPostFix": "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "oPaginate": {
                        "sPrevious": "Précédent",
                        "sNext": "Suivant"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    }
                }
            });
        });
        $(document).ready(function() {
            $('#nomclasse').on('input', function() {
                $('#libclasse').val($(this).val());
            });
        });
    </script>
    <!-- End custom js for this page-->

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
      $(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Ajouter un gestionnaire d'événements à chaque ligne d'élève
    $('.eleve').click(function() {
        // Supprimer la classe 'selected' de toutes les lignes d'élève
        $('.eleve').removeClass('selected');
        // Ajouter la classe 'selected' à la ligne d'élève cliquée
        $(this).addClass('selected');
        // Récupérer les informations de l'élève sélectionné
        // var eleveId = $(this).data('id');
        var eleveNom = $(this).data('nom');
        var elevePrenom = $(this).data('prenom');
        var eleveCodeClas = $(this).data('codeclas');
        $.ajax({
            url: '/traiter',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
           
            data: JSON.stringify({ eleveNom: eleveNom }), // Encapsuler les données dans un objet JSON
    contentType: 'application/json',
            success: function(response) {
              console.log('Réponse du serveur : ', response);
                console.log('Les informations de l\'élève ont été envoyées avec succès.');
            },
            error: function(xhr, status, error) {
                console.error('Une erreur s\'est produite lors de l\'envoi des informations de l\'élève : ' + error);
                console.log(xhr);
            }
        });
        
        // Afficher les informations de l'élève sélectionné (par exemple, dans la console)
        // console.log("ID de l'élève : " + eleveId);
        console.log("Nom de l'élève : " + eleveNom);
        console.log("Prénom de l'élève : " + elevePrenom);
        console.log("Code de la classe de l'élève : " + eleveCodeClas);
    });

    

        // Vérifier si aucune ligne n'est sélectionnée au chargement de la page
        var premiereLigne = $('.eleve:first');
    if (!premiereLigne.hasClass('selected')) {
        // Si aucune ligne n'est sélectionnée, sélectionner automatiquement la première ligne
        premiereLigne.addClass('selected');
        // Simuler un clic sur la première ligne pour afficher les informations de l'élève par défaut
        premiereLigne.click();
    }


});
  </script>
  <style>
    .selected {
        background-color: #e3e6f4; /* Changez la couleur de fond selon vos préférences */
    }
</style> --}}


    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#tableSelect1').on('change', function() {
                var codeClass = $(this).val();
                $.ajax({
                    url: '/get-clas/' + codeClass,
                    type: 'GET',
                    success: function(data) {
                        $('#tableSelect4').empty();
                        if (data.length > 0) {
                            $('#tableSelect4').append(
                                '<option value="">Sélectionner une classe</option>');
                            $.each(data, function(index, eleve) {
                                $('#tableSelect4').append('<option value="' + eleve
                                    .CODECLAS + '">' + eleve.CODECLAS + '</option>');
                            });
                        } else {
                            $('#tableSelect4').append(
                                '<option value="">Aucune classe disponible</option>');
                        }
                        // $('#tableSelect4').select2();

                    }
                });
            }); 
           
            function toggleDivs() {
                if ($('#optionsRadios1').is(':checked')) {
                    $('#div1').removeClass('d-block').addClass('d-none');
                    $('#div2').removeClass('d-none').addClass('d-block');
                } else if ($('#optionsRadios2').is(':checked')) {
                    $('#div1').removeClass('d-none').addClass('d-block');
                    $('#div2').removeClass('d-block').addClass('d-none');
                }
            }

            // Écoutez les changements sur les boutons radio
            $('input[name="optionsRadios"]').change(toggleDivs);

            // Initialiser l'état des divs
            toggleDivs();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#nouvelleFaute').on('change', function() {
                // Récupère l'option sélectionnée
                var selectedOption = $(this).find('option:selected');

                // Récupère la valeur de l'attribut data-heure de l'option sélectionnée
                var heure = selectedOption.data('heure');

                // Mets à jour la valeur de l'input avec la valeur de data-heure
                $('#heure').val(heure ? parseFloat(heure) : 0);
            });
        });
        $(document).ready(function() {
            // Initialiser Select2 sur le sélecteur de classes
        $('.js-example-basic-multiple').select2();
      $('#submitBtn').click(function() {
      var selectedValues = $('.js-example-basic-multiple').val(); // Récupérer les valeurs sélectionnées
      
      if (selectedValues.length > 0) {
          // Utiliser une route Laravel correctement définie pour éviter la duplication
          var url = `{{ url('listegeneraleeleve') }}/` + selectedValues.join(',');
          
          // Si l'utilisateur est déjà sur la page, utiliser replace() pour forcer le rechargement
          if (window.location.href === url) {
              window.location.replace(url); // Remplacer l'URL actuelle pour forcer le rechargement
          } else {
              window.location.href = url; // Sinon rediriger normalement
          }
      } else {
          alert('Veuillez sélectionner au moins une classe.');
      }
  });
  $('#submitBtnselective').click(function() {
    var selectedClasses = $('.js-example-basic-multiple[name="CODECLAS[]"]').val(); // Récupérer les classes sélectionnées
    var sexe = $('#sexeSelect').val(); // Récupérer le sexe sélectionné
    var minAge = $('#minAge').val() || $('#minAge').attr('placeholder'); // Utiliser le placeholder si l'âge n'est pas sélectionné
    var maxAge = $('#maxAge').val() || $('#maxAge').attr('placeholder'); 

    // Vérifier si aucune classe n'est sélectionnée
    var classesQueryParam = selectedClasses.length > 0 ? selectedClasses.join(',') : 'all';

    var url = `{{ url('filterlisteselectiveeleve') }}?classes=` + classesQueryParam + `&sexe=${sexe}&minAge=${minAge}&maxAge=${maxAge}`;
    
    window.location.href = url; // Rediriger vers la route
});


$('#Filtercycle').on('change', function() {
                var serieClass = $(this).val();
                console.log(serieClass);
                $.ajax({
                    url: '/get-serie/' + serieClass,
                    type: 'GET',
                    success: function(data) {
                        $('#filterserie').empty();
                        console.log(data);
                        if (data.length > 0) {
                            $('#filterserie').append(
                                '<option value="">Sélectionner une serie</option>');
                            $.each(data, function(index, serie) {
                                $('#filterserie').append('<option value="' + serie
                                    .SERIE + '">' + serie.LIBELSERIE + '</option>');
                            });
                        } else {
                            $('#filterserie').append(
                                '<option value="">Aucune serie disponible</option>');
                        }
                        $('#filterserie').select2();

                    }
                });
            });
            $('#ensignSelect').on('change', function() {
                var ensigClass = $(this).val();
                console.log(ensigClass);
                if (ensigClass == '1' || ensigClass == '2') {
        $('#Filtercycle').prop('disabled', true); // Désactiver le sélecteur Cycle
        $('#filterserie').prop('disabled', true); // Désactiver le sélecteur Série
    } else {
        $('#Filtercycle').prop('disabled', false); // Réactiver le sélecteur Cycle
        $('#filterserie').prop('disabled', false); // Réactiver le sélecteur Série
    }
                $.ajax({
                    url: '/get-promo/' + ensigClass,
                    type: 'GET',
                    success: function(data) {
                        $('#Filterpromo').empty();
                        console.log(data);
                        if (data.length > 0) {
                            $('#Filterpromo').append(
                                '<option value="">Sélectionner une promotion</option>');
                            $.each(data, function(index, promo) {
                                $('#Filterpromo').append('<option value="' + promo
                                    .CODEPROMO + '">' + promo.LIBELPROMO + '</option>');
                            });
                        } else {
                            $('#Filterpromo').append(
                                '<option value="">Aucune promotion disponible</option>');
                        }
                        $('#Filterpromo').select2();

                    }
                });
            });
            $('#classSelect').on('change', function() {
                var codeClass = $(this).val();
                $.ajax({
                    url: '/get-eleves/' + codeClass,
                    type: 'GET',
                    success: function(data) {
                        $('#eleveSelect').empty();
                        if (data.length > 0) {
                            $('#eleveSelect').append(
                                '<option value="">Sélectionner un élève</option>');
                            $.each(data, function(index, eleve) {
                                $('#eleveSelect').append('<option value="' + eleve
                                    .MATRICULE + '">' + eleve.NOM + ' ' + eleve
                                    .PRENOM + '</option>');
                            });
                        } else {
                            $('#eleveSelect').append(
                                '<option value="">Aucun élève disponible</option>');
                        }
                        $('#eleveSelect').select2();

                    }
                });
                $.ajax({
                    url: '/get-montant/' + codeClass,
                    type: 'GET',
                    success: function(data) {
                        $('#montant').val(data.montant);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
        

            $('#selectClasses').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'Maternelle') {
                    $('.prima').addClass('d-none');
                    $('.mater').removeClass('d-none');
                } else if (selectedValue === 'Primaire') {
                    $('.mater').addClass('d-none');
                    $('.prima').removeClass('d-none');
                }
            });
            $('#calculs').on('click', function() {
                $('#percentage').removeClass('d-none');

                $('#calcul-one').addClass('col-lg-8');
                $('.calcul-modal').css({
                    'width': '800px'
                });
            });

            $('#calculss').on('click', function() {
                $('#percentages').removeClass('d-none');

                $('#calcul-ones').addClass('col-lg-8');
                $('.calcul-modals').css({
                    'width': '800px'
                });
            });

            $('#closecalculate').on('click', function() {
                $('#percentage').addClass('d-none');
                $('#calcul-one').removeClass('col-lg-8');
                $('.calcul-modal').css({
                    'width': '500px'
                });

            });

            $('#closecalculates').on('click', function() {
                $('#percentages').addClass('d-none');
                $('#calcul-ones').removeClass('col-lg-8');
                $('.calcul-modals').css({
                    'width': '500px'
                });

            });

        });
    
  $('#class-select').on('select2:select', function (e) {
  var selectedOption = e.params.data.element;
  var typeReduction = $(selectedOption).data('type');
  var reductionFrais1 = parseFloat($(selectedOption).data('frais1')) || 0;
  var reductionFrais2 = parseFloat($(selectedOption).data('frais2')) || 0;
  var reductionFrais3 = parseFloat($(selectedOption).data('frais3')) || 0;
  var reductionFrais4 = parseFloat($(selectedOption).data('frais4')) || 0;
  var reductionFrais4 = parseFloat($(selectedOption).data('frais4')) || 0;
  var sco = parseFloat($(selectedOption).data('sco')) || 0;
  var arrie = parseFloat($(selectedOption).data('arrie')) || 0;

  var reductionfixeFrais1 = parseFloat($(selectedOption).data('fixefrais1')) || 0;
  var reductionfixeFrais2 = parseFloat($(selectedOption).data('fixefrais2')) || 0;
  var reductionfixeFrais3 = parseFloat($(selectedOption).data('fixefrais3')) || 0;
  var reductionfixeFrais4 = parseFloat($(selectedOption).data('fixefrais4')) || 0;
  var fixearriere = parseFloat($(selectedOption).data('fixearriere')) || 0;
  var fixesco = parseFloat($(selectedOption).data('fixesco')) || 0;

  var frais1 = parseFloat($('#frais1').val()) || 0;
  var frais2 = parseFloat($('#frais2').val()) || 0;
  var frais3 = parseFloat($('#frais3').val()) || 0;
  var frais4 = parseFloat($('#frais4').val()) || 0;
  var apayer = parseFloat($('#apayer').val()) || 0;
  var arriere = parseFloat($('#arriereinitial').val()) || 0;

  var fraisclasse1 = parseFloat($('#fraisclasse1').val()) || 0;
  var fraisclasse2 = parseFloat($('#fraisclasse2').val()) || 0;
  var fraisclasse3 = parseFloat($('#fraisclasse3').val()) || 0;
  var fraisclasse4 = parseFloat($('#fraisclasse4').val()) || 0;
  var classesco = parseFloat($('#classesco').val()) || 0;


    if (typeReduction === 'P') {
    frais1 = fraisclasse1 - (fraisclasse1 * reductionFrais1);
    frais2 = fraisclasse2 - (fraisclasse2 * reductionFrais2);
    frais3 = fraisclasse3 - (fraisclasse3 * reductionFrais3);
    frais4 = fraisclasse4 - (fraisclasse4 * reductionFrais4);
    if(arriere !== 0){
        arriere = arriere - (arriere * arrie);
    }
    apayer = classesco - (classesco * sco);
  } else if (typeReduction === 'F') {
    frais1 = fraisclasse1 - reductionfixeFrais1;
    frais2 = fraisclasse2 - reductionfixeFrais2;
    frais3 = fraisclasse3 - reductionfixeFrais3;
    frais4 = fraisclasse4 - reductionfixeFrais4;
    apayer = classesco - fixesco;
    if(arriere !== 0){
    arriere = arriere - fixearriere;
    }
  }

  $('#frais1').val(frais1);
  $('#frais2').val(frais2);
  $('#frais3').val(frais3);
  $('#frais4').val(frais4);
  $('#apayer').val(apayer);
  $('#arriere').val(arriere);

});


    </script>

    

<script>
    $('#submitBtn1').click(function() {
      var selectedValues = $('.js-example-basic-multiple').val(); // Récupérer les valeurs sélectionnées
      
      if (selectedValues.length > 0) {
          // Utiliser une route Laravel correctement définie pour éviter la duplication
          var url = `{{ url('eleveparclassespecifique') }}/` + selectedValues.join(',');
          
          // Si l'utilisateur est déjà sur la page, utiliser replace() pour forcer le rechargement
          if (window.location.href === url) {
              window.location.replace(url); // Remplacer l'URL actuelle pour forcer le rechargement
          } else {
              window.location.href = url; // Sinon rediriger normalement
          }
      } else {
          alert('Veuillez sélectionner au moins une classe.');
      }
  });
</script>

<script>
    $('#submitBtnNoteVierge').click(function() {
      var selectedValues = $('.js-example-basic-multiple').val(); // Récupérer les valeurs sélectionnées
      
      if (selectedValues.length > 0) {
          // Utiliser une route Laravel correctement définie pour éviter la duplication
          var url = `{{ url('/editions2/fichedenoteviergefina') }}/` + selectedValues.join(',');
          
          // Si l'utilisateur est déjà sur la page, utiliser replace() pour forcer le rechargement
          if (window.location.href === url) {
              window.location.replace(url); // Remplacer l'URL actuelle pour forcer le rechargement
          } else {
              window.location.href = url; // Sinon rediriger normalement
          }
      } else {
          alert('Veuillez sélectionner au moins une classe.');
      }
  });
</script>

<script>
    $('#submitBtnRP').click(function() {
      var selectedValues = $('.js-example-basic-multiple').val(); // Récupérer les valeurs sélectionnées
      
      if (selectedValues.length > 0) {
          // Utiliser une route Laravel correctement définie pour éviter la duplication
          var url = `{{ url('rpaiementclassespecifique') }}/` + selectedValues.join(',');
          
          // Si l'utilisateur est déjà sur la page, utiliser replace() pour forcer le rechargement
          if (window.location.href === url) {
              window.location.replace(url); // Remplacer l'URL actuelle pour forcer le rechargement
          } else {
              window.location.href = url; // Sinon rediriger normalement
          }
      } else {
          alert('Veuillez sélectionner au moins une classe.');
      }
  });
</script>

<script>
    $('#submitBtnSFE').click(function() {
      var selectedValues = $('.js-example-basic-multiple').val(); // Récupérer les valeurs sélectionnées
      
      if (selectedValues.length > 0) {
          // Utiliser une route Laravel correctement définie pour éviter la duplication
          var url = `{{ url('sfinanceclassespecifique') }}/` + selectedValues.join(',');
          
          // Si l'utilisateur est déjà sur la page, utiliser replace() pour forcer le rechargement
          if (window.location.href === url) {
              window.location.replace(url); // Remplacer l'URL actuelle pour forcer le rechargement
          } else {
              window.location.href = url; // Sinon rediriger normalement
          }
      } else {
          alert('Veuillez sélectionner au moins une classe.');
      }
  });
</script>





</body>

</html>
