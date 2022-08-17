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
                            <div>
                                <a href="{{ route('report.generate') }}" class="btn btn-md btn-primary" type="button">Generate
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
