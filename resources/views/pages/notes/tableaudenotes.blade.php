@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4">Intervalles à appliquer aux staistique</h2>
            <form>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="flex: 0 0 48%;">
                        <label for="champ1">Champ 1</label>
                        <input type="text" id="champ1" name="champ1" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 48%;">
                        <label for="champ2">Champ 2</label>
                        <input type="text" id="champ2" name="champ2" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="flex: 0 0 48%;">
                        <label for="champ3">Champ 3</label>
                        <input type="text" id="champ3" name="champ3" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 48%;">
                        <label for="champ4">Champ 4</label>
                        <input type="text" id="champ4" name="champ4" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="flex: 0 0 48%;">
                        <label for="champ5">Champ 5</label>
                        <input type="text" id="champ5" name="champ5" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 48%;">
                        <label for="champ6">Champ 6</label>
                        <input type="text" id="champ6" name="champ6" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="flex: 0 0 48%;">
                        <label for="champ7">Champ 7</label>
                        <input type="text" id="champ7" name="champ7" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 48%;">
                        <label for="champ8">Champ 8</label>
                        <input type="text" id="champ8" name="champ8" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div style="flex: 0 0 48%;">
                        <label for="champ9">Champ 9</label>
                        <input type="text" id="champ9" name="champ9" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 48%;">
                        <label for="champ10">Champ 10</label>
                        <input type="text" id="champ10" name="champ10" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                </div>
                <button type="submit">Soumettre</button>
            </form>
        <div>
    <div>
@endsection
