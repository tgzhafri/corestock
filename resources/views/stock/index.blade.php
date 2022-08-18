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
                                <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                    data-target="#staticBackdrop">
                                    Upload Excel File</button>
                                <a href="{{ url('/stock/edit') }}">
                                    <button class="btn btn-md btn-secondary" type="button">Edit List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
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
                                <div class="card justify-content">
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
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row-->

        <!-- /.row-->
        <div class="row">
            <div class="col">
                <div class="card tableFixHead">
                    <table class="table table-responsive-sm table table-hover table-striped mb-0 table-borderless">
                        <thead class="thead-dark">
                            <form action="{{ route('stock.show') }}" method="GET">
                                <tr>
                                    <th>No.</th>
                                    <th class="col-1">Item Code</th>
                                    <th class="col-3">Drug / Non-Drug Name</th>
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
                                    <th class="col-1">Quantity Required
                                        {{-- per
                                        @if (isset($_GET['usage']))
                                            {{ $_GET['usage'] }}
                                        @else
                                            year
                                        @endif --}}
                                    </th>
                                    <th class="col-1">Status
                                        <select class="form-select btn btn-secondary dropdown-toggle" name="status"
                                            aria-label="Default select example" onchange="this.form.submit()">
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
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock['code'] }}</td>
                                    <td>{{ $stock['name'] }}</td>
                                    <td>{{ $stock['common_name'] }}</td>
                                    <td>{{ $stock['description'] }}</td>
                                    <td>{{ $stock['balance'] }}</td>
                                    {{-- usage per --}}
                                    <td>{{ $stock['annual_usage'] }}</td>
                                    {{-- quantity required --}}
                                    <td>{{ $stock['balance'] - $stock['annual_usage'] }}</td>
                                    <td class="text-center">
                                        @if ($stock['status'] == 'high')
                                            <span class="badge badge-success">High</span>
                                        @elseif ($stock['status'] == 'medium')
                                            <span class="badge badge-warning">Medium</span>
                                        @else
                                            <span class="badge badge-danger">Low</span>
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
@endsection
