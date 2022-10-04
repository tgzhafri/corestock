@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <div class="col">
                    <div class="card-header bg-transparent border-bottom-0">
                        <div class="row d-flex justify-content-between">
                            <div>
                                <h4><strong>Stock Supplier List</strong></h4>
                            </div>
                            <div>
                                <div class="input-group">
                                    <input id="searchInput" onkeyup="searchTable()" type="search" class="form-control rounded"
                                        placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                                    <button type="button" class="btn btn-secondary">Go!</button>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                    data-target="#staticBackdrop">
                                    Upload Excel File</button>
                                <a href="{{ url('/supplier/edit') }}">
                                    <button class="btn btn-md btn-secondary" type="button">Edit List</button>
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
        <div class="row">
            <div class="col">
                <div class="card tableFixHead">
                    <table id="listTable" class="table table-responsive-sm table-borderless table-striped mb-0">
                        <thead class="thead-dark">
                            <form action="{{ route('stock.show') }}" method="GET">
                                <tr id="tableHeader">
                                    <th class="">No.</th>
                                    <th class="col-2">Item Code</th>
                                    <th class="col-3">Name</th>
                                    <th class="col-2">Common Name</th>
                                    <th class="col-2">Packaging Description</th>
                                    <th class="col-3">Suppliers</th>
                                </tr>
                            </form>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $key => $stock)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock['code'] }}</td>
                                    <td>{{ $stock['name'] }}</td>
                                    <td>{{ $stock['common_name'] }}</td>
                                    <td>{{ $stock['description'] }}</td>
                                    <td>
                                        @foreach ($stock->supplier as $supplier)
                                            <div>
                                                <li>{{ $supplier->name }}</li>
                                            </div>
                                        @endforeach
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
        </div>
    </div>
    <!-- /.col-->
@endsection

@section('javascript')
    @include('stock.searchTable')
@endsection
