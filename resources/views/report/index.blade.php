@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <!-- /.col-->
                <div class="col">
                    <div class="card-header bg-transparent border-bottom-0">
                        <div class="row d-flex justify-content-between">
                            <div>
                                <h4 class="fa fa-align-justify"><strong>Report Preview</strong></h4>
                            </div>
                            <div class="d-flex justify-content-around">
                                <div class="dropdown mr-2">
                                    <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu3" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="c-icon c-icon-sm cil-filter"></i></button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">Quantity Per</h6>
                                        <a class="dropdown-item" href="#">Month</a>
                                        <a class="dropdown-item" href="#">Quarter</a>
                                        <a class="dropdown-item" href="#">Year</a>
                                    </div>
                                </div>
                                <a href="{{ route('report.generate') }}" class="btn btn-md btn-primary mr-1"
                                    type="button">Generate
                                    Report</a>
                                <a href="{{ url('/stock/edit') }}">
                                    <button class="btn btn-md btn-secondary" type="button">Edit List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('report.pdf')
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>
        </div>
    @endsection
