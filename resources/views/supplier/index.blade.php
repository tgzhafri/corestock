@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <!-- /.row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card-header bg-transparent border-bottom-0">
                    <div class="row d-flex justify-content-between">
                        <div>
                            <h4><strong>Stock Supplier List</strong></h4>
                        </div>
                        <div>
                            <a href="{{ url('/supplier/edit') }}">
                                <button class="btn btn-md btn-primary" type="button">Edit List</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card tableFixHead">
                    <table class="table table-responsive-sm table-borderless table-striped mb-0">
                        <thead class="thead-dark">
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
                            @foreach ($stocks as $key => $stock)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
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
            <!-- /.col-->
        </div>
        <!-- /.row-->
    </div>
    </div>
@endsection

@section('javascript')
@endsection
