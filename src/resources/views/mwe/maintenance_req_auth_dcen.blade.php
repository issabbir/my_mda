@extends('layouts.default')

@section('title')
    Maintenance Request Authorization For Deputy Chief Engineer
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
                        <h4 class="card-title">Maintenance Request Authorization For Deputy Chief Engineer</h4>
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
                                                value="{{isset($data->request_number)?$data->request_number:''}}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Department</label>
                                            <input type="hidden" name="maintenance_req_id" id="maintenance_req_id"
                                                   value="{{$data->id}}">
                                            <select name="department_id" class="form-control select2" disabled>
                                                <option value="">Select one</option>
                                                @forelse($departments  as $department)
                                                    <option
                                                        {{ (old("department_id", $data->department_id) == $department->id) ? "selected" : "" }} value="{{ $department->id }}">{{$department->name}}</option>
                                                @empty
                                                    <option value="">Department List empty</option>
                                                @endforelse
                                            </select>
                                            @if ($errors->has('department_id'))
                                                <span class="help-block">{{ $errors->first('department_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Vessel</label>
                                            <select name="vessel_id" id="vessel_id" class="form-control select2"
                                                    disabled>
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
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Vessel Master </label>
                                            <select name="vessel_master_id" id="vessel_master_id"
                                                    class="form-control select2" disabled>
                                                @if(isset($vesselMaster->emp_id))
                                                    <option value="{{$vesselMaster->emp_id}}"
                                                            selected>{{isset($vesselMaster->emp_name) ? $vesselMaster->emp_name : ''}}</option>
                                                @endif
                                                <option value="">Select one</option>
                                            </select>
                                            @if ($errors->has('vessel_master_id'))
                                                <span class="help-block">{{ $errors->first('vessel_master_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{--                                @if(($data->deputy_chief_eng_app_status)!=null)--}}
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status</label>
                                            {{--                                                <input type="hidden" name="current_status" id="current_status" value="{{isset($data->status)?$data->status:''}}">--}}
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('deputy_chief_eng_app_status','Y') }}"
                                                                   {{isset($data->deputy_chief_eng_app_status) && $data->deputy_chief_eng_app_status == "Y" ? 'checked' : ''}}
                                                                   name="deputy_chief_eng_app_status"
                                                                   checked
                                                                   id="customRadio2">
                                                            <label class="custom-control-label" for="customRadio2">Approve</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset
                                                        style="display: {{(isset($data->status) && $data->status==1)?'':'none' }}">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="{{ old('deputy_chief_eng_app_status','R') }}"
                                                                   {{isset($data->deputy_chief_eng_app_status) && $data->deputy_chief_eng_app_status == "R" ? 'checked' : ''}}
                                                                   name="deputy_chief_eng_app_status"
                                                                   id="customRadio1">
                                                            <label class="custom-control-label" for="customRadio1">Reject</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            @if ($errors->has('deputy_chief_eng_app_status'))
                                                <span
                                                    class="help-block">{{ $errors->first('deputy_chief_eng_app_status') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Attachment</label>
                                            <div class="row">
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
                                                                <span class="form-control" style="text-align: center;">No file found</span>
                                                                <input type="hidden" name="pre_attachment_id"
                                                                       value="{{ isset($data->attachment->id) ? $data->attachment->id : '' }}">
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
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Description</label>
                                            <textarea type="text" name="description" placeholder="Description" @if(isset($data->description)) readonly @endif
                                            class="form-control"
                                                      oninput="this.value = this.value.toUpperCase()">{{ old('description', $data->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Comment</label>
                                            <textarea type="text" name="deputy_chief_eng_comment" placeholder="Comment" @if(isset($data->deputy_chief_eng_comment)) readonly @endif
                                                      class="form-control"
                                                      oninput="this.value = this.value.toUpperCase()">{{ old('deputy_chief_eng_comment', $data->deputy_chief_eng_comment) }}</textarea>
                                            @if ($errors->has('deputy_chief_eng_comment'))
                                                <span
                                                    class="help-block">{{ $errors->first('deputy_chief_eng_comment') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 float-right">
                                    <div class="row my-1 ">
                                        <div class="col-md-12">
                                            <label></label>
                                            <div class="d-flex justify-content-end col">
                                                {{--                                                @if($data->status <= 4 )--}}
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')"
                                                        class="btn btn btn-dark shadow mr-1 mb-1">Submit
                                                </button>
                                                {{--                                                @endif--}}
                                                <a type="reset" href="{{route("mwe.operation.maintenance-request")}}"
                                                   class="btn btn btn-outline-dark mr-1  mb-1"> Cancel</a>
                                                @if(isset($data->status)&& $data->status=='12')
                                                    <a href="" name="certificate_generate" id="certificate_generate"
                                                       class="btn btn btn-success shadow mb-1">Certificate Generate</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                            </div>
                        </form>
                        <form name="report-generator" id="report-generator" method="post" target="_blank"
                              action="{{route('report', ['title' => 'maintenance-certificate'])}}">
                            {{csrf_field()}}
                            <input type="hidden" name="xdo" value="/~weblogic/MWE/RPT_MAINTENANCE_REQUEST_DETAILS.xdo"/>
                            <input type="hidden" name="type" id="type" value="pdf"/>
                            <input type="hidden" name="p_maintenance_req_id" id="p_maintenance_req_id"
                                   value="{{$data->id}}"/>
                            <input type="hidden" name="p_workshop_id" id="p_workshop_id"/>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"> Maintenance Request List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="">
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
                                    <th>Assigned By</th>
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
            $("#certificate_generate").on('click', function (e) {
                e.preventDefault();
                var report_form = $("#report-generator");
                report_form.submit();
            });
        });
        // Show Assign Inspector
        $('input[type=radio][name=status]').change(function () {
            if (this.value == '2') {
                $(".approv").css("display", "block");
            } else if (this.value == '3') {
                $(".approv").css("display", "none");
            }
        });

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
            $('#inspector_emp_id').select2({
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
                                    text: obj.emp_name,
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



