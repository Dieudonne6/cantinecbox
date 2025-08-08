@extends('layouts.master')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div>
      <style>
        .btn-arrow {
          position: absolute; top: 0; left: 0;
          background-color: transparent !important;
          border: none !important;
          text-transform: uppercase !important;
          font-weight: bold !important;
          cursor: pointer !important;
          font-size: 17px !important;
          color: #b51818 !important;
        }
        .btn-arrow:hover { color: #b700ff !important; }
      </style>

      <button type="button" class="btn btn-arrow" onclick="window.history.back();">
        <i class="fas fa-arrow-left"></i> Retour
      </button>
      <br><br>
    </div>

    <div class="card-body" id="cardBody">
      <h5 class="mb-2">Importation des élèves</h5>

      <!-- Zone d'erreur -->
      <div id="errorSection" class="mt-4"></div>

      <!-- Upload + boutons -->
      <div class="col-auto">
        <div class="d-flex align-items-center">
          <input type="file" class="form-control me-2" id="fileInput" accept=".xlsx, .xls, .csv" />
          <button id="btnPreview" class="btn btn-primary me-2">Prévisualiser</button>
          <button id="btnImport"  class="btn btn-primary">Importer</button>
        </div>
      </div>

      <!-- Preview table (scrollable) -->
      <div class="table-responsive mt-4" id="previewSection" style="max-height:60vh; overflow:auto;">
        <table class="table table-bordered table-striped" id="excelTable">
          <!-- thead / tbody injectés par JS -->
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Barre de scroll horizontale fixe (sera déplacée dans <body> si besoin) -->
<div id="hScroll" aria-hidden="true" style="display:none">
  <div id="hScrollContent"></div>
</div>

<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ---------- Elements ---------- */
  const fileInput    = document.getElementById('fileInput');
  const btnPreview   = document.getElementById('btnPreview');
  const btnImport    = document.getElementById('btnImport');
  const errorSection = document.getElementById('errorSection');
  const previewSection = document.getElementById('previewSection'); // .table-responsive
  const excelTable   = document.getElementById('excelTable');

  // Fixed scrollbar elements
  let hScroll = document.getElementById('hScroll');
  let hScrollContent = document.getElementById('hScrollContent');

  // If hScroll is not in body, move it so it remains fixed relative to viewport
  if (hScroll && hScroll.parentNode !== document.body) {
    document.body.appendChild(hScroll);
  }

  // Basic styling for the fixed scrollbar (you can adjust)
  const style = document.createElement('style');
  style.innerHTML = `
    #hScroll {
      position: fixed;
      left: 12px;
      right: 12px;
      bottom: 12px;
      height: 18px;
      overflow-x: auto;
      overflow-y: hidden;
      background: rgba(0,0,0,0.03);
      z-index: 99999;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      -webkit-overflow-scrolling: touch;
    }
    #hScroll::-webkit-scrollbar { height: 12px; }
    #hScrollContent { height: 1px; }
    .card-body.with-fixed-scroll { padding-bottom: 84px; } /* avoid overlap */
  `;
  document.head.appendChild(style);

  /* ---------- Events binding ---------- */
  btnPreview.addEventListener('click', previewExcel);
  fileInput.addEventListener('change', previewExcel);
  btnImport.addEventListener('click', importExcel);
  window.addEventListener('resize', () => setTimeout(updateFixedScrollbarAlwaysVisible, 80));

  // Sync flags to avoid circular updates
  let syncingFromTable = false;
  let syncingFromBar = false;

  // When previewSection scrolls horizontally, update fixed bar
  previewSection.addEventListener('scroll', () => {
    if (syncingFromBar) return;
    syncingFromTable = true;
    hScroll.scrollLeft = previewSection.scrollLeft;
    setTimeout(() => syncingFromTable = false, 10);
  });

  // When user scrolls the fixed bar, scroll the previewSection
  hScroll.addEventListener('scroll', () => {
    if (syncingFromTable) return;
    syncingFromBar = true;
    previewSection.scrollLeft = hScroll.scrollLeft;
    setTimeout(() => syncingFromBar = false, 10);
  });

  /* ---------- Helper to show errors ---------- */
  function buildErrorHtml(message, errors) {
    let html = `<div class="alert alert-danger"><p>${message || 'Erreur'}</p>`;
    if (Array.isArray(errors) && errors.length) {
      html += '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
    }
    html += '</div>';
    return html;
  }

  /* ---------- Preview (SheetJS -> table) ---------- */
  function previewExcel() {
    errorSection.innerHTML = '';
    const file = fileInput.files[0];
    if (!file) {
      errorSection.innerHTML = `<div class="alert alert-danger">Veuillez sélectionner un fichier Excel.</div>`;
      return;
    }

    const reader = new FileReader();
    reader.readAsBinaryString(file);

    reader.onload = function(e) {
      const wb = XLSX.read(e.target.result, { type: 'binary' });
      const ws = wb.Sheets[wb.SheetNames[0]];

      // Generate full HTML table with sheet_to_html
      const fullHtml = XLSX.utils.sheet_to_html(ws, { editable: false });

      // Parse and extract the table
      const parser = new DOMParser();
      const doc = parser.parseFromString(fullHtml, 'text/html');
      const generatedTable = doc.querySelector('table');

      // Replace our table's thead/tbody
      excelTable.querySelector('thead')?.remove();
      excelTable.querySelector('tbody')?.remove();

      if (generatedTable) {
        const thead = generatedTable.querySelector('thead');
        const tbody = generatedTable.querySelector('tbody');
        if (thead) excelTable.appendChild(thead.cloneNode(true));
        if (tbody) excelTable.appendChild(tbody.cloneNode(true));
      } else {
        excelTable.innerHTML = '<tbody><tr><td>Aucune donnée détectée</td></tr></tbody>';
      }

      // After DOM injection let the browser compute layout, then update fixed scrollbar
      setTimeout(updateFixedScrollbarAlwaysVisible, 80);
    };
  }

  /* ---------- Import (fetch + error handling) ---------- */
  function importExcel() {
    errorSection.innerHTML = '';
    if (!fileInput.files[0]) {
      errorSection.innerHTML = `<div class="alert alert-danger">Veuillez sélectionner un fichier Excel.</div>`;
      return;
    }

    const formData = new FormData();
    formData.append('excelFile', fileInput.files[0]);

    fetch("{{ route('eleves.import') }}", {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      body: formData
    })
    .then(res => res.json().then(payload => {
      if (!res.ok) return Promise.reject(payload);
      return payload;
    }))
    .then(data => {
      if (data.success) {
        errorSection.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
      } else {
        errorSection.innerHTML = buildErrorHtml(data.message, data.errors);
      }
    })
    .catch(err => {
      // err may be JSON payload or unexpected
      if (err && (err.message || err.errors)) {
        errorSection.innerHTML = buildErrorHtml(err.message, err.errors);
      } else {
        console.error('Import error:', err);
        errorSection.innerHTML = `<div class="alert alert-danger">Erreur lors de l'importation.</div>`;
      }
    });
  }

  /* ---------- Fixed scrollbar: always visible and synced ---------- */
  function updateFixedScrollbarAlwaysVisible() {
    // safety checks
    if (!excelTable || !previewSection || !hScroll || !hScrollContent) {
      return;
    }

    // compute widths
    const tableScrollWidth = excelTable.scrollWidth || 0;
    const containerWidth = previewSection.clientWidth || 0;

    // Force minimal width slightly larger than container to ensure a thumb is present
    const forcedWidth = Math.max(tableScrollWidth, containerWidth + 20);

    hScrollContent.style.width = forcedWidth + 'px';
    hScroll.style.display = 'block';
    document.getElementById('cardBody')?.classList.add('with-fixed-scroll');

    // sync positions
    hScroll.scrollLeft = previewSection.scrollLeft;
  }

  // expose for manual calls if needed (e.g. after server-side modifications)
  window.updateFixedScrollbarAlwaysVisible = updateFixedScrollbarAlwaysVisible;

  // initial call in case table pre-exists
  setTimeout(updateFixedScrollbarAlwaysVisible, 200);
});
</script>
@endsection
