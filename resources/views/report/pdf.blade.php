@section('content')
    @parent
    <div class="card tableFixHead border-0">
        <table class="table table-responsive-sm mb-0 table-bordered">
            <tr>
                <td colspan="8"><strong>SQ Report</strong></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div>For general item:</div>
                </td>
                <td colspan="2">
                    <div>Please specifiy the following info:</div>
                    <div>1. Packaging</div>
                    <div>2. Brand</div>
                    <div>3. MDA Registration No.</div>
                    <div>4. Ready Stock?</div>
                    <div>5. Contact No. PIC</div>
                </td>
                <td colspan="4">
                    <div>1. Please do not supply product with expiry date less than 6 months.</div>
                    <div> 2. LOU must be given if the product expiry date is less than 1 year upon
                        receive of product.</div>
                </td>
            </tr>
            <tr>
                <form action="">
                    <td colspan="4">
                        <strong>Date</strong>
                        <input class="text-muted border-secondary rounded" type="text"
                            value="{{ session()->put('start_date', now()->format('d-M-Y')) ?? now()->format('d-M-Y') }}">
                    </td>
                    <td colspan="4">
                        <strong>Closing Date</strong>
                        <input class="text-muted border-secondary rounded" type="text"
                            value="{{ session()->put('closing_date',now()->addMonth()->format('d-M-Y')) ??now()->addMonth()->format('d-M-Y') }}">
                    </td>
                </form>
            </tr>
        </table>

        <table id="listTable" class="table table-responsive-sm table-striped table-bordered table-outline table-hover mb-0">
            <thead class="thead-dark">
                <tr id="tableHeader">
                    <th>No.</th>
                    <th class="col-1">Item Code</th>
                    <th class="col-3">Name</th>
                    <th class="col-3">Common Name</th>
                    <th class="col-1">Quantity</th>
                    <th class="col-2">Suppliers</th>
                    <th class="col-2">Remark</th>
                </tr>
            </thead>
            <tbody>
                @if ($stocks->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center">No data found</td>
                    </tr>
                @endif
                @foreach ($stocks as $key => $stock)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $stock['code'] }}</td>
                        <td>{{ $stock['name'] }}</td>
                        <td>{{ $stock['common_name'] }}</td>
                        <td>{{ $stock['balance'] - $stock['annual_usage'] }}</td>
                        <td>
                            @foreach ($stock['supplier'] as $supplier)
                                <div>
                                    <li>{{ $supplier->name }}</li>
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $stock['remark'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('javascript')
    @include('stock.searchTable')
@endsection
