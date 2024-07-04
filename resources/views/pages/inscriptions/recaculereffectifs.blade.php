@extends('layouts.master')

@section('content')


<div class="container">
    <div class="form-group row">
        <div class="col" style="margin-top: 18px">
            <div class="">
                <button type="submit" class="btn btn-primary">Recalculer effectifs</button>
            </div>
        </div>
        <div class="col-2">
        <label for="tableSelect">Sélectionnez un tableau :</label>
        <select class="form-select" id="tableSelect">
            <option value="table1">Effectif par classe</option>
            <option value="table2">Effectif par promotion</option>
            <option value="table3">Effectif par série</option>
            <option value="table4">Effectif alphabétique</option>
        </select>
        </div>
    </div>
    
    <div id="table1" class="table-container">
        <p><strong>Effectif par classe</strong><p>
        <table class="table table-bordered">
            <!-- En-têtes de tableau -->
            <thead class="table-header">
                <tr>
                    <th>CLASSES</th>
                    <th>EFFECTIF</th>
                    <th>FILLES</th>
                    <th>GARCONS</th>
                    <th>REDOUBLE</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>CE1</td>
                    <td>20</td>
                    <td>12</td>
                    <td>8</td>
                    <td>0</td>
                </tr>
            </tbody>
            <tfoot class="table-footer">
                <tr>
                    <td>Total</td>
                    <td>20</td>
                    <td>12</td>
                    <td>8</td>
                    <td>0</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="table2" class="table-container d-none">
        <p><strong>Effectif par promotion</strong><p>
        <table class="table table-bordered">
            <thead class="table-header">
                <tr>
                    <th>PROMOTION</th>
                    <th>Nb CLASSES</th>
                    <th>EFFECTIF</th>
                    <th>FILLES</th>
                    <th>GARCONS</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>CE1</td>
                    <td>3</td>
                    <td>44</td>
                    <td>26</td>
                    <td>18</td>
                </tr>
            </tbody>
            <tfoot class="table-footer">
                <tr>
                    <td>Total</td>
                    <td>3</td>
                    <td>44</td>
                    <td>26</td>
                    <td>18</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="table3" class="table-container d-none">
        <p><strong>Effectif par série</strong><p>
        <table class="table table-bordered">
            <thead class="table-header">
                <tr>
                    <th>SERIE</th>
                    <th>Nb CLASSES</th>
                    <th>EFFECTIF</th>
                    <th>FILLES</th>
                    <th>GARCONS</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>Aucun</td>
                    <td>24</td>
                    <td>268</td>
                    <td>131</td>
                    <td>137</td>
                </tr>
            </tbody>
            <tfoot class="table-footer">
                <tr>
                    <td>Total</td>
                    <td>24</td>
                    <td>268</td>
                    <td>131</td>
                    <td>137</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div id="table4" class="table-container d-none">
        <p><strong>Effectif alphabétique</strong><p>
        <table class="table table-bordered">
            <thead class="table-header">
                <tr>
                    <th>LETTRE</th>
                    <th>EFFECTIF</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                    <td>K</td>
                    <td>19</td>
                </tr>
            </tbody>
            <tfoot class="table-footer">
                <tr>
                    <td>Total</td>
                    <td>19</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


@endsection

<style>
    .table-header {
        background-color: #060e1600;
        color: #333;
        font-weight: bold;
    }

    .table-header th {
        padding: 10px;
        text-align: center;
        border-bottom: 2px solid #ddd;
    }


    .table-body td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table-footer {
        background-color: #e9ecef;
        color: #333;
        font-weight: bold;
    }

    .table-footer td {
        padding: 10px;
        text-align: center;
        border-top: 2px solid #ddd;

    }
</style>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableSelect').change(function() {
            var selectedTable = $(this).val();
            $('.table-container').addClass('d-none');
            $('#' + selectedTable).removeClass('d-none');
        });
    });
</script>
