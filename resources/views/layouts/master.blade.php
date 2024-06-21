<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{asset('assets/vendors/typicons/typicons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <script src="{{ asset('davidshimjs-qrcodejs-04f46c6/qrcode.js') }}"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{asset('assets/vendors/select2/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />

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
   .tables th:nth-child(n+1), .tables td:nth-child(n+1) {
      /* width: 80px;
      max-width: 80px; */
      overflow: hidden;
    }
    .menu-item-has-children:hover::after {
transform: translateY(-3.5rem) rotate(-90deg);
}
.menu-item-has-children {
position: relative;
padding: 0;
}
.menu-item-has-children::after{
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
  width: 12.5rem;
  background-color: #fff;
  padding: 0.8rem;
  margin: 0;
  z-index: 1;
  box-shadow: 0px 1px 15px 1px rgba(230, 234, 236, 0.35);
  border: 1px solid #f3f3f3;
}
.sub-menus li a {
  color: #000 !important;
}
.sub-menus li a:hover {
  text-decoration: none;
}
.sub-menus li {
  list-style: none;
  margin-bottom: 0.4rem;
}
.menu-item-has-children:hover .sub-menus{
display: block;
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
  <script src="{{asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{asset('assets/js/off-canvas.js')}}"></script>
  <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('assets/js/template.js')}}"></script>
  <script src="{{asset('assets/js/settings.js')}}"></script>
  <script src="{{asset('assets/js/todolist.js')}}"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="{{asset('assets/vendors/typeahead.js/typeahead.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendors/select2/select2.min.js')}}"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="{{asset('assets/js/file-upload.js')}}"></script>

  <script src="{{asset('assets/js/typeahead.js')}}"></script>
  {{-- <script src="{{asset('assets/js/select2.js')}}"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
  <script>
    var statusAlert = document.getElementById('statusAlert');
    if (statusAlert) {
      setTimeout(function() {
        statusAlert.style.display = 'none';
      }, 1050); 
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
$(document).ready(function(){
    $('#myTable').DataTable({
        "paging": false, // Désactiver la pagination
        "language": {
            "sProcessing":     "Traitement en cours...",
            "sSearch":         "Rechercher&nbsp;:",
            "sLengthMenu":     "Afficher _MENU_ éléments",
            "sInfo":           "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "sInfoEmpty":      "Affichage de 0 à 0 sur 0 entrées",
            "sInfoFiltered":   "(filtré à partir de _MAX_ entrées au total)",
            "sInfoPostFix":    "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords":    "Aucun résultat trouvé",
            "sEmptyTable":     "Aucune donnée disponible dans le tableau",
            "oPaginate": {
                "sPrevious":   "Précédent",
                "sNext":       "Suivant"
            },
            "oAria": {
                "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            }
        }
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
        // Initialiser Select2 sur le sélecteur de classes
        $('.js-example-basic-multiple').select2();

        $('#classSelect').on('change', function() {
            var codeClass = $(this).val();
            $.ajax({
                url: '/get-eleves/' + codeClass,
                type: 'GET',
                success: function(data) {
                  $('#eleveSelect').empty();
                  if (data.length > 0) {
                    $('#eleveSelect').append('<option value="">Sélectionner un élève</option>');
                    $.each(data, function(index, eleve) {
                        $('#eleveSelect').append('<option value="' + eleve.MATRICULE + '">' + eleve.NOM + ' ' + eleve.PRENOM + '</option>');
                    });
                  } else {
                      $('#eleveSelect').append('<option value="">Aucun élève disponible</option>');
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
      $('.calcul-modal').css({'width': '800px'});
    });
    $('#closecalculate').on('click', function() {
      $('#percentage').addClass('d-none');
      $('#calcul-one').removeClass('col-lg-8');
      $('.calcul-modal').css({'width': '500px'});

    });
    
  });
</script>
</body>

</html>
