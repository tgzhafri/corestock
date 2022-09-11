<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="fade-in">
            <!-- /.row-->
            <div class="row">
                <!-- /.col-->
                <div class="col">
                    <div class="card tableFixHead border-0 small">
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
                                <td colspan="4">
                                    <strong>Date</strong>
                                    <span class="medium text-muted">{{ session()->get('start_date') }}</span>
                                </td>
                                <td colspan="4">
                                    <strong>Closing Date</strong>
                                    <span class="medium text-muted">{{ session()->get('closing_date') }}</span>
                                </td>
                            </tr>
                        </table>

                        <table class="table table-responsive-sm table-striped table-bordered table-sm table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th style="width: 10%">Item Code</th>
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 20%">Common Name</th>
                                    <th style="">Qty</th>
                                    <th style="width: 20%">Suppliers</th>
                                    <th style="width: 20%">Remark</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @if ($stocks->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">No data found</td>
                                    </tr>
                                @endif
                                @foreach ($stocks as $index => $stock)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stock->code }}</td>
                                        <td>{{ $stock->name }}</td>
                                        <td>{{ $stock->common_name }}</td>
                                        <td>
                                            <div class="clearfix">
                                                <div class="float-left">50000</div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach ($stock->supplier as $supplier)
                                                <div>
                                                    <li>{{ $supplier->name }}</li>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>{{ $stock->remark }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
</body>

</html>
