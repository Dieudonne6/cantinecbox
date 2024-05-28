@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">
                <div class="toolbar"></div>
                <div class="material-datatables">
                    <div id="datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row"><div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="datatables_length">
                                <label class="form-group">Show <select name="datatables_length" aria-controls="datatables" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="10">10</option><option value="25">25</option>
                                    <option value="50">50</option><option value="-1">All</option>
                                </select> entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="datatables_filter" class="dataTables_filter">
                            <label class="form-group">
                                <input type="search" class="form-control form-control-sm" placeholder="Search records" aria-controls="datatables">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="datatables" cellspacing="0" width="100%" class="table table-striped table-no-bordered table-hover dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="datatables_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 170px;" aria-label="Name: activate to sort column descending" aria-sort="ascending">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 117px;" aria-label="Position: activate to sort column ascending">Position</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 142px;" aria-label="Office: activate to sort column ascending">Office</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 67px;" aria-label="Age: activate to sort column ascending">Age</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 87px;" aria-label="Date: activate to sort column ascending">Date</th>
                                    <th class="disabled-sorting text-right sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" style="width: 190px;" aria-label="Actions: activate to sort column ascending">Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">Name</th>
                                    <th rowspan="1" colspan="1">Position</th>
                                    <th rowspan="1" colspan="1">Office</th>
                                    <th rowspan="1" colspan="1">Age</th>
                                    <th rowspan="1" colspan="1">Start Date</th>
                                    <th class="text-right" rowspan="1" colspan="1">Actions</th>
                                </tr>
                            </tfoot>
                            <tbody><!---->
                                <tr role="row" class="odd ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Airi Satou</td>
                                    <td>Andrew Mike</td><td>Develop</td><td>2013</td>
                                    <td>99,225</td><td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="even ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Angelica Ramos</td>
                                    <td>John Doe</td>
                                    <td>Design</td>
                                    <td>2012</td>
                                    <td>89,241</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="odd ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Ashton Cox</td>
                                    <td>Alex Mike</td>
                                    <td>Design</td>
                                    <td>2010</td>
                                    <td>92,144</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="ng-star-inserted even">
                                    <td tabindex="0" class="sorting_1">Bradley Greer</td>
                                    <td>Mike Monday</td>
                                    <td>Marketing</td>
                                    <td>2013</td>
                                    <td>49,990</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="odd ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Brenden Wagner</td>
                                    <td>Paul Dickens</td>
                                    <td>Communication</td>
                                    <td>2015</td>
                                    <td>69,201</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="even ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Brielle Williamson</td>
                                    <td>Mike Monday</td>
                                    <td>Marketing</td>
                                    <td>2013</td>
                                    <td>49,990</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i>
                                        </a>
                                        <a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit">
                                            <i class="material-icons">dvr</i>
                                        </a>
                                        <a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </td>
                                </tr>
                                <tr role="row" class="odd ng-star-inserted">
                                    <td tabindex="0" class="sorting_1">Caesar Vance</td>
                                    <td>Mike Monday</td>
                                    <td>Marketing</td>
                                    <td>2013</td>
                                    <td>49,990</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like">
                                            <i class="material-icons">favorite</i></a><a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a><a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a></td></tr><tr role="row" class="even ng-star-inserted"><td tabindex="0" class="sorting_1">Cedric Kelly</td><td>Mike Monday</td><td>Marketing</td><td>2013</td><td>49,990</td><td class="text-right"><a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">favorite</i></a><a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a><a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a></td></tr><tr role="row" class="odd ng-star-inserted"><td tabindex="0" class="sorting_1">Charde Marshall</td><td>Mike Monday</td><td>Marketing</td><td>2013</td><td>49,990</td><td class="text-right"><a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">favorite</i></a><a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a><a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a></td></tr><tr role="row" class="even ng-star-inserted"><td tabindex="0" class="sorting_1">Colleen Hurst</td><td>Mike Monday</td><td>Marketing</td><td>2013</td><td>49,990</td><td class="text-right"><a href="javascript:void(0)" class="btn btn-link btn-info btn-just-icon like"><i class="material-icons">favorite</i></a><a href="javascript:void(0" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a><a href="javascript:void(0s" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a></td></tr></tbody></table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="datatables_info" role="status" aria-live="polite">Showing 1 to 10 of 40 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_full_numbers" id="datatables_paginate"><ul class="pagination"><li class="paginate_button page-item first disabled" id="datatables_first"><a href="#" aria-controls="datatables" data-dt-idx="0" tabindex="0" class="page-link">First</a></li><li class="paginate_button page-item previous disabled" id="datatables_previous"><a href="#" aria-controls="datatables" data-dt-idx="1" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="datatables" data-dt-idx="3" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="datatables" data-dt-idx="4" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item "><a href="#" aria-controls="datatables" data-dt-idx="5" tabindex="0" class="page-link">4</a></li><li class="paginate_button page-item next" id="datatables_next"><a href="#" aria-controls="datatables" data-dt-idx="6" tabindex="0" class="page-link">Next</a></li><li class="paginate_button page-item last" id="datatables_last"><a href="#" aria-controls="datatables" data-dt-idx="7" tabindex="0" class="page-link">Last</a></li></ul></div></div></div></div></div></div></div></div></div>

@endsection