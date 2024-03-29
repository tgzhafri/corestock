@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <div class="col">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="card-header bg-transparent border-bottom-0">
                        <div class="row d-flex justify-content-between">
                            <div class="btn-md">
                                <h4 class="fa fa-align-justify"><strong>Stock List</strong></h4>
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
                            <div>
                                <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                    data-target="#staticBackdrop">
                                    Upload Excel</button>
                                <a href="{{ url('/stock/edit') }}">
                                    <button class="btn btn-md btn-secondary" type="button">Edit</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import/Export Modal -->
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-primary">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel"><strong>Upload File</strong> here</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <a href="{{ route('stock.download') }}" class="btn btn-md btn-secondary col-lg-11">
                                            <i class="c-icon cil-cloud-download"></i> Download Template
                                        </a>
                                    </div>
                                    {{ csrf_field() }}
                                    <input id="file-input" type="file" name="file"
                                        class="align-self-center card-body">
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-around">
                                            <button class="btn btn-md btn-primary col-lg-5" type="submit">
                                                Submit</button>
                                            <button class="btn btn-md btn-danger col-lg-5" type="reset"> Reset</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Import/Export Modal -->
        </div>

        <!-- /.row-->
        <div class="row">
            <div class="col">
                <div class="card tableFixHead">
                    <table id="listTable"
                        class="table table-responsive-sm table table-hover table-striped mb-0 table-borderless">
                        <thead class="thead-dark">
                            <form action="{{ route('stock.show') }}" method="GET">
                                <tr id="tableHeader">
                                    <th>No.</th>
                                    <th class="col-1">Item Code</th>
                                    <th class="col-3">Name</th>
                                    <th class="col-2">Common Name</th>
                                    <th class="col-2">Packaging Description</th>
                                    <th class="col-1">Total Stock In (SKU)</th>
                                    <th class="col-1">Usage Per
                                        <select class="form-select btn btn-secondary dropdown-toggle" name="usage"
                                            aria-label="Default select example" onchange="this.form.submit()">
                                            <option value="year" @if (isset($_GET['usage']) && $_GET['usage'] == 'year') selected @endif>
                                                Year</option>
                                            <option value="quarter" @if (isset($_GET['usage']) && $_GET['usage'] == 'quarter') selected @endif>
                                                Quarter</option>
                                            <option value="month" @if (isset($_GET['usage']) && $_GET['usage'] == 'month') selected @endif>
                                                Month</option>
                                            <option value="week" @if (isset($_GET['usage']) && $_GET['usage'] == 'week') selected @endif>
                                                Week</option>
                                        </select>
                                    </th>
                                    <th class="col-1">Quantity Required per
                                        <span class="text-uppercase">{{ $_GET['usage'] ?? 'year' }}</span>
                                    </th>
                                    <th class="col-1">Status
                                        <select class="form-select btn btn-secondary dropdown-toggle" name="status"
                                            aria-label="Default select example" onchange="this.form.submit()">
                                            <option value="n/a" @if (isset($_GET['status']) && $_GET['status'] == 'n/a') selected @endif>
                                                N/A</option>
                                            <option value="all" @if (isset($_GET['status']) && $_GET['status'] == 'all') selected @endif>
                                                All</option>
                                            <option value="low" @if (isset($_GET['status']) && $_GET['status'] == 'low') selected @endif>
                                                Low</option>
                                            <option value="medium" @if (isset($_GET['status']) && $_GET['status'] == 'medium') selected @endif>
                                                Medium</option>
                                            <option value="high" @if (isset($_GET['status']) && $_GET['status'] == 'high') selected @endif>
                                                High</option>
                                        </select>
                                    </th>
                                </tr>
                            </form>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $index => $stock)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock['code'] }}</td>
                                    <td>{{ $stock['name'] }}</td>
                                    <td>{{ $stock['common_name'] }}</td>
                                    <td>{{ $stock['description'] }}</td>
                                    <td>{{ number_format($stock['balance']) }}</td>
                                    <td>{{ number_format($stock['annual_usage']) }}</td>
                                    <td>
                                        @if (number_format($stock['annual_usage'] - $stock['balance']) <= 0)
                                            {{ 0 }}
                                        @else
                                            {{ number_format($stock['annual_usage'] - $stock['balance']) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($stock['status'] == 'high')
                                            <span class="badge badge-success">High</span>
                                        @elseif ($stock['status'] == 'medium')
                                            <span class="badge badge-warning">Medium</span>
                                        @elseif ($stock['status'] == 'low')
                                            <span class="badge badge-danger">Low</span>
                                        @elseif ($stock['status'] == 'n/a')
                                            <span class="badge badge-secondary">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if ($stocks->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center">No data found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- /.row-->
    </div>
    </div>
@endsection

@section('javascript')
    @include('stock.searchTable')
@endsection
