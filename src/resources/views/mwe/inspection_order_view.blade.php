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
                    @php
                        use Illuminate\Support\Facades\Auth;
                        $getKey = json_encode(Auth::user()->roles->pluck('role_key'));//dd($getKey);
                    @endphp
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">Maintenance Inspection Order </h4>
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
                                                        <input type="text" class="form-control" disabled
                                                               name="department_id" id="department_id"
                                                               value="{{isset($maintenanceReqData->department)?$maintenanceReqData->department->name:''}}">
                                                        <input type="hidden" class="form-control"
                                                               name="maintenance_req_id" id="maintenance_req_id"
                                                               value="{{isset($maintenanceReqData)?$maintenanceReqData->id:''}}">
                                                        <input type="hidden" name="prv_status" id="prv_status"
                                                               value="{{$maintenanceReqData->status}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel</label>
                                                        <input type="text" class="form-control" disabled
                                                               name="vessel_id" id="vessel_id"
                                                               value="{{isset($maintenanceReqData->vessel)?$maintenanceReqData->vessel->name:''}}">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Vessel Master</label>
                                                        <input type="text" class="form-control" disabled
                                                               name="vessel_master_id" id="vessel_master_id"
                                                               value="{{isset($maintenanceReqData->vesselMaster)?$maintenanceReqData->vesselMaster->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Assigned SSAEN Name</label>
                                                        <input type="text" class="form-control" disabled
                                                               name="inspector_emp_id" id="inspector_emp_id"
                                                               value="{{isset($maintenanceReqData->assignedInspector)?$maintenanceReqData->assignedInspector->emp_name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>SSAEN Assigned Date</label>
                                                        <div class="input-group date"
                                                             onfocusout="$(this).datetimepicker('hide')"
                                                             id="inspector_assigned_date" data-target-input="nearest">
                                                            <input type="text" name="inspector_assigned_date" disabled
                                                                   value="{{ old('inspection_date', empty($maintenanceReqData->inspector_assigned_date)?'': date('d/m/Y', strtotime($maintenanceReqData->inspector_assigned_date))) }}"
                                                                   class="form-control inspector_assigned_date"
                                                                   data-target="#inspector_assigned_date"
                                                                   data-toggle="datetimepicker"/>
                                                            <div class="input-group-append"
                                                                 data-target="#inspector_assigned_date"
                                                                 data-toggle="datetimepicker">
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
                                                                        <input type="radio" class="custom-control-input"
                                                                               disabled
                                                                               value="{{ old('is_schedule_request','A') }}"
                                                                               {{isset($maintenanceReqData->is_schedule_request) && $maintenanceReqData->is_schedule_request == "Y" ? 'checked' : ''}} name="is_schedule_request"
                                                                               id="customRadio2">
                                                                        <label class="custom-control-label"
                                                                               for="customRadio2">Yes</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input"
                                                                               disabled
                                                                               value="{{ old('is_schedule_request','I') }}"
                                                                               {{isset($maintenanceReqData->is_schedule_request) && $maintenanceReqData->is_schedule_request == "N" ? 'checked' : ''}} name="is_schedule_request"
                                                                               id="customRadio1">
                                                                        <label class="custom-control-label"
                                                                               for="customRadio1">No</label>
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
                                                        <input type="text" class="form-control" disabled
                                                               name="current_status" id="current_status"
                                                               value="{{isset($maintenanceReqData->status)?\App\Helpers\HelperClass::getReqStatus($maintenanceReqData->status)->name:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Inspection Date</label>
                                                        <input type="text" class="form-control" disabled
                                                               value="{{ old('inspection_date', isset($maintenanceReqData->inspection_date)?date('d/m/Y', strtotime($maintenanceReqData->inspection_date)):'') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label class="input-required">Description</label>
                                                        <textarea type="text" name="description"
                                                                  placeholder="Description"
                                                                  class="form-control"
                                                                  readonly>{{ old('description', $maintenanceReqData->description) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>Attachment</label>
                                                        <div class="row">
                                                            @if(isset($maintenanceReqData->id))
                                                                <div class="col-md">
                                                                    @if(isset($maintenanceReqData->attachment)?$maintenanceReqData->attachment->title:'' == "MAINTENANCE_REQUEST")
                                                                        @if($maintenanceReqData->attachment->files != "")
                                                                            <a target="_blank" class="form-control"
                                                                               style="text-align: center;"
                                                                               href="{{ route('local-vessel-download-media',$maintenanceReqData->attachment->id)}}"><i
                                                                                    class="bx bx-download"></i></a>
                                                                            <input type="hidden"
                                                                                   name="pre_attachment_id"
                                                                                   value="{{ isset($maintenanceReqData->attachment->id) ? $maintenanceReqData->attachment->id : '' }}">
                                                                        @else
                                                                            <span class="form-control"
                                                                                  style="text-align: center;">No file found</span>
                                                                            <input type="hidden"
                                                                                   name="pre_attachment_id"
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
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row my-1">
                                                    <div class="col-md-12">
                                                        <label>Inspector Comment</label>
                                                        <textarea type="text" name="inspector_comment" readonly
                                                                  placeholder="Comment" class="form-control"
                                                                  oninput="this.value = this.value.toUpperCase()">{{ old('inspector_comment', $maintenanceReqData->inspector_comment) }}</textarea>
                                                        @if ($errors->has('inspector_comment'))
                                                            <span
                                                                class="help-block">{{ $errors->first('inspector_comment') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($maintenanceReqData->status) && in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_inspection_job))
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="row my-1">
                                                        <div class="col-md-12">
                                                            <label class="input-required">Inspection Job Number<span
                                                                    class="required"></span></label>
                                                            <select name="inspector_job_id" id="inspector_job_id"
                                                                    class="form-control select2 inspector_job_id">
                                                                <option value="{{'MRI+'. $maintenanceReqData->id}}">
                                                                    Select one
                                                                </option>
                                                                @forelse($inspection_order_jobs  as $job)
                                                                    <option value="{{ $job->inspector_job_id }}"
                                                                            @if($job->insp_conf_ssae!='N' && strpos($getKey, "MDA_SAE_M") == TRUE) disabled @endif>{{$job->inspector_job_number}}</option>
                                                                @empty
                                                                    <option value="">Job list empty</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="row my-1">
                                                        <div class="col-md-12">
                                                            <label class="input-required">List of jobs<span
                                                                    class="required"></span></label>
                                                            <select name="inspection_job_type_id"
                                                                    id="inspection_job_type_id"
                                                                    class="form-control select2">
                                                                <option value="">Select one</option>
                                                                @forelse($inspectionJobs  as $job)
                                                                    <option
                                                                        value="{{ $job->id }}">{{$job->name}}</option>
                                                                @empty
                                                                    <option value="">Job Type list empty</option>
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row my-1">
                                                        <div class="col-md-12">
                                                            <label for="order_attachment" class="">Attachment</label>
                                                            <input type="file" class="form-control" id="attachedFile"
                                                                   name="attachedFile" onchange="encodeFileAsURL();"/>
                                                            <input type="hidden" id="converted_file"
                                                                   name="converted_file">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="row my-1">
                                                        <div class="col-md-12">
                                                            <label>Remarks</label>
                                                            <input type="text" class="form-control"
                                                                   name="remarks" id="remarks">
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($maintenanceReqData->insp_conf_ssae=='N')
                                                    <div class="col-md-2">
                                                        <div class="row my-1">
                                                            <div class="col-md-3">
                                                                <label>&nbsp;</label>
                                                                <div class="">
                                                                    <button type="button" name="add_inspection_job"
                                                                            id="add_inspection_job"
                                                                            class="btn btn btn-dark shadow mb-1"> Add
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-9" id="response_">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered table-hover job_table">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th class="hidden">job dtl id</th>
                                                            <th class="hidden">inspector job id</th>
                                                            <th>inspector job number</th>
                                                            <th class="hidden">Job Type Id</th>
                                                            <th scope="col">List of jobs</th>
                                                            <th scope="col">Attachment</th>
                                                            <th scope="col">Remarks</th>
                                                            <th scope="col" class="text-right">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="div_inspections" id="chk_data">
                                                        @if(count($data)>0)
                                                            @foreach($data as $val)
                                                                <tr>
                                                                    <td class="hidden">
                                                                        <input type="text" name="job_dtl_id"
                                                                               class="form-control input-sm"
                                                                               value="{{$val->job_dtl_id}}" readonly>
                                                                    </td>
                                                                    <td class="hidden"><input type="hidden"
                                                                                              class="form-control input-sm"
                                                                                              name="inspector_job_id"
                                                                                              value="{{$val->inspector_job_id}}">
                                                                    </td>
                                                                    <td>
                                                                        <select name="inspector_job_number"
                                                                                id="inspector_job_number"
                                                                                class="form-control input-sm" disabled>
                                                                            @foreach($inspection_order_jobs  as $job)
                                                                                <option
                                                                                    value="{{ $job->inspector_job_id }}"
                                                                                    @if($job->inspector_job_id==$val->inspector_job_id) selected @endif>{{$job->inspector_job_number}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="hidden">
                                                                        <input type="hidden"
                                                                               class="form-control input-sm"
                                                                               name="inspection_job_type_id"
                                                                               value="{{$val->inspection_job_type_id}}">
                                                                    </td>
                                                                    <td><input type="text" class="form-control input-sm"
                                                                               name="inspection_job_type"
                                                                               value="{{isset($val->inspection_job)?$val->inspection_job->name:''}}"
                                                                               readonly>
                                                                    </td>
                                                                    <td>@if($val->attachment)
                                                                            <a href="{{ route('mwe.operation.inspection-order-download', [$val->job_dtl_id]) }}"
                                                                               target="_blank"><i
                                                                                    class="bx bx-download"></i></a>
                                                                        @else --
                                                                        @endif</td>
                                                                    <td>@if($val->remarks)
                                                                            <textarea type="text"
                                                                                      class="form-control"
                                                                                      name="inspection_job_type"
                                                                                      readonly>{{isset($val->remarks)?$val->remarks:'-'}}</textarea>@else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td style="text-align: right">
                                                                        @if(\Illuminate\Support\Facades\Auth::user()->user_id == $val->created_by)
                                                                            @if($val->maintenance_inspector->insp_conf_ssae=='N')
                                                                                <button
                                                                                    class="remove btn btn-sm btn-danger"
                                                                                    id="remove_inspection_job"
                                                                                    name="remove_inspection_job"
                                                                                    data-job_dtl_id={{$val->job_dtl_id}} type="button">
                                                                                    X
                                                                                </button>
                                                                            @else
                                                                                <p>N/A</p>
                                                                            @endif
                                                                        @else
                                                                            @if(\Illuminate\Support\Facades\Auth::user()->emp_id == $val->maintenance_inspector->assigned_ssae_emp_id)
                                                                                <button
                                                                                    class="remove btn btn-sm btn-danger"
                                                                                    id="remove_inspection_job"
                                                                                    name="remove_inspection_job"
                                                                                    data-job_dtl_id={{$val->job_dtl_id}} type="button">
                                                                                    X
                                                                                </button>
                                                                            @else
                                                                                <p>N/A</p>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-4 offset-8 float-right">
                                            <div class="row my-1 ">
                                                <div class="col-md-12">
                                                    <div class="d-flex justify-content-end col">
                                                        {{--                                                            @if(isset($maintenanceReqData->status) &&  !in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::con_not_be_work_in_inspection))--}}
                                                        {{--                                                                <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1">Submit</button>--}}
                                                        {{--                                                                @endif--}}
                                                        @if (strpos($getKey, "MDA_SSAE_M") == TRUE /*&& $ssae_conf!='Y'*/)
                                                            <form onsubmit="return chkTable()"
                                                                action="{{route('mwe.operation.inspection-confirm-by-ssae')}}"
                                                                method="post" enctype="multipart/form-data">
                                                                {!! csrf_field() !!}
                                                                <input type="hidden" value="{{$maintenanceReqData->id}}"
                                                                       name="main_req_id">
                                                                <input type="hidden" name="inspector_job_id"
                                                                       id="inspector_job_id_post">
                                                                <button type="submit"
                                                                        {{--onclick="return confirm('Are you sure?')"--}}
                                                                        class="btn btn btn-dark shadow mb-1"> Submit
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <a type="reset"
                                                           href="{{route("mwe.operation.inspection-order")}}"
                                                           class="btn btn btn-outline-dark ml-1 mb-3"> Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Vessel Inspection Order List</h4>
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

        function chkTable() {
            if ($('#chk_data tr').length == 0) {
                Swal.fire({
                    title: 'Please add at least one inspection job number.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                return true;
            }
        }

        function encodeFileAsURL() {
            var file = document.querySelector('input[type=file]')['files'][0];
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                $("#converted_file").val(baseString);
                console.log(baseString);
            };
            reader.readAsDataURL(file);
        }

        $(document).ready(function () {
            datePicker("#inspection_date");

            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('mwe.operation.inspection-order-datatable',1)}}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
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

            $(document).on('click', '#add_inspection_job', function (e) {
                e.preventDefault();
                let inspector_job_id = $('#inspector_job_id').val();
                let inspection_job_type_id = $('#inspection_job_type_id').val();
                let maintenance_req_id = $('#maintenance_req_id').val();
                let remarks = $('#remarks').val();

                let converted_file = $("#converted_file").val();

                var filePath = $("#attachedFile").val();
                var file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
                //console.log("File Extension ->-> "+file_dd);
                //let file_ext = converted_file.substring(converted_file.indexOf(":") + 1,converted_file.lastIndexOf(";"));


                if (inspection_job_type_id == '' || inspection_job_type_id == undefined || inspection_job_type_id == null) {
                    return sweetAlert('please select an inspection job type')
                }
                if (inspector_job_id == '' || inspector_job_id == undefined || inspector_job_id == null) {
                    return sweetAlert('please select a job number')
                }

                addMaintenanceInspection(inspector_job_id, inspection_job_type_id, maintenance_req_id, remarks, file_ext, converted_file);

                $("#inspector_job_id").val('MRI+' + maintenance_req_id).trigger('change');
                $("#inspection_job_type_id").val('').trigger('change');
                $('#remarks').val('');
            });

            function addMaintenanceInspection(inspector_job_id, inspection_job_type_id, maintenance_req_id, remarks, file_ext, converted_file) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{route('mwe.operation.add-inspection-order-job')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            inspector_job_id: inspector_job_id,
                            inspection_job_type_id: inspection_job_type_id,
                            maintenance_req_id: maintenance_req_id,
                            remarks: remarks,
                            file_ext: file_ext,
                            converted_file: converted_file,
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                document.getElementById("response_").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                                $('.div_inspections').html(data.html);
                            } else {
                                document.getElementById("response_").innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            }
                        }
                    }
                );
            }

            $(document).on('click', '#remove_inspection_job', function (e) {
                e.preventDefault();
                var job_dtl_id = $(this).attr('data-job_dtl_id');
                var inspector_job_id = $(this).attr('data-inspector_job_id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var maintenance_req_id = $('#maintenance_req_id').val();
                $.ajax(
                    {
                        type: 'DELETE',
                        url: '{{route('mwe.operation.remove-inspection-order-job')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            job_dtl_id: job_dtl_id,
                            maintenance_req_id: maintenance_req_id,
                            inspector_job_id: inspector_job_id
                        },
                        success: function (data) {
                            if (data.response.status == true) {
                                document.getElementById("response_").innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                                $('.div_inspections').html(data.html);
                            } else {
                                document.getElementById("response_").innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                                    + '<strong>' + data.response.status_message + '</strong>'
                                    + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    + '<span aria-hidden="true">&times;</span>'
                                    + '</button></div>';
                            }
                        }
                    }
                );
                $("#inspector_job_id").val('MRI+' + maintenance_req_id).trigger('change');
                $("#inspection_job_type_id").val('').trigger('change');
                $('#remarks').val('');
            });

            $(document).on('change', '.inspector_job_id', function () {
                let value = $(this).val();
                $("#inspector_job_id_post").val(value);

                $.ajax({
                    type: 'GET',
                    url: '/mwe/inspection-order/get-data-from-job-no',
                    data: {inspector_job_id: value},
                    success: function (msg) {
                        let job_data = msg.job_data;
                        console.log(job_data);
                        $(".job_table").find("tr:gt(0)").remove();

                        if (job_data.length !== 0) {
                            let markup = '';
                            let attachment = '';
                            let remarks = '';
                            let tag_attach = '';
                            let tag_remarks = '';
                            let tag_cross = '';
                            let job_dtl_id = '';
                            let created_by = '';
                            let insp_conf_ssae = '';
                            let created_by_inspaction = '';

                            $(".job_table > tbody").html("");
                            $.each(job_data, function (i) {
                                attachment = job_data[i].attachment;
                                remarks = job_data[i].remarks;
                                job_dtl_id = job_data[i].job_dtl_id;
                                created_by = job_data[i].created_by;
                                insp_conf_ssae = job_data[i].insp_conf_ssae;
                                created_by_inspaction = job_data[i].created_by_inspaction;

                                let processUrl = '{{url('/mwe/inspection-order/download/:job_dtl_id')}}';
                                processUrl = processUrl.replace(':job_dtl_id', job_dtl_id);

                                if (attachment) {
                                    tag_attach = '<a href=' + processUrl + ' target="_blank"><i class="bx bx-download"></i></a>';
                                } else {
                                    tag_attach = '--';
                                }

                                if (remarks) {
                                    //tag_remarks = '<input type="text" class="form-control input-sm" name="remarks" value=' + remarks + ' readonly>';
                                    tag_remarks = '<textarea type="text" class="form-control input-sm" name="remarks" readonly>' + remarks + '</textarea>';
                                } else {
                                    tag_remarks = '--';
                                }

                                if (insp_conf_ssae == 'N' && created_by_inspaction == 'Y') {
                                    tag_cross = '<button class="remove btn btn-sm btn-danger" id="remove_inspection_job" name="remove_inspection_job" data-job_dtl_id=' + job_data[i].job_dtl_id + ' type="button"> X </button>';
                                } else {
                                    tag_cross = '<p>N/A</p>';
                                }

                                markup += "<tr role='row'>" +
                                    "<td class='hidden'><input type='text' name='job_dtl_id' class='form-control input-sm' value='" + job_data[i].job_dtl_id + "' readonly></td>" +
                                    "<td class='hidden'><input type='hidden' class='form-control input-sm' name='inspector_job_id' value='" + job_data[i].inspector_job_id + "'></td>" +
                                    "<td><input type='text' name='job_dtl_id' class='form-control input-sm' value='" + job_data[i].inspector_job_number + "' readonly></td></td>" +
                                    "<td class='hidden'><input type='hidden' class='form-control input-sm' name='inspection_job_type_id' value='" + job_data[i].inspection_job_type_id + "'></td>" +
                                    "<td><input type='text' class='form-control input-sm' name='inspection_job_type' value='" + job_data[i].name + "' readonly></td>" +
                                    "<td>" + tag_attach + "</td>" +
                                    "<td>" + tag_remarks + "</td>" +
                                    "<td style='text-align: right'>" + tag_cross + "</td></tr>";
                            });
                            $(".job_table tbody").html(markup);
                        } else {
                            $(".job_table").find("tr:gt(0)").remove();
                        }

                    }
                });
            });
            /************selected workshop  on change*********/
            $(document).on('change', '.workshop_id', function () {
                let that = $(this);
                let selected_obj = that.find(":selected").attr('data-workshop_info');
                if (selected_obj) {
                    let obj = JSON.parse(selected_obj);
                    console.log(obj, 'here obj');
                    $(that).closest('table tr').find('.workshop_ssaen_emp_id').val(obj.in_charged_emp_id);
                    $(that).closest('table tr').find('.workshop_ssaen_emp_name').val(obj.authorization.emp_name);
                    $(that).closest('table tr').find('.workshop_saen_emp_id').val(obj.saen_emp_id);
                    $(that).closest('table tr').find('.workshop_saen_name').val(obj.workshop_saen.emp_name);
                }
            });

        });
    </script>

@endsection



