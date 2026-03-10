@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    
    <div>
      <style>
          .btn-arrow {
              position: absolute;
              top: 0;
              left: 0;
              background-color: transparent !important;
              border: 1px !important;
              font-weight: bold !important;
              cursor: pointer !important;
              font-size: 17px !important;
              color: #b51818 !important;
          }

          .btn-arrow:hover {
              color: #b700ff !important;
          }

          select.form-control {
              color: #555 !important;
              background-color: #fff !important;
          }

          .file-drop {
              border: 2px dashed #4c85c1;
              border-radius: 8px;
              padding: 35px;
              text-align: center;
              cursor: pointer;
              transition: 0.25s;
              background: #f8fbff;
              color: #4c85c1;
              font-size: 16px;
              font-weight: 500;
          }

          .file-drop:hover {
              background-color: #eef5ff;
          }

          .file-drop.disabled {
              border-color: #ccc;
              background: #f3f3f3;
              color: #999;
              cursor: not-allowed;
          }

          .file-drop input {
              display: none;
          }

          .file-selected {
              border-color: #28a745 !important;
              background: #f0fff4 !important;
              color: #28a745 !important;
              font-weight: 600;
          }

          @keyframes shake {
              0% { transform: translateX(0); }
              25% { transform: translateX(-5px); }
              50% { transform: translateX(5px); }
              75% { transform: translateX(-5px); }
              100% { transform: translateX(0); }
          }

          .shake {
              animation: shake 0.3s;
              border-color: #dc3545 !important;
              color: #dc3545 !important;
          }
      </style>

      <button type="button" class="btn btn-arrow" onclick="window.history.back();">
          <i class="fas fa-arrow-left"></i> Retour
      </button>
      <br><br>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card-body">
      <h4 class="card-title">Importer des notes (fichier global)</h4>

      <form action="{{ route('previewImport') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mb-4">
            <div class="col-md-4">
                <label>Classe</label>
                <select class="form-control" id="classe" name="classe" required>
                    <option value="">Sélectionnez une classe</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->CODECLAS }}">{{ $classe->CODECLAS }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Période</label>
                <select class="form-control" id="periode" name="periode" required>
                    <option value="">Sélectionnez une période</option>
                    @for ($i = 1; $i <= 3; $i++)
                        <option value="{{ $i }}" {{ (old('periode',$current)==$i)?'selected':'' }}>
                            {{ $i==1 ? '1ère période' : $i.'ème période' }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <label>Fichier Excel des notes</label>
                <div class="file-drop disabled" id="dropZone">
                    <span id="dropText">Sélectionnez d’abord la classe et la période</span>
                    <input type="file" id="fileInput" name="fichier" accept=".xls,.xlsx" disabled>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-4">Importer</button>
        <br><br>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const classe = document.getElementById('classe');
    const periode = document.getElementById('periode');
    const drop = document.getElementById('dropZone');
    const input = document.getElementById('fileInput');
    const text = document.getElementById('dropText');

    function checkActivation() {
        if(classe.value && periode.value){
            drop.classList.remove('disabled');
            input.disabled = false;
            text.textContent = "Cliquer pour sélectionner le fichier Excel";
        }else{
            drop.classList.add('disabled');
            input.disabled = true;
            text.textContent = "Sélectionnez d’abord la classe et la période";
        }
    }

    classe.addEventListener('change', checkActivation);
    periode.addEventListener('change', checkActivation);

    drop.addEventListener('click', function(){
        if(input.disabled){
            drop.classList.add('shake');
            text.textContent = "⚠ Choisissez la classe et la période";

            setTimeout(()=>{
                drop.classList.remove('shake');
                text.textContent = "Sélectionnez d’abord la classe et la période";
            },1500);

            return;
        }

        input.click();
    });

    input.addEventListener('change', function(){
        if(input.files.length){
            text.textContent = input.files[0].name;
            drop.classList.add('file-selected');
        }
    });

});
</script>
@endsection