
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
                                    <h4 class="card-title">Maintenance Inspection</h4>
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
                                                        <label class="input-required">Assigned SSAEN Name</label>
                                                        <input type="text" class="form-control" disabled name="inspector_emp_id" id="inspector_emp_id" value="{{isset($maintenanceReqData->assignedInspector)?$maintenanceReqData->assignedInspector->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>SSAEN Assigned Date</label>
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
                                                        <label class="input-required">Current Maintenance Status</label>
                                                        <input type="text" class="form-control" disabled name="current_status" id="current_status" value="{{isset($maintenanceReqData->status)?\App\Helpers\HelperClass::getReqStatus($maintenanceReqData->status)->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{((Auth::user()->hasPermission('CAN_SAEN_MDA')) && (in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_slipway))) ? 'show' : 'hidden' }}">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Slipway<span class="required"></span></label>
                                                        <select name="slipway_id" {{(in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_slipway))?'':$isDisabled}}  class="form-control select2">
                                                            <option value="">Select one</option>
                                                            @forelse($slipways  as $slipway)
                                                                <option {{ (old("slipway_id", $maintenanceReqData->slipway_id) == $slipway->id) ? "selected" : "" }} value="{{ $slipway->id }}">{{$slipway->name}}</option>
                                                            @empty
                                                                <option value="">Slipway List empty</option>
                                                            @endforelse
                                                        </select>
                                                        @if ($errors->has('slipway_id'))
                                                            <span class="help-block">{{ $errors->first('slipway_id') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>Inspection Date</label>
                                                        <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="inspection_date" data-target-input="nearest">
                                                            <input type="text" name="inspection_date" {{(in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_inspection_job) &&Auth::user()->hasPermission('CAN_SAEN_MDA'))?'':$isDisabled}}  value="{{ old('inspection_date', isset($maintenanceReqData->inspection_date)?date('d/m/Y', strtotime($maintenanceReqData->inspection_date)):'') }}" class="form-control inspection_date" data-target="#inspection_date" data-toggle="datetimepicker" placeholder="Inspection date" autocomplete="off" />
                                                            <div class="input-group-append" data-target="#inspection_date" data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="bx bx-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($errors->has("inspection_date"))
                                                            <span class="help-block">{{$errors->first("inspection_date")}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($maintenanceReqData->status) && in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_inspection_authorized))
                                            <div class="col-md-12">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Inspection Status<span class="required"></span></label>
                                                        <ul class="list-unstyled mb-0 mt-1">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input"  value="{{ old('status','4') }}" {{isset($maintenanceReqData->status) && $maintenanceReqData->status == "4" ? 'checked' : ''}} name="status" id="customRadio1">
                                                                        <label class="custom-control-label" for="customRadio1">Assigned</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" value="{{ old('status','5') }}" {{isset($maintenanceReqData->status) && $maintenanceReqData->status == "5" ? 'checked' : ''}} name="status" id="customRadio2">
                                                                        <label class="custom-control-label" for="customRadio2">Pending</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" {{(Auth::user()->hasPermission('CAN_XEN_MDA'))?'':$isDisabled}} value="{{ old('status','6') }}" {{isset($maintenanceReqData->status) && $maintenanceReqData->status == "6" ? 'checked' : ''}} name="status" id="customRadio3">
                                                                        <label class="custom-control-label" for="customRadio3">Approve</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio" style="display: none">
                                                                        <input type="radio" class="custom-control-input" {{(Auth::user()->hasPermission('CAN_XEN_MDA'))?'':$isDisabled}} value="{{ old('status','7') }}" {{isset($maintenanceReqData->status) && $maintenanceReqData->status == "7" ? 'checked' : ''}} name="status" id="customRadio4">
                                                                        <label class="custom-control-label" for="customRadio4">Recheck</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                        </ul>
                                                        @if ($errors->has('status'))
                                                            <span class="help-block">{{ $errors->first('status') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
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
                                        @if(isset($maintenanceReqData->status) && in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_inspection_job))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Inspection Job<span class="required"></span></label>
                                                        <select name="inspection_job_id" id="inspection_job_id" class="form-control select2">
                                                            <option value="">Select one</option>
                                                            @forelse($inspectionJobs  as $job)
                                                                <option value="{{ $job->id }}">{{$job->name}}</option>
                                                            @empty
                                                                <option value="">Job list empty</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row my-1">
                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <div class="d-flex col">
                                                            <button type="button" name="add_maintenance_job" id="add_maintenance_job" class="btn btn btn-dark shadow mr-1 mb-1">  Add </button>
                                                        </div>
                                                    </div>
                                                    <label></label>
                                                    <div class="col-md-10 my-1" id="response_{{$maintenanceReqData->id}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover job_table">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="hidden">id</th>
                                                            <th class="hidden">Maintenance req id</th>
                                                            <th class="hidden">Job Id</th>
                                                            <th scope="col">Job</th>
                                                            @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_assigned_workshop))
                                                            <th class="hidden">Workshop id</th>
                                                            <th scope="col">Assign Workshop</th>
                                                            <th class="hidden">SSAEN emp id</th>
                                                            <th scope="col">SSAEN</th>
                                                            <th class="hidden">SAEN emp id</th>
                                                            <th scope="col">SAEN</th>
                                                            <th scope="col">Assign Order</th>
                                                            @endif
                                                            @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_inspection_job))
                                                            <th scope="col" class="text-right">Action</th>
                                                            @endif
                                                        </tr>
                                                        </thead>
                                                        <tbody class="addInspections">
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
                                                        <label></label>
                                                        <div class="d-flex justify-content-end col">
                                                            @if(isset($maintenanceReqData->status) &&  !in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::con_not_be_work_in_inspection))
                                                                <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1">Submit</button>
                                                                @endif
                                                            <a type="reset" href="{{route("mwe.operation.request-inspection")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                            <h4 class="card-title">Vessel Inspection List</h4>
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
           datePicker("#inspection_date");
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
                    url:'{{ route('mwe.operation.request-inspection-datatable',1)}}',
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
            $(document).on('click', '#add_maintenance_job', function (e) {
                e.preventDefault();
                var inspection_job_id = $('#inspection_job_id').val();

                if(inspection_job_id==''||inspection_job_id==undefined ||inspection_job_id==null){
                    return  sweetAlert('PLEASE SELECT A JOB')
                }
                var maintenance_req_id = $('#maintenance_req_id').val();
                addMaintenanceInspectionJob(maintenance_req_id,inspection_job_id);
            });

            function addMaintenanceInspectionJob(maintenance_req_id,inspection_job_id) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{route('mwe.operation.request-add-inspection-job')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            maintenance_req_id: maintenance_req_id,
                            inspection_job_id: inspection_job_id
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                document.getElementById("response_"+((data.response.data.P_maintenance_req_id))).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                                $('.addInspections').html(data.html);
                            } else {
                                document.getElementById("response_"+((data.response.data.P_maintenance_req_id))).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                            }

                        }
                    }
                );
            }
            $(document).on('click', '#remove_inspection_job', function (e) {
                e.preventDefault();
                var maintenance_req_id = $('#maintenance_req_id').val();
                var inspection_id = $(this).attr('data-inspection_id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'DELETE',
                        url: 'delete/'+inspection_id,
                        data: {
                            _token: CSRF_TOKEN,
                            maintenance_req_id: maintenance_req_id
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                document.getElementById("response_"+((data.response.data.P_maintenance_req_id))).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                                $('.addInspections').html(data.html);
                            } else {
                                document.getElementById("response_"+((data.response.data.P_maintenance_req_id))).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    +'<strong>'+data.response.status_message+'</strong>'
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                    +'</button></div>';
                            }
                        }
                    }
                );
            });


            /************selected workshop  on change*********/
            $(document).on('change', '.workshop_id', function () {
                let that = $(this);
                let selected_obj = that.find(":selected").attr('data-workshop_info');
                if (selected_obj) {
                    let obj = JSON.parse(selected_obj);
                    console.log(obj,'here obj');
                    $(that).closest('table tr').find('.workshop_ssaen_emp_id').val(obj.in_charged_emp_id);
                    $(that).closest('table tr').find('.workshop_ssaen_emp_name').val(obj.authorization.emp_name);
                    $(that).closest('table tr').find('.workshop_saen_emp_id').val(obj.saen_emp_id);
                    $(that).closest('table tr').find('.workshop_saen_name').val(obj.workshop_saen.emp_name);
                }
            });

        });
    </script>

@endsection



