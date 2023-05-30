
@extends('layouts.default')

@section('title')
   Workshop Requisition
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
                    <h4 class="card-title"> Requisition List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Request Number</th>
                                    <th>Request Date & Time</th>
                                    <th>Department</th>
                                    <th>Vessel</th>
                                    <th>VESSEL MASTER</th>
                                    <th>Inspection by</th>
                                    <th>Status</th>
                                    {{--<th>Assigned By</th>--}}
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
           // datePicker("#expDate");

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
                    url:'{{ route('mwe.operation.workshop-requisition-datatable')}}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "request_number"},
                    {"data": "created_at"},
                    {"data": "department"},
                    {"data": "vessel"},
                    {"data": "vessel_master"},
                    {"data": "assigned_inspector"},
                    {"data": "status"},
                    /*{"data": "inspector_assigned_by_emp_id"},*/
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



