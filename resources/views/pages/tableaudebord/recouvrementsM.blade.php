@extends('layouts.master')
@section('content')

<div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" _msttexthash="150163" _msthash="230">Catégorie</label>
                          <div class="col-sm-9">
                            <select class="form-control">
                              <option _msttexthash="125775" _msthash="231">Catégorie 1</option>
                              <option _msttexthash="125970" _msthash="232">Catégorie 2</option>
                              <option _msttexthash="126165" _msthash="233">Catégorie 3</option>
                              <option _msttexthash="126360" _msthash="234">Catégorie4</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" _msttexthash="130377" _msthash="235">Adhésion</label>
                          <div class="col-sm-4">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios1" value="" checked=""><font _mstmutation="1" _msttexthash="58565" _msthash="236"> Libre </font><i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-5">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios2" value="option2"><font _mstmutation="1" _msttexthash="235456" _msthash="237"> Professionnel </font><i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

@endsection