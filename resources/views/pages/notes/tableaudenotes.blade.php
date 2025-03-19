@extends('layouts.master')

@section('content')
<div class="container">
  <div class="card shadow-sm p-4">
    <div>
      <style>
          .btn-arrow {
              position: absolute;
              top: 0px;
              /* Ajustez la position verticale */
              left: 0px;
              /* Positionnez à gauche */
              background-color: transparent !important;
              border: 1px !important;
              text-transform: uppercase !important;
              font-weight: bold !important;
              cursor: pointer !important;
              font-size: 17px !important;
              /* Taille de l'icône */
              color: #b51818 !important;
              /* Couleur de l'icône */
          }
  
          .btn-arrow:hover {
              color: #b700ff !important;
              /* Couleur au survol */
          }
      </style>
      <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
      </button>   
      <br>
      <br>                                   
  </div>
  
    <h3 class="text-center mb-4">Intervalles à appliquer aux statistiques</h3>
    <div class="row">
      <div class="col-lg-6 mx-auto">
       
        <form action="{{ route('filtertableaunotes') }}" method="POST" class="text-center">
          @csrf
          <div class="row gx-2 mb-2">
            <input type="number" id="interval1" name="interval1" class="form-control w-50" value="0.00" onchange="updateInterval(1)" />
            <input type="number" id="interval2" name="interval2" class="form-control w-50" value="5.00" onchange="updateInterval(2)" />
          </div>
          <div class="row g-2 mb-2">
            <input type="number" id="interval3" name="interval3" class="form-control w-50" value="5.00" onchange="updateInterval(3)" />
            <input type="number" id="interval4" name="interval4" class="form-control w-50" value="10.00" onchange="updateInterval(4)" />
          </div>
          <div class="row g-2 mb-2">
            <input type="number" id="interval5" name="interval5" class="form-control w-50" value="10.00" onchange="updateInterval(5)" />
            <input type="number" id="interval6" name="interval6" class="form-control w-50" value="12.00" onchange="updateInterval(6)" />
          </div>
          <div class="row g-2 mb-2">
            <input type="number" id="interval7" name="interval7" class="form-control w-50" value="12.00" onchange="updateInterval(7)" />
            <input type="number" id="interval8" name="interval8" class="form-control w-50" value="15.00" onchange="updateInterval(8)" />
          </div>
          <div class="row g-2 mb-2">
            <input type="number" id="interval9" name="interval9" class="form-control w-50" value="15.00" onchange="updateInterval(9)" />
            <input type="number" id="interval10" name="interval10" class="form-control w-50" value="21.00" onchange="updateInterval(10)" />
          </div>
          <button type="submit" class="btn btn-primary">Afficher le tableau</button>
        </form>
      </div>
    </div>
  </div>
</div>
   <script>
     function updateInterval(index) {
    // Obtenir la valeur de l'input modifié
    let currentInput = document.getElementById(`interval${index}`);
    let nextInput = document.getElementById(`interval${index + 1}`);
    
    // Vérifier si le prochain input existe
    if (nextInput) {
      // Mettre à jour la valeur du prochain input pour être égale à la valeur actuelle
      nextInput.value = currentInput.value;
    }
  }
   </script>
        @endsection
        