
@extends('layouts.default')

@section('title')
   Maintenance Request
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
  <div class="row">
        <div class="col-12">
            @section('content')
                @php
                   $isDisabled='disabled';
                @endphp
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">Maintenance Workshop Order </h4>
                                    <form method="POST" action="" enctype="multipart/form-data">
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
                                                        <input type="hidden" name="prv_status" id="prv_status" value="{{$maintenanceReqData->status}}">
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
                                                        <label class="input-required">Vessel Master</label>
                                                        <input type="text" class="form-control" disabled name="vessel_master_id" id="vessel_master_id" value="{{isset($maintenanceReqData->vesselMaster)?$maintenanceReqData->vesselMaster->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Assigned SSAE(Project) Name</label>
                                                        <input type="text" class="form-control" disabled name="inspector_emp_id" id="inspector_emp_id" value="{{isset($maintenanceReqData->assignedInspector)?$maintenanceReqData->assignedInspector->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>SSAE(Project) Assigned Date</label>
                                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="inspector_assigned_date" data-target-input="nearest">
                                                            <input type="text" name="inspector_assigned_date" disabled value="{{ old('inspection_date', empty($maintenanceReqData->inspector_assigned_date)?'': date('d/m/Y', strtotime($maintenanceReqData->inspector_assigned_date))) }}" class="form-control inspector_assigned_date" data-target="#inspector_assigned_date" data-toggle="datetimepicker"  />
                                                            <div class="input-group-append" data-target="#inspector_assigned_date" data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Is Schedule Request</label>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" disabled value="{{ old('is_schedule_request','A') }}" {{isset($maintenanceReqData->is_schedule_request) && $maintenanceReqData->is_schedule_request == "Y" ? 'checked' : ''}} name="is_schedule_request" id="customRadio2">
                                                                        <label class="custom-control-label" for="customRadio2">Yes</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" disabled value="{{ old('is_schedule_request','I') }}" {{isset($maintenanceReqData->is_schedule_request) && $maintenanceReqData->is_schedule_request == "N" ? 'checked' : ''}} name="is_schedule_request" id="customRadio1">
                                                                        <label class="custom-control-label" for="customRadio1">No</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Assigned Slipway</label>
                                                        <input type="text" class="form-control" disabled value="{{isset($maintenanceReqData)? $maintenanceReqData->slipwayName->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
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
                                                        <label>Attachment</label>
                                                        <div class="row">
                                                            @if(isset($maintenanceReqData->id))
                                                                <div class="col-md-3">
                                                                    @if(isset($maintenanceReqData->attachment)?$maintenanceReqData->attachment->title:'' == "MAINTENANCE_REQUEST")
                                                                        @if($maintenanceReqData->attachment->files != "")
                                                                            <a target="_blank" class="form-control"
                                                                               style="text-align: center;"
                                                                               href="{{ route('local-vessel-download-media',$maintenanceReqData->attachment->id)}}"><i
                                                                                    class="bx bx-download"></i></a>
                                                                            <input type="hidden" name="pre_attachment_id"
                                                                                   value="{{ isset($maintenanceReqData->attachment->id) ? $maintenanceReqData->attachment->id : '' }}">
                                                                        @else
                                                                            <span class="form-control" style="text-align: center;">No file found</span>
                                                                            <input type="hidden" name="pre_attachment_id"
                                                                                   value="{{ isset($maintenanceReqData->attachment->id) ? $maintenanceReqData->attachment->id : '' }}">
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="display: none">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Assigned SAE(Mechanical)<span class="required"></span></label>
                                                        <select name="sae_mechanical_emp_id" id="sae_mechanical_emp_id" class="form-control">
                                                            @if(isset($assigned_sae_mechanical->emp_id))
                                                                <option value="{{$assigned_sae_mechanical->emp_id}}" selected>{{isset($assigned_sae_mechanical->emp_name) ? $assigned_sae_mechanical->emp_name.' - '.$assigned_sae_mechanical->emp_code : ''}}</option>
                                                            @endif
                                                            <option value="">Select one</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-10 my-1" id="response">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover job_table">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="hidden">id</th>
                                                            <th class="hidden">Maintenance req id</th>
                                                            <th class="hidden">Job Id</th>
                                                            <th scope="col" style="width:150px">Job No</th>
                                                            <th scope="col" style="width:200px">Job</th>
                                                            @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_assigned_workshop))
                                                                <th class="hidden">Workshop id</th>
                                                                <th scope="col" style="width:100px">Assign Workshop</th>
                                                                <th scope="col" style="width:100px">SAEN INCHARGE</th>
                                                                <th scope="col" style="width:20px">Priority Order</th>
                                                            @endif
{{--                                                            <th scope="col" class="text-right">Action</th>--}}
                                                        </tr>
                                                        </thead>
                                                        <tbody class="div_workshop_job">
                                                        @include('mwe.partial.inspection_jobs')
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 offset-8 float-right">
                                                <div class="row my-1 ">
                                                    <div class="col-md-12">
                                                        <div class="d-flex justify-content-end col">
                                                            @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_send_job_order_to_workshop) && \Illuminate\Support\Facades\Auth::user()->hasPermission('CAN_EDIT_WORKSHOP_ORDER_VIEW_MDA')=='true')
                                                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">Submit</button>
                                                            @endif
                                                            <a type="reset" href="{{route("mwe.operation.workshop-order")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Maintenance Workshop Order List</h4>
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
                                            <th>Assigned by</th>
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
        </div>
    </div>
@endsection

@section('footer-script')
    <!--Load custom script-->
    <script>
        $(document).ready(function () {
            $(document).on("change",'.workshop_id', function (e) {
                e.preventDefault();
                let val = $(this).find('option:selected').data('workshop_info');
                if(val){
                    let emp_name = $(this).find('option:selected').data('workshop_info').workshop_saen.emp_name +' ('+ $(this).find('option:selected').data('workshop_info').workshop_saen.emp_code+')';
                    $(this).closest('tr').find('.set_name').val(emp_name);
                }else{
                    $(this).closest('tr').find('.set_name').val('');
                }

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
                    url:'{{ route('mwe.operation.workshop-order-datatable',1)}}',
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
                    {"data": "assigned_inspector"},
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

            $('#sae_mechanical_emp_id').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('mwe.ajax.search-maintenance-saen')}}',
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
                                    text: obj.emp_name+' - '+obj.emp_code,
                                };
                            })
                        };
                    },
                    cache: false
                },
            });

            $(document).on('click', '#remove_inspection_job', function (e) {
                e.preventDefault();
                var maintenance_req_id = $('#maintenance_req_id').val();
                var inspection_id = $(this).attr('data-inspection_id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'DELETE',
                        url: '{{route('mwe.operation.delete-inspection-job')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            maintenance_req_id: maintenance_req_id,
                            inspection_id:inspection_id,
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                document.getElementById("response").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                                $('.div_workshop_job').html(data.html);
                            } else {
                                document.getElementById("response").innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                            }
                        }
                    }
                );
            });
        });
    </script>

@endsection



