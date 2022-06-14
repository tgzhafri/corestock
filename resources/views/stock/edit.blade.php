@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <div class="col-lg-12">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ route('stock.save') }}" method="POST" enctype="multipart/form-data" id="formSave">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-12 d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-md btn-dark" data-toggle="modal"
                                            data-target="#addItemModal">
                                            <strong>Add Item</strong>
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('stock.index') }}" class="btn btn-md btn-secondary"
                                            type="button">Return</a>
                                        <button class="btn btn-md btn-primary" type="submit"><strong>Save</strong></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body overflow-auto">
                                <table class="table table-responsive-sm table-bordered table-striped table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th class='col-1'>No.</th>
                                            <th class='col-1'>Item Code</th>
                                            <th class='col-2'>Drug / Non-Drug Name</th>
                                            <th class='col-2'>Common Name</th>
                                            <th class='col-2'>Packaging Description</th>
                                            <th class='col-1.5'>Total Stock In (SKU)</th>
                                            <th class='col-1.5'>Usage per Year</th>
                                            <th class='col-1'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stocks as $stock)
                                            <tr>
                                                <td>
                                                    <input id="id" type="number" name="id[]" value="{{ $stock->id }}"
                                                        placeholder="{{ $stock->id }}" class="form-control-plaintext"
                                                        readonly>
                                                </td>
                                                <td>{{ $stock->code }}</td>
                                                <td>{{ $stock->name }}</td>
                                                <td>
                                                    <textarea class="form-control" id="common" type="text" name="common[]" value="{{ $stock->name }}"
                                                        placeholder="{{ $stock->common_name }}"
                                                        rows="3">{{ $stock->common_name }}</textarea>
                                                </td>
                                                <td>
                                                    <input class="form-control" id="description" type="text"
                                                        name="description[]" value="{{ $stock->description }}"
                                                        placeholder="{{ $stock->description }}">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="balance" type="number"
                                                        name="balance[]" value="{{ $stock->balance }}"
                                                        placeholder="{{ $stock->balance }}">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="annual_usage" type="number"
                                                        name="annual_usage[]" value="{{ $stock->annual_usage }}"
                                                        placeholder="{{ $stock->annual_usage }}">
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-middle">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-secondary btn-md deleteButton"
                                                            data-toggle="modal" data-target="#deleteModal"
                                                            data-id={{ $stock->id }} data-code={{ $stock->code }}
                                                            data-name="{{ $stock->name }}">
                                                            <i class="cil-trash"></i> </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="row col-lg-12 d-flex justify-content-between">
                                    <div>
                                        <i class="fa fa-align-justify"></i><strong>Stock List</strong>
                                    </div>
                                    <div>
                                        <a href="{{ route('stock.index') }}" class="btn btn-md btn-secondary"
                                            type="button">Return</a>
                                        <button class="btn btn-md btn-primary" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                    </form>

                    <!-- Delete Stock Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">
                                        <strong>Are you sure to delete this item?</strong>
                                    </h5>
                                    <button type="button" class="btn-close" data-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <strong>This action cannot be undone.</strong>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('stock.delete') }}" method="POST" id="deleteFormStock">
                                        {{ csrf_field() }}
                                        <input id="stock_id" name="stock_id" type="hidden" value="">
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Delete Stock Modal -->

                    <!-- Modal -->
                    <form action="{{ route('stock.create') }}" method="POST" enctype="multipart/form-data"
                        id="createForm" class="need-validation">
                        {{ csrf_field() }}
                        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog"
                            aria-labelledby="addItemModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col">
                                                    <label for="code">Item Code</label>
                                                    <input type="text" class="form-control" id="code" name="code"
                                                        placeholder="Enter item code" required>
                                                    @if ($errors->has('code'))
                                                        <div class="alert alert-danger">{{ $errors->first('code') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <label for="description">Packaging Description</label>
                                                    <input type="text" class="form-control" id="description"
                                                        name="description" placeholder="Enter description">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Drug / Non-Drug Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter name" required>
                                            @if ($errors->has('name'))
                                                <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="common_name">Common Name</label>
                                            <input type="text" class="form-control" id="common_name" name="common_name"
                                                placeholder="Enter common name">
                                        </div>
                                        <div class="form-row">
                                            <div class="col">
                                                <label for="usage">Annual Usage</label>
                                                <input type="number" class="form-control" id="usage" name="usage"
                                                    placeholder="Enter number">
                                            </div>
                                            <div class="col">
                                                <label for="balance">Stock Balance</label>
                                                <input type="number" class="form-control" id="balance" name="balance"
                                                    placeholder="Enter number" required>
                                                @if ($errors->has('balance'))
                                                    <div class="alert alert-danger">{{ $errors->first('balance') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" data-dismiss="static">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Modal -->
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- /.row-->

    </div>
@endsection

@section('javascript')
    <script>
        $(document).on('click', '.deleteButton', function(e) {

            e.preventDefault();

            const id = $(this).data('id');
            const code = $(this).data('code');
            const name = $(this).data('name');

            $('#stock_id').val(id);
            $('#deleteModalLabel').text(`Are you sure to delete this item, ${name}?`);
        })
    </script>
@endsection
