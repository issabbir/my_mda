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
            @php
                use Illuminate\Support\Facades\DB;
                if(isset($data)){
                    $sql = "select * from PMIS.EMPLOYEE emp where emp.EMP_ID = :EMP_ID";
                    $emp_data = db::selectOne($sql, ['EMP_ID' => $data->vessel_master_id]);
                }

            @endphp
            @if(Auth::user()->hasPermission('CAN_DOC_MASTER_MDA') || Auth::user()->hasPermission('CAN_DEPUTY_CHIEF_ENG_MDA'))
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Maintenance Request</h4>
                            <form method="POST" action="" enctype="multipart/form-data">
                                {{ isset($data->id)?method_field('PUT'):'' }}
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Department<span
                                                        class="required"></span></label>
                                                <select name="department_id" id="department_id"
                                                        class="form-control select2">
                                                    <option value="">Select One</option>
                                                    @forelse($departments  as $department)
                                                        <option
                                                            {{ (old("department_id", $data->department_id) == $department->id) ? "selected" : "" }} value="{{ $department->id }}">{{$department->name}}</option>
                                                    @empty
                                                        <option value="">Department List empty</option>
                                                    @endforelse
                                                </select>
                                                @if ($errors->has('department_id'))
                                                    <span
                                                        class="help-block">{{ $errors->first('department_id') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Request Number</label>
                                                <input
                                                    type="text"
                                                    name="request_number"
                                                    id="request_number"
                                                    class="form-control"
                                                    value=""
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Vessel<span
                                                        class="required"></span></label>
                                                <select name="vessel_id" id="vessel_id" class="form-control select2">
                                                    @if(isset($vessels[0]->id))
                                                        <option value="{{$vessels[0]->id}}"
                                                                selected>{{isset($vessels[0]->name) ? $vessels[0]->name : ''}}</option>
                                                    @endif
                                                    <option value="">Select one</option>
                                                </select>
                                                @if ($errors->has('vessel_id'))
                                                    <span class="help-block">{{ $errors->first('vessel_id') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{--<div class="col-md-3">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Vessel Master</label>
                                                <input type="text" name="vessel_master_name" id="vessel_master_name"
                                                       class="form-control"
                                                       value="{{isset($vessels[0]->id)?$vessels[0]->emp_name:''}}"
                                                       readonly>
                                            </div>
                                        </div>
                                    </div>--}}

                                    <div class="col-md-3">
                                        <input type="hidden" name="vessel_master_id" id="vessel_master_id"
                                               value="{{isset($vessels[0]->id)?$vessels[0]->incharge_emp_id:''}}">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label for="incharge_emp_id">Vessel Incharge</label>
                                                <select name="incharge_emp_id" id="incharge_emp_id"
                                                        class="form-control incharge_emp_id select2" autocomplete="off">

                                                    @if(isset($data) && isset($emp_data->emp_name))
                                                        <option
                                                            value="{{$data->vessel_master_id}}">{{$emp_data->emp_name.', '.$emp_data->emp_code.''}}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Description<span class="required"></span></label>
                                                <textarea type="text" name="description" placeholder="Description"
                                                          class="form-control"
                                                          oninput="this.value = this.value.toUpperCase()">{{ old('description', $data->description) }}</textarea>
                                                @if ($errors->has('description'))
                                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label>Attachment</label>
                                                <div class="row">
                                                    <div class="{{ isset($data->id) ? 'col-md-9' : 'col-md-12' }}">
                                                        <input type="file" name="attachment"
                                                               value="{{ old('attachment', $data->attachment) }}"
                                                               placeholder="Attachment File" class="form-control"/>
                                                    </div>
                                                    @if(isset($data->id))
                                                        <div class="col-md-3">
                                                            @if(isset($data->attachment)?$data->attachment->title:'' == "MAINTENANCE_REQUEST")
                                                                @if($data->attachment->files != "")
                                                                    <a target="_blank" class="form-control"
                                                                       style="text-align: center;"
                                                                       href="{{ route('local-vessel-download-media',$data->attachment->id)}}"><i
                                                                            class="bx bx-download"></i></a>
                                                                    <input type="hidden" name="pre_attachment_id"
                                                                           value="{{ isset($data->attachment->id) ? $data->attachment->id : '' }}">
                                                                @else
                                                                    <span class="form-control"
                                                                          style="text-align: center;">No file found</span>
                                                                    <input type="hidden" name="pre_attachment_id"
                                                                           value="{{ isset($data->attachment->id) ? $data->attachment->id : '' }}">
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                @if ($errors->has('attachment'))
                                                    <span class="help-block">{{ $errors->first('attachment') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 offset-8 float-right">
                                        <div class="row my-1 ">
                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end col">
                                                    <button type="submit" name="save"
                                                            onclick="return confirm('Are you sure?')"
                                                            class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Send' }} </button>
                                                    <a type="reset"
                                                       href="{{route("mwe.operation.maintenance-request")}}"
                                                       class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        @endif
        <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Maintenance Request List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Request Number</th>
                                    <th>Request Date&Time</th>
                                    <th>Department</th>
                                    <th>Vessel</th>
                                    <th>Vessel Master</th>
                                    <th>Status</th>
                                    {{--<th>Assigned By</th>--}}
                                    <th>Print</th>
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
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '{{ route('mwe.operation.maintenance-request-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "request_number"},
                    {"data": "created_at"},
                    {"data": "department"},
                    {"data": "vessel"},
                    {"data": "vessel_master"},
                    {"data": "status"},
                    /*{"data": "inspector_assigned_by_emp_id"},*/
                    {"data": "print"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                createdRow: function (row, data, index) {
                    if (data['current_status']['status_code'] == 2) {

                    }
                },
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

            $('#vessel_id').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('mwe.ajax.search-vessel')}}',
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
                                    id: obj.id,
                                    text: obj.name,
                                };
                            })
                        };
                    },
                    cache: false
                },
            });

            $("#vessel_id").change(function () {
                showVesselMasterName(this.value);
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
                                    text: obj.emp_name.concat(', ', obj.emp_code),
                                };
                            })
                        };
                    },
                    cache: false
                },
            });

            function showVesselMasterName(id) {
                $.ajax(
                    {
                        type: 'GET',
                        url: '{{route('mwe.ajax-show-vessel-master')}}',
                        data: {
                            id: id,
                        },
                        success: function (data) {
                            document.getElementById('vessel_master_id').value = data[0].incharge_emp_id;
                            document.getElementById('vessel_master_name').value = data[0].emp_name;
                        }
                    }
                );
            }

            getMaintenanceReqNumber($("#department_id").val());
            $("#department_id").change(function () {
                getMaintenanceReqNumber(this.value);
            });

            function getMaintenanceReqNumber(id) {
                $.ajax(
                    {
                        type: 'GET',
                        url: '{{route('mwe.ajax-get-maintenance-req-number')}}',
                        data: {
                            id: id,
                        },
                        success: function (data) {
                            document.getElementById('request_number').value = data;
                        }
                    }
                );
            }
        });
    </script>

@endsection



