@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel"><strong>Upload File</strong> here</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data">
                                <div class="card justify-content">
                                    {{ csrf_field() }}
                                    <input id="file-input" type="file" name="file" class="align-self-center card-body">
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row col-lg-12 d-flex justify-content-between">
                            <div>
                                <h4><strong>List of Stock Supplier</strong></h4>
                            </div>
                            <div>
                                <a href="{{ url('/supplier/edit') }}">
                                    <button class="btn btn-md btn-primary" type="button">Edit List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-bordered table-striped table-sm">
                            <thead>
                                <form action="{{ route('stock.show') }}" method="GET">
                                    <tr>
                                        <th class="">No.</th>
                                        <th class="col-2">Item Code</th>
                                        <th class="col-3">Drug / Non-Drug Name</th>
                                        <th class="col-2">Common Name</th>
                                        <th class="col-2">Packaging Description</th>
                                        <th class="col-3">Suppliers</th>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock['id'] }}</td>
                                        <td>{{ $stock['code'] }}</td>
                                        <td>{{ $stock['name'] }}</td>
                                        <td>{{ $stock['common_name'] }}</td>
                                        <td>{{ $stock['description'] }}</td>
                                        <td>
                                            @foreach ($stock->supplier as $supplier)
                                                <h6>
                                                    <li>{{ $supplier->name }}</li>
                                                </h6>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row col-lg-12 d-flex justify-content-between">
                            <div>
                                <h4><strong>List of Stock Supplier</strong></h4>
                            </div>
                            <div>
                                <a href="{{ url('/supplier/edit') }}">
                                    <button class="btn btn-md btn-primary" type="button">Edit List</button>
                                </a>
                            </div>
                        </div>
                    </div>
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
