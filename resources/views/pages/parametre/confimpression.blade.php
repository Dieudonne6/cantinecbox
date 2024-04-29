@extends('layouts.master')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">


            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Imprimante</h4>
                    <p class="card-description">
                      Add classes like <code>.form-control-lg</code> and <code>.form-control-sm</code>.
                    </p>
                    <div class="form-group">
                      <label>Large input</label>
                      <input type="text" class="form-control form-control-lg" placeholder="Username" aria-label="Username">
                    </div>
                    <div class="form-group">
                      <label>Default input</label>
                      <input type="text" class="form-control" placeholder="Username" aria-label="Username">
                    </div>
                    <div class="form-group">
                      <label>Small input</label>
                      <input type="text" class="form-control form-control-sm" placeholder="Username" aria-label="Username">
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Select size</h4>
                    <p class="card-description">
                      Add classes like <code>.form-control-lg</code> and <code>.form-control-sm</code>.                    
                    </p>
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Large select</label>
                      <select class="form-control form-control-lg" id="exampleFormControlSelect1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect2">Default select</label>
                      <select class="form-control" id="exampleFormControlSelect2">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Small select</label>
                      <select class="form-control form-control-sm" id="exampleFormControlSelect3">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Checkbox Controls</h4>
                    <p class="card-description">Checkbox and radio controls (default appearance is in primary color)</p>
                    <form>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input">
                                Default
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Checked
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" disabled="">
                                Disabled
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" disabled="" checked="">
                                Disabled checked
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="">
                                Default
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" checked="">
                                Selected
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optionsRadios2" id="optionsRadios3" value="option3" disabled="">
                                Disabled
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optionsRadio2" id="optionsRadios4" value="option4" disabled="" checked="">
                                Selected and disabled
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-body">
                    <p class="card-description">Add class <code>.form-check-{color}</code> for checkbox and radio controls in theme colors</p>
                    <form>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Primary
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-success">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Success
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-info">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Info
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-danger">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Danger
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-warning">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" checked="">
                                Warning
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="ExampleRadio1" id="ExampleRadio1" checked="">
                                Primary
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-success">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="ExampleRadio2" id="ExampleRadio2" checked="">
                                Success
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-info">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="ExampleRadio3" id="ExampleRadio3" checked="">
                                Info
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-danger">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="ExampleRadio4" id="ExampleRadio4" checked="">
                                Danger
                              <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-warning">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="ExampleRadio5" id="ExampleRadio5" checked="">
                                Warning
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>


        </div>
    </div>
</div>

@endsection