
@extends('layouts.default')

@section('title')
    Vessel
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Vessel List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Vessel Type</th>
                                    <th>Fuel Type</th>
                                    <th>Build Year</th>
                                    <th>GRT</th>
                                    <th>incharge</th>
                                    <th>Reserved Fuel</th>
                                    <th>Status</th>
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
                    url:'{{ route('cms.vessel.user-wise-vessel-datatable') }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "name"},
                    {"data": "vessel_type"},
                    {"data": "fuel_type"},
                    {"data": "build_year"},
                    {"data": "grt"},
                    {"data": "incharge"},
                    {"data": "reserved_fuel"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
            $('#incharge_emp_id').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('cms.ajax.search-employee')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search_param: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (obj) {
                                return {
                                    id: obj.emp_id,
                                    text: obj.emp_name.concat(', ',obj.emp_code),
                                };
                            })
                        };
                    },
                    cache: false
                },
            });
        });
    </script>

@endsection



