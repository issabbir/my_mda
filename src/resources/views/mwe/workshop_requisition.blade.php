
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
            @section('content')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">Workshop Requisition</h4>
{{--                                    <form method="POST" action="" enctype="multipart/form-data">--}}
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Request Number</label>
                                                        <input
                                                            type="text"
                                                            name="request_number"
                                                            id="request_number"
                                                            class="form-control"
                                                            value="{{isset($maintenanceReqData->request_number)?$maintenanceReqData->request_number:''}}"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Department</label>
                                                        <input type="text" class="form-control" disabled name="department_id" id="department_id" value="{{isset($maintenanceReqData->department)?$maintenanceReqData->department->name:''}}">
                                                        <input type="hidden" class="form-control"  name="maintenance_req_id" id="maintenance_req_id" value="{{isset($maintenanceReqData)?$maintenanceReqData->id:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel</label>
                                                        <input type="text" class="form-control" disabled name="vessel_id" id="vessel_id" value="{{isset($maintenanceReqData->vessel)?$maintenanceReqData->vessel->name:''}}">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Current Maintenance Status</label>
                                                        <input type="text" class="form-control" disabled name="current_status" id="current_status" value="{{isset($maintenanceReqData->status)?\App\Helpers\HelperClass::getReqStatus($maintenanceReqData->status)->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Workshop Name</label>
                                                        <input type="text" class="form-control" disabled name="workshop_id" id="workshop_id" value="{{isset($workshop)?$workshop->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel Master</label>
                                                        <input type="text" class="form-control" disabled name="vessel_master_id" id="vessel_master_id" value="{{isset($maintenanceReqData->vesselMaster)?$maintenanceReqData->vesselMaster->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                               <h4 class="card-title"><strong>Task</strong></h4>
                                                @include('mwe.partial.workshop_tasks')
                                            </div>
                                        </div>
{{--                                    </form>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"> Vessel Inspection List</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-sm datatable">
                                            <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Request Number</th>
                                                <th>Department</th>
                                                <th>Vessel</th>
                                                <th>Vessel Master</th>
                                                <th>Status</th>
                                                <th>Assigned by</th>
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
            // datePicker("#expDate");

        $(".collapse").on('show.bs.collapse', function(){
            $(this).prev(".card-header").find(".bx").removeClass("bx bxs-plus-circle").addClass("bx bxs-minus-circle");
        }).on('hide.bs.collapse', function(){
            $(this).prev(".card-header").find(".bx").removeClass("bx bxs-minus-circle").addClass("bx bxs-plus-circle");
        });

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
                    url:'{{ route('mwe.operation.workshop-requisition-datatable',1)}}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "request_number"},
                    {"data": "department"},
                    {"data": "vessel"},
                    {"data": "vessel_master"},
                    {"data": "status"},
                    {"data": "inspector_assigned_by_emp_id"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

            // $(document).on('click', '#add_workshop_req_item', function (e) {
            //     e.preventDefault();
            //     var product_id = $('#product_id').val();
            //     var demand_qty = $('#demand_qty').val();
            //     var unit_id = $('#unit_id').val();
            //     console.log(product_id,demand_qty,unit_id);
            //     addMaintenanceInspectionJob(maintenance_req_id,inspection_job_id);
            // });

            {{--function addMaintenanceInspectionJob(maintenance_req_id,inspection_job_id) {--}}
            {{--    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}
            {{--    $.ajax(--}}
            {{--        {--}}
            {{--            type: 'POST',--}}
            {{--            url: '{{route('mwe.operation.request-add-inspection-job')}}',--}}
            {{--            data: {--}}
            {{--                _token: CSRF_TOKEN,--}}
            {{--                maintenance_req_id: maintenance_req_id,--}}
            {{--                inspection_job_id: inspection_job_id--}}
            {{--            },--}}
            {{--            success: function (data) {--}}
            {{--                if (data.response.status == true) {--}}
            {{--                    $('.addInspections').html(data.html);--}}
            {{--                } else {--}}
            {{--                }--}}

            {{--            }--}}
            {{--        }--}}
            {{--    );--}}
            {{--}--}}

        });
    </script>

@endsection



