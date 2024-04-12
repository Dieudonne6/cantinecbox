@extends('layouts.master')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <div class="col-md-8 mx-auto grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Connexion Scodelux</h4>
            <form>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Horizontal Form</h4>
                            <p class="card-description">
                              Horizontal form layout
                            </p>
                            <form class="forms-sample">
                              <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="exampleInputUsername2" placeholder="Username">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" id="exampleInputMobile" placeholder="Mobile number">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                  <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">Re Password</label>
                                <div class="col-sm-9">
                                  <input type="password" class="form-control" id="exampleInputConfirmPassword2" placeholder="Password">
                                </div>
                              </div>
                              <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input">
                                  Remember me
                                </label>
                              </div>
                              <button type="submit" class="btn btn-primary mr-2">Submit</button>
                              <button class="btn btn-light">Cancel</button>
                            </form>
                          </div>
                        </div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                    <button class="btn btn-light">Annuler</button>
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