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
            <form action="{{ route('report.show') }}" method="GET">
                <thead class="thead-dark">
                    <tr id="tableHeader">
                        <th>No.</th>
                        <th class="col-1">Item Code</th>
                        <th class="col-3">Name</th>
                        <th class="col-3">Common Name</th>
                        <th class="col-1">Qty Required
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
                        <th class="col-2">Suppliers</th>
                        <th class="col-2">Remark</th>
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
                </thead>
            </form>
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
                        <td>
                            @if (number_format($stock['annual_usage'] - $stock['balance']) <= 0)
                                {{ 0 }}
                            @else
                                {{ number_format($stock['annual_usage'] - $stock['balance']) }}
                            @endif
                        </td>
                        <td>
                            @foreach ($stock['supplier'] as $supplier)
                                <div>
                                    <li>{{ $supplier->name }}</li>
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $stock['remark'] }}</td>
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
            </tbody>
        </table>
    </div>
@endsection

@section('javascript')
    @include('stock.searchTable')
@endsection
