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
                                <div class="input-group">
                                    <input id="searchInput" onkeyup="searchTable()" type="search"
                                        class="form-control rounded" placeholder="Search" aria-label="Search"
                                        aria-describedby="search-addon" />
                                    <button type="button" class="btn btn-secondary">Go!</button>
                                </div>
                            </div>
                            <div class="alert alert-primary mb-0 animated-alert fadeOut" tabindex="1">
                                Quantity per<span class='text-uppercase'> {{ request('usage') ?? 'year' }}</span>
                                & Status <span class='text-uppercase'> {{ request('status') ?? 'all' }}</span> selected
                            </div>
                            <div class="d-flex justify-content-around">
                                <div class="dropdown mr-2">
                                    <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu3" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="c-icon c-icon-sm cil-filter"></i></button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">Quantity Per</h6>
                                        <a class="dropdown-item"
                                            href="{{ route('report.show', ['usage' => 'month']) }}">Month</a>
                                        <a class="dropdown-item"
                                            href="{{ route('report.show', ['usage' => 'quarter']) }}">Quarter</a>
                                        <a class="dropdown-item"
                                            href="{{ route('report.show', ['usage' => 'year']) }}">Year</a>
                                    </div>
                                </div>
                                <div class="dropdown mr-2">
                                    <button class="btn btn-primary dropdown-toggle" id="dropdownMenu3" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Export</button>
                                    <div class="dropdown-menu">
                                        <h6 class="dropdown-header">Export as</h6>
                                        <a href="{{ route('report.excel', ['usage' => $_GET['usage'], 'status' => $_GET['status']]) }}"
                                            class="dropdown-item" type="button">Excel</a>
                                        <a href="{{ route('report.pdf', ['usage' => $_GET['usage'], 'status' => $_GET['status']]) }}"
                                            class="dropdown-item" type="button">PDF</a>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ url('/stock/edit') }}">
                                        <button class="btn btn-md btn-secondary" type="button">Edit</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('report.table')
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>
        </div>
    @endsection
