@extends('layouts.master')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {{-- <div class="form-group">
                    <label>Single select box using select 2</label>
                    <select class="js-example-basic-single w-100">
                        <option value="AppppL">Alabama</option>
                        <option value="WYop">Wyoming</option>
                        <option value="AolM">America</option>
                        <option value="CppA">Canada</option>
                        <option value="RppU">Russia</option>
                    </select>
                </div> --}}
                <h4 class="card-title">Toutes les classes</h4>
                <div class="form-group">
                    <select class="js-example-basic-single w-100" onchange="window.location.href=this.value">
                        {{-- <option disabled selected value="">Toutes les classes</option> --}}
                        @foreach ($classe as $classes)
                            {{-- <option value="eleve/{{$classes->CODECLAS}}">{{$classes->CODECLAS}}</a></option> --}}

                            <option value="{{ $classes->CODECLAS }}">{{ $classes->CODECLAS }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="table-responsive ">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Classes
                                </th>
                                <th>
                                    Elève
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filterEleve as $filterEleves)
                                <tr>
                                    <td>
                                        {{ $filterEleves->CODECLAS }}
                                    </td>
                                    <td>
                                        {{ $filterEleves->NOM }} {{ $filterEleves->PRENOM }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
