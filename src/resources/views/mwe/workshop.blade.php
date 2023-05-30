
@extends('layouts.default')

@section('title')
    Workshop Setting
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
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Workshop</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Name<span class="required"></span></label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data->name) }}" autocomplete="off">
                                            @if($errors->has("name"))
                                                <span class="help-block">{{$errors->first("name")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Location<span class="required"></span></label>
                                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $data->location) }}" autocomplete="off">
                                            @if($errors->has("location"))
                                                <span class="help-block">{{$errors->first("location")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Description<span class="required"></span></label>
                                            <textarea type="text" name="description" placeholder="Description" class="form-control"   oninput="this.value = this.value.toUpperCase()">{{ old('description', $data->description) }}</textarea>
                                            @if($errors->has("description"))
                                                <span class="help-block">{{$errors->first("description")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">SSAEN Name<span class="required"></span></label>
                                            <select name="in_charged_emp_id" id="in_charged_emp_id" class="form-control select2">
                                                @if(isset($inChargeEMP->emp_id))
                                                    <option value="{{$inChargeEMP->emp_id}}" selected>{{isset($inChargeEMP->emp_name) ? $inChargeEMP->emp_name.' - '.$inChargeEMP->emp_code : ''}}</option>
                                                @endif
                                                    <option value="">Select one</option>
                                            </select>
                                            @if($errors->has("in_charged_emp_id"))
                                                <span class="help-block">{{$errors->first("in_charged_emp_id")}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">SAEN Name<span class="required"></span></label>
                                            <select name="saen_emp_id" id="saen_emp_id" class="form-control select2">
                                                @if(isset($saen_employee->emp_id))
                                                    <option value="{{$saen_employee->emp_id}}" selected>{{isset($saen_employee->emp_name) ? $saen_employee->emp_name.' - '.$saen_employee->emp_code : ''}}</option>
                                                @endif
                                                <option value="">Select one</option>
                                            </select>
                                            @if($errors->has("saen_emp_id"))
                                                <span class="help-block">{{$errors->first("saen_emp_id")}}</span>
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
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','A') }}" {{isset($data->status) && $data->status == "A" ? 'checked' : ''}} name="status" id="customRadio2" checked="">
                                                            <label class="custom-control-label" for="customRadio2">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="{{ old('status','I') }}" {{isset($data->status) && $data->status == "I" ? 'checked' : ''}} name="status" id="customRadio1">
                                                            <label class="custom-control-label" for="customRadio1">Inactive</label>
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
                            </div>
                            <div class="row">
                                <div class="offset-8 col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  {{ isset($data->id)?'Update':'Save' }} </button>
                                                <a type="reset" href="{{route("mwe.setting.workshop-setting")}}" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title">Workshop List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>SSAEN Name</th>
                                    <th>SAEN Name</th>
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
                    url: '{{ route('mwe.setting.workshop-setting-datatable', isset($data->id)?$data->id:0 ) }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "name"},
                    {"data": "location"},
                    {"data": "in_charged"},
                    {"data": "saen_emp_id"},
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
            $('#in_charged_emp_id').select2({
                // minimumInputLength: 1,
                ajax: {
                    url: '{{route('mwe.ajax.search-maintenance-ssaen')}}',
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
            $('#saen_emp_id').select2({
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
        })
    </script>

@endsection



