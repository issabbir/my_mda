
@extends('layouts.default')

@section('title')
   Docking Maintenance Schedule Setting
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Docking Maintenance Schedule</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Department<span class="required"></span></label>
                                            <select name="department_id" id="department_id" class="form-control select2">
                                                <option value="">Select one</option>
                                                @foreach($departments as $dept)
                                                    <option {{ ( old("department_id", $data->department_id) == $dept->id) ? "selected" : ""  }} value="{{$dept->id}}">{{$dept->name}}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has("department_id"))
                                                <span class="help-block">{{$errors->first("department_id")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Vessel Name<span class="required"></span></label>
                                            <select name="vessel_id" id="vessel_id" class="form-control select2">
                                                @if(isset($vessels[0]->id))
                                                    <option value="{{$vessels[0]->id}}" selected>{{isset($vessels[0]->name) ? $vessels[0]->name : ''}}</option>
                                                @endif
                                                <option value="">Select one</option>
                                            </select>
                                            @if($errors->has("vessel_id"))
                                                <span class="help-block">{{$errors->first("vessel_id")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Vessel Master</label>
                                            <input type="text" name="vessel_master" id="vessel_master" class="form-control" readonly value="{{isset($vessels[0]->id)?$vessels[0]->emp_name:''}}">
                                            <input type="hidden" name="vessel_master_id" id="vessel_master_id" class="form-control"  value="{{isset($vessels[0]->id)?$vessels[0]->vessel_master_emp_id:''}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Doc Master <span class="required"></span></label>
                                            <select name="doc_master_id" id="doc_master_id" class="form-control select2">
                                                @if(isset($doc_master->emp_id))
                                                    <option value="{{$doc_master->emp_id}}" selected>{{isset($doc_master->emp_name) ? $doc_master->emp_name : ''}}</option>
                                                @endif
                                                <option value="">Select one</option>
                                            </select>
                                            @if ($errors->has('doc_master_id'))
                                                <span class="help-block">{{ $errors->first('doc_master_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Last Docking Date</label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="last_maintenance_at" data-target-input="nearest">
                                                <input type="text" name="last_maintenance_at" disabled value="{{ old('last_maintenance_at', $data->last_maintenance_at) }}" class="form-control last_maintenance_at" data-target="#last_maintenance_at" data-toggle="datetimepicker" placeholder="Last docking date"  />
                                                <div class="input-group-append" data-target="#last_maintenance_at" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("last_maintenance_at"))
                                                <span class="help-block">{{$errors->first("last_maintenance_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Next Docking Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="next_maintenance_at" data-target-input="nearest">
                                                <input type="text" name="next_maintenance_at" value="{{ old('next_maintenance_at', $data->next_maintenance_at) }}" class="form-control next_maintenance_at" data-target="#next_maintenance_at" data-toggle="datetimepicker" placeholder="Next docking date"/>
                                                <div class="input-group-append" data-target="#next_maintenance_at" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("next_maintenance_at"))
                                                <span class="help-block">{{$errors->first("next_maintenance_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
{{--                                @if(isset($data->status))--}}
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Last Un-Docking Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="last_undocking_at" data-target-input="nearest">
                                                <input type="text" name="last_undocking_at" value="{{ old('last_undocking_at', $data->last_undocking_at) }}" class="form-control last_undocking_at" data-target="#last_undocking_at" data-toggle="datetimepicker" placeholder="last un-docking date"/>
                                                <div class="input-group-append" data-target="#last_undocking_at" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($errors->has("next_maintenance_at"))
                                                <span class="help-block">{{$errors->first("next_maintenance_at")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                        <div class="row my-1">
                                            <div class="col-md-12">
                                                <label class="input-required">Status<span class="required"></span></label>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="{{ old('status','P') }}" {{(isset($data->status) && $data->status == 'P')  ? 'checked' : ''}} name="status" id="customRadio2" checked="">
                                                                <label class="custom-control-label" for="customRadio2">Published</label>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2 mb-1">
                                                        <fieldset>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" value="{{ old('status','I') }}" {{(isset($data->status) && $data->status == 'I') ? 'checked' : ''}} name="status" id="customRadio1">
                                                                <label class="custom-control-label" for="customRadio1">Draft</label>
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
{{--                                @endif--}}
                                <div class="col-md-4 offset-8">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("mwe.setting.maintenance-schedule")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Docking Maintenance Schedule List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Vessel</th>
                                    <th>Department</th>
                                    <th>Last Docking Date</th>
                                    <th>Last UnDocking Date</th>
                                    <th>Next Docking Date</th>
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
            datePicker("#last_maintenance_at");
            datePicker("#next_maintenance_at");
            datePicker("#last_undocking_at");

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
                    url:'{{ route('mwe.setting.maintenance-schedule-datatable', isset($data->id)?$data->id:0 ) }}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "vessel"},
                    {"data": "department"},
                    {"data": "last_maintenance_at"},
                    {"data": "last_undocking_at"},
                    {"data": "next_maintenance_at"},
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

            $("#vessel_id").select2().on("change", function () {
                $(".last_maintenance_at").val("");

                let vessel_id = $("#vessel_id").find(":selected").val();
                console.log(vessel_id)
                if (vessel_id !== null) {
                    $.ajax({
                        dataType:'JSON',
                        url: '/mwe/maintenance-schedule-setting/get-last-maintenance-date/' + vessel_id,
                        cache: true,
                        success:function (data) {
                            console.log(data["last_maintenance_date"],'data')
                            $(".last_maintenance_at").val(data["last_maintenance_date"]);
                            $(".next_maintenance_at").val(data["next_maintenance_date"]);
                        }
                    });
                }
            });

            $('#doc_master_id').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('mwe.ajax.search-doc-master')}}',
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

            $("#vessel_id").change(function(){
                showVesselMasterName(this.value);
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
                            document.getElementById('vessel_master_id').value  =data[0].vessel_master_emp_id;
                            document.getElementById('vessel_master').value  =data[0].emp_name;
                        }
                    }
                );
            }
        });
    </script>

@endsection



