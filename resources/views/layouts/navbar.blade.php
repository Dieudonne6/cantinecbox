<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex justify-content-center">
    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
      <a class="navbar-brand brand-logo" href="{{url('/vitrine')}}">SchoolBOX</a>
      <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo"/></a>
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="typcn typcn-th-menu"></span>
      </button>
    </div>
  </div>
 
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="typcn typcn-th-menu"></span>
    </button>
    {{-- <div>
      @if (Session::has('nom_user') || Session::has('prenom_user'))
      <h5 class="mb-0">
          Bienvenue {{ Session::get('nom_user') }} {{ Session::get('prenom_user') }}
      </h5>
      @endif
    </div> --}}
    <div class="dropdown text-end">
      <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle show" data-bs-toggle="dropdown" aria-expanded="true">
        {{ $image = Session::get('image') }}
        @if($image)
        <img src="data:image/jpeg;base64,{{ base64_encode($image) }}" alt="Logo" width="40" height="40" class="rounded-circle"> {{ Session::get('nom_user') }}
        @else
        <img src="{{asset('assets/logo.webp')}}" alt="Logo" width="32" height="32" class="rounded-circle"> {{ Session::get('nom_user') }}
        @endif 
      </a>   
      <ul class="dropdown-menu text-small" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0.5px, 34px, 0px);">
        <li><a class="dropdown-item" href="{{ route('profile.show') }}" data-allow-readonly>Modifier Profil</a></li>
        <li><a class="dropdown-item" href="{{ route('password.show') }}" data-allow-readonly>Modifier le mot de passe </a></li>
        <li>
          <form action="{{ route('logout') }}" method="POST" class="logout-form" data-allow-readonly>
            @csrf
            <button type="submit" class="nav-logout logout-btn" data-allow-readonly>Déconnexion</button>            
          </form>
        </li>
      </ul>
    </div>

  </div>
</nav>