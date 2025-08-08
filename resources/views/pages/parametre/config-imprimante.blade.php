@extends('layouts.master')

@section('content')
<div class="col-100 grid-margin">
    <div class="card">
      <div>
        <style>
          .btn-arrow {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: transparent !important;
            border: none !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            cursor: pointer !important;
            font-size: 17px !important;
            color: #b51818 !important;
          }

          .btn-arrow:hover {
            color: #b700ff !important;
          }
        </style>
        <button type="button" class="btn btn-arrow" onclick="window.history.back();" aria-label="Retour">
          <i class="fas fa-arrow-left"></i> Retour
        </button>
        <br><br>
      </div>

<style>
    .print-dialog {
        width: 500px;
        margin: 30px auto;
        border: 1px solid #ccc;
        padding: 20px;
        font-family: "Segoe UI", sans-serif;
        background-color: #f3f3f3;
    }

    .group-box {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 15px;
    }

    .group-box legend {
        font-size: 14px;
        font-weight: bold;
        width: auto;
        padding: 0 5px;
    }

    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .form-row label {
        width: 110px;
        font-size: 13px;
        margin-right: 5px;
    }

    .form-row .form-control, .form-row select {
        flex: 1;
        font-size: 13px;
        height: 28px;
    }

    .readonly-field {
        background-color: #e9ecef;
    }

    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 5px;
    }

    .bottom-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }
</style>

<div class="print-dialog">
    <form action="#" method="POST">
        @csrf

        {{-- Bloc Imprimante --}}
         <fieldset class="group-box">
            <legend>Imprimante</legend>

            <div class="form-row">
                <label for="printer_name">Nom :</label>
                <select name="printer_name" id="printer_name" class="form-control">
                    <option value="pdf" selected>Microsoft Print to PDF</option>
                    <option value="canon">Canon LBP 2900</option>
                    <option value="hp">HP OfficeJet Pro</option>
                    <option value="epson">EPSON L3110</option>
                </select>
            </div>

            <div class="form-row">
                <label>État :</label>
                <input type="text" id="etat" class="form-control readonly-field" readonly>
            </div>

            <div class="form-row">
                <label>Type :</label>
                <input type="text" id="type" class="form-control readonly-field" readonly>
            </div>

            <div class="form-row">
                <label>Emplacement :</label>
                <input type="text" id="emplacement" class="form-control readonly-field" readonly>
            </div>

            <div class="form-row">
                <label>Commentaire :</label>
                <input type="text" id="commentaire" class="form-control readonly-field" readonly>
            </div>

            <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#printerPropertiesModal">
                Propriétés...
            </button>
        </fieldset>

        {{-- Papier et Orientation --}}
        <div class="d-flex justify-content-between">
            <fieldset class="group-box" style="width: 48%;">
                <legend>Papier</legend>
                <div class="form-row">
                    <label for="paper_size">Taille :</label>
                    <select name="paper_size" id="paper_size" class="form-control">
                        <option selected>A4</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Source :</label>
                    <input type="text" class="form-control readonly-field" readonly>
                </div>
            </fieldset>

            <fieldset class="group-box" style="width: 48%;">
                <legend>Orientation</legend>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="orientation" value="portrait" checked> Portrait
                    </label>
                    <label>
                        <input type="radio" name="orientation" value="paysage"> Paysage
                    </label>
                </div>
            </fieldset>
        </div>

        <div class="bottom-buttons">
            <button type="button" class="btn btn-secondary">Réseau...</button>
            <div>
                <button type="submit" class="btn btn-primary">OK</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>

{{-- Modal des propriétés d’impression --}}
<div class="modal fade" id="printerPropertiesModal" tabindex="-1" aria-labelledby="printerPropertiesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printerPropertiesLabel">Propriétés de l’imprimante</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom :</strong> <span id="modal_printer_name"></span></p>
        <p><strong>Type :</strong> <span id="modal_printer_type"></span></p>
        <p><strong>État :</strong> <span id="modal_printer_etat"></span></p>
        <p><strong>Emplacement :</strong> <span id="modal_printer_emplacement"></span></p>
        <p><strong>Commentaire :</strong> <span id="modal_printer_commentaire"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

{{-- Script --}}
<script>
    const printers = {
        pdf: {
            etat: 'Prêt',
            type: 'Microsoft Print To PDF',
            emplacement: 'PORTPROMPT:',
            commentaire: ''
        },
        canon: {
            etat: 'En veille',
            type: 'Canon CAPT USB',
            emplacement: 'USB001',
            commentaire: 'Imprimante locale Canon'
        },
        hp: {
            etat: 'Prêt',
            type: 'HP Universal Print Driver',
            emplacement: 'USB002',
            commentaire: 'HP sur port USB'
        },
        epson: {
            etat: 'Occupée',
            type: 'Epson ESC/P-R',
            emplacement: 'WIFI',
            commentaire: 'Connexion réseau sans fil'
        }
    };

    function updatePrinterFields(key) {
        const selected = printers[key];
        if (selected) {
            document.getElementById('etat').value = selected.etat;
            document.getElementById('type').value = selected.type;
            document.getElementById('emplacement').value = selected.emplacement;
            document.getElementById('commentaire').value = selected.commentaire;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('printer_name');
        updatePrinterFields(select.value);

        select.addEventListener('change', function () {
            updatePrinterFields(this.value);
        });

        document.querySelector('[data-bs-target="#printerPropertiesModal"]').addEventListener('click', function () {
            const key = select.value;
            const selected = printers[key];

            document.getElementById('modal_printer_name').textContent = select.options[select.selectedIndex].text;
            document.getElementById('modal_printer_type').textContent = selected.type;
            document.getElementById('modal_printer_etat').textContent = selected.etat;
            document.getElementById('modal_printer_emplacement').textContent = selected.emplacement;
            document.getElementById('modal_printer_commentaire').textContent = selected.commentaire;
        });
    });
</script>
@endsection
