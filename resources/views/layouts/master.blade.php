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

  <script src="{{ asset('davidshimjs-qrcodejs-04f46c6/qrcode.js') }}"></script>

  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{asset('assets/vendors/select2/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('assets/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" />

  
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
  <script src="{{asset('assets/vendors/typeahead.js/typeahead.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendors/select2/select2.min.js')}}"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="{{asset('assets/js/file-upload.js')}}"></script>

  <script src="{{asset('assets/js/typeahead.js')}}"></script>
  <script src="{{asset('assets/js/select2.js')}}"></script>
  <script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
  
  

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


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 

</body>

</html>
