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
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              @if(Session::has('status'))
              <div id="statusAlert" class="alert alert-succes btn-primary">
              {{ Session::get('status')}}
              </div>
            @endif
              <h3 class="font-weight-light text-center text-uppercase">Connectez-vous</h3>
                <form action="{{url('logins')}}" class="pt-3" method="POST">
                  {{csrf_field() }}
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="login_usercontrat" aria-label="Default input example" placeholder="Sélectionnez un utilisateur">
                  </div>
                  
                <div class="form-group">
                  <input type="password" name="password_usercontrat" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Mot de passe">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Connexion</button>
                </div>
            
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
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
  {{-- <script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script> --}}
  
  

  <!-- End custom js for this page-->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 

</body>

</html>


