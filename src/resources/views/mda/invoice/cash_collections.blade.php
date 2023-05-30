@extends('layouts.default')

@section('title')
    Cash collection invoice
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Cash collections list</h3>
                                <div class="table-responsive">
                                    <table class="table table-sm datatable">
                                        <thead>
                                        <tr>
                                            <th>FORM NO</th>
                                            <th>TYPE</th>
                                            <th>VESSEL</th>
                                            <th>PORT DUES  </th>
                                            <th>RIVER DUES  </th>
                                            <th>OTHER DUES TITLE</th>
                                            <th>OTHER DUES AMOUNT</th>
                                            <th>VAT AMOUNT</th>
                                            <th>TOTAL AMOUNT</th>
                                            <th>PERIOD FORM</th>
                                            <th>PERIOD TO</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script>
        $(document).ready(function () {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function(settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url:'{{ route('invoice-cash-collection-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    // {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "form_no"},
                    {"data": "slip_type.name"},
                    {"data": "local_vessel.name"},
                    {"data": "port_dues_amount"},
                    {"data": "river_dues_amount"},
                    {"data": "other_dues_title"},
                    {"data": "other_dues_amount"},
                    {"data": "vat_amount"},
                    {"data": "total"},
                    {"data": "period_form"},
                    {"data": "period_to"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        });
    </script>

@endsection
