@extends('layouts.master')

@section('content')
<style>
  #mainTable th, #mainTable td {
    text-align: center;
    padding: 8px;
  }
  .table { width: 100%; border-collapse: collapse; }
  .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; }
  #mainTable th {
    background-color: #f2f2f2;
  }
  .interval-section td {
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>

</style>
<div class="container">
  <div class="card shadow-sm p-4">
    <div class="row">
      <div class="col-md-2 mb-3">
        <select class="js-example-basic-multiple w-100" id="tableSelect4" onchange="redirectWithSelection()">         
          @foreach ($classe as $classeOption)
          <option value="{{ $classeOption->CODECLAS }}" 
            {{ request()->query('classe') == $classeOption->CODECLAS ? 'selected' : '' }}>
            {{ $classeOption->CODECLAS }}
          </option>
          @endforeach
        </select>
      </div>
      
      <div class="col-md-3 mb-3">
        <select class="js-example-basic-multiple w-100" id="tableSelect5" onchange="redirectWithSelection()">
          @for ($i = 1; $i <= 9; $i++)
          <option value="{{ $i }}" 
          {{ request()->query('periode') == $i ? 'selected' : '' }}>
          {{ $i }}ème Période
        </option>
        @endfor
      </select>
    </div>
    
    <div class="col-md-2 mb-3">
      <select class="js-example-basic-multiple w-100" id="tableSelect6" onchange="redirectWithSelection()">
        @foreach (['DEV1', 'DEV2', 'DEV3', 'TEST', 'MS1'] as $note)
        <option value="{{ $note }}" 
        {{ request()->query('note') == $note ? 'selected' : '' }}>
        {{ $note }}
      </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3 mb-3">
    <button class="btn btn-primary">Calculer moyennes</button>
    
  </div>
  <div class="col-md-2 mb-3">
    <button onclick="printTables()" class="btn btn-primary">Imprimer</button>
    
  </div>
  
</div>
<div class="table-responsive mb-4">
  <table class="table" id="mainTable">
    <thead>
      <tr>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Prénom</th>
        @foreach ($matieres as $matiere)
        <th>{{ $matiere->NOMCOURT }} ({{ $matiere->COEF }})</th>
        @endforeach
        <th>M.SEM</th>
        <th>M.AN</th>
        <th>RANG</th>
      </tr>
    </thead>
    
    <tbody>
      <!-- Affichage des notes par élève -->
      @foreach ($eleves as $eleve)
      <tr>
        <td>{{ $eleve->MATRICULE }}</td>
        <td>{{ $eleve->NOM }}</td>
        <td>{{ $eleve->PRENOM }}</td>
        @foreach ($matieres as $matiere)
        @php
        $noteKey = $eleve->MATRICULE . '-' . $matiere->CODEMAT;
        $noteValue = $notes[$noteKey]->$selectedEvaluation ?? '-';
        @endphp
        <td>{{ $noteValue }}</td>
        @endforeach
        <td>{{ $selectedEvaluation === 'MS1' ? ($eleve->MSEM != -1 && $eleve->MSEM != 0 ? $eleve->MSEM : '**') : '**' }}</td>
        <td>**</td>
        <td>{{ $selectedEvaluation === 'MS1' ? ($eleve->RANG != -1 && $eleve->RANG != 0 ? $eleve->RANG : '**') : '**' }}</td>
      </tr>
      @endforeach
      <!-- Affichage des plus faibles et plus fortes moyennes alignées avec les matières -->
      <tr class="interval-section">
        <td colspan="3">Plus faibles moyennes</td>
        @foreach ($matieres as $matiere)
        <td>{{ $moyennes[$matiere->CODEMAT]['min'] ?? 0 }}</td>
        @endforeach
        <td colspan="3"></td>
      </tr>
      <tr class="mb-3">
        <td colspan="3">Plus fortes moyennes</td>
        @foreach ($matieres as $matiere)
        <td>{{ $moyennes[$matiere->CODEMAT]['max'] ?? 0 }}</td>
        @endforeach
        <td colspan="3"></td>
      </tr>
      
      <!-- Affichage des intervalles alignés avec les matières -->
      @foreach ($intervals as $index => $interval)
      <tr>
        <td colspan="3">Nb moyennes de {{ $interval['min'] }} à {{ $interval['max'] }}</td>
        @foreach ($matieres as $matiere)
        <td>{{ $moyenneCounts[$matiere->CODEMAT][$index] ?? 0 }}</td>
        @endforeach
        <td colspan="3"></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  
</div>


</div>
</div>
<script>
  function redirectWithSelection() {
    const classe = document.getElementById("tableSelect4").value; // Récupère la classe sélectionnée
    const periode = document.getElementById("tableSelect5").value; // Récupère la matière sélectionnée
    const note = document.getElementById("tableSelect6").value; // Récupère la matière sélectionnée
    
    let url = '/filtertablenotes'; // URL de redirection
    let params = [];
    
    if (classe) params.push(`classe=${classe}`); // Ajoute le paramètre de classe si sélectionné
    if (periode) params.push(`periode=${periode}`); // Ajoute le paramètre de matière si sélectionné
    if (note) params.push(`note=${note}`);
    if (params.length > 0) {
      url += '?' + params.join('&'); 
    }
    
    window.location.href = url;
  }
  
  // Ajoute les écouteurs d'événements pour les deux sélecteurs
  document.getElementById("tableSelect4").addEventListener("change", redirectWithSelection);
  document.getElementById("tableSelect5").addEventListener("change", redirectWithSelection);
  document.getElementById("tableSelect6").addEventListener("change", redirectWithSelection);
  
  function printTables() {
    // Créer une nouvelle fenêtre pour l'impression
    const printWindow = window.open('', '_blank');
    const tables = document.getElementById('table1').outerHTML +
    document.getElementById('table2').outerHTML +
    document.getElementById('table3').outerHTML;
    
    // Style pour les tableaux
    const style = `
        <style>
            body { font-family: Arial, sans-serif; }
            .table { width: 100%; border-collapse: collapse; }
            .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; }
        </style>
    `;
    
    // Insérer le contenu HTML et le style dans la fenêtre d'impression
    printWindow.document.write(`<html><head><title>Impression des tableaux</title>${style}</head><body>${tables}</body></html>`);
    
    printWindow.document.close();
    printWindow.print();
  }
</script>
@endsection
