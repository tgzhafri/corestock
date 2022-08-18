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

                    <div class="card-header bg-transparent border-bottom-0">
                        <form action="{{ route('stock.save') }}" method="POST" enctype="multipart/form-data" id="formSave">
                            {{ csrf_field() }}
                            <div class="row d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-md btn-primary" data-toggle="modal"
                                        data-target="#addItemModal">
                                        <strong>Add Supplier</strong>
                                    </button>
                                </div>
                                <div>
                                    <a href="{{ url()->previous() }}" class="btn btn-md btn-secondary"
                                        type="button">Return</a>
                                </div>
                            </div>
                    </div>
                    <div class="card tableFixHead">
                        <table class="table table-responsive-sm table-borderless table-striped table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th class=''>No.</th>
                                    <th class='col-1'>Item Code</th>
                                    <th class='col-3'>Drug / Non-Drug Name</th>
                                    <th class='col-2'>Common Name</th>
                                    <th class='col-2'>Packaging Description</th>
                                    <th class='col-4'>Suppliers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $index => $stock)
                                    <tr>
                                        <td>
                                            <input id="id" type="number" name="id[]" value="{{ $stock->id }}"
                                                placeholder="{{ $stock->id }}" class="form-control-plaintext" hidden
                                                readonly>
                                            {{ $index + 1 }}
                                        </td>
                                        <td>{{ $stock->code }}</td>
                                        <td>{{ $stock->name }}</td>
                                        <td> {{ $stock->common_name }}</td>
                                        <td>{{ $stock->description }}</td>
                                        <td>
                                            @foreach ($stock->supplier as $supplier)
                                                <div>
                                                    <form action="{{ route('supplier.destroy') }}" method="POST">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="supplier_id"
                                                            value="{{ $supplier->id }}">
                                                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                                                        <h6><button type="button"
                                                                class="mr-1 btn btn-sm btn-secondary deleteButton"
                                                                id="deleteButton" data-toggle="modal"
                                                                data-target="#deleteModal" data-id={{ $supplier->id }}
                                                                data-name="{{ $supplier->name }}">
                                                                <i class="cil-trash"></i>
                                                            </button>
                                                            {{ $supplier->name }}</h6>
                                                    </form>
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
                        </form>
                        <!-- Delete Supplier Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-danger">
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
                                        <form action="{{ route('supplier.destroy') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input id="supplier_id" name="supplier_id" type="hidden">
                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Supplier Modal -->

                        <!-- Add supplier Modal -->
                        <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data"
                            id="createForm" class="needs-validation">
                            {{ csrf_field() }}
                            <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog"
                                aria-labelledby="addItemModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-primary" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addItemModalLabel">Add New Supplier </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="supplier-body">
                                            <div class="form-group">
                                                <label for="code">Item</label>
                                                <select class="form-control" id="stock-list" name="stock_id">
                                                    <option selected value="">Select item here</option>
                                                    @foreach ($stocks as $stock)
                                                        <option id="itemList" value="{{ $stock->id }}">
                                                            {{ $stock->code }} - {{ $stock->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="supplier">Supplier</label>
                                                <button type="button" id="add-field" class="btn btn-secondary btn-sm">
                                                    <i class='cil-plus'></i></button>
                                                <input type="text" class="form-control supplier" id="supplier"
                                                    name="supplier[]" placeholder="Enter supplier name" required>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary"
                                                data-dismiss="static">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- add supplier Modal -->
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->

        </div>
    @endsection

    @section('javascript')
        <script>
            // {start} for adding and removing supplier field
            function createFieldComponent() {
                var elements = [];
                rootElement = document.createElement('div');
                rootElement.setAttribute('class', 'form-group');

                elements.push(
                    `<div class="form-row supplier_field">
                    <div class="col-11">
                        <input type="text" class="form-control" id="supplier" name="supplier[]" placeholder="Enter supplier name" required>
                    </div>
                    <div class="col-1">
                        <button type="button" id="minus-field" class="btn btn-secondary btn-sm" onclick="removeInputField(this);">
                        <i class='cil-minus'></i></button>
                    </div>
                 </div>`
                );

                rootElement.innerHTML = elements.join('');

                return rootElement;
            }

            function onClickCreateFieldButton(event) {
                var button = event.target,
                    container = document.querySelector('#supplier-body'),

                    component = createFieldComponent();

                container.appendChild(component);
            }

            function removeInputField(selectedField) {
                selectedField.closest('.supplier_field').remove();
            }

            var addFieldButton = document.getElementById('add-field');
            addFieldButton.addEventListener('click', onClickCreateFieldButton);
            // {end} for adding and removing supplier field


            // {start} for deleting supplier
            $(document).on('click', '.deleteButton', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#supplier_id').val(id);
                $('#deleteModalLabel').text(`Are you sure to delete this supplier, ${name}?`);
            })
            // {end} for deleting supplier
        </script>
    @endsection
