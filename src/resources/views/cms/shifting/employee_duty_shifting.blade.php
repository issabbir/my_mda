
@extends('layouts.default')

@section('title')
    Employee Duty Shifting Setting
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('cms.shifting.partial.employee_info')
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">Shift Setting</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->emp_duty_shifting_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="shift_id">Shift<span
                                            class="required"></span></label>
                                    <input type="hidden" id="emp_duty_shifting_id" name="emp_duty_shifting_id" value="{{$data->emp_duty_shifting_id}}">
                                    <input type="hidden" id="employee_duty_id" name="employee_duty_id" value="{{(app('request')->get('employee_duty_id'))?app('request')->get('employee_duty_id'):$data->employee_duty_id}}">
                                   <select class="form-control select2 shift_id" name="shift_id" id="shift_id">
                                       <option value="">Select One</option>
                                       @foreach($shift as $val)
                                           <option {{ (old("shift_id", $val->shifting_id) == $data->shift_id) ? "selected" : "" }} value="{{ $val->shifting_id }}">{{$val->shift_name.', Date Range : '.\App\Helpers\HelperClass::defaultDateTimeFormat($val->effective_from_date,'LOCALDATE').' - '.\App\Helpers\HelperClass::defaultDateTimeFormat($val->effective_to_date,'LOCALDATE').', Time Range: '.$val->shifting_start_time.' - '.$val->shifting_end_time}}</option>
                                       @endforeach
                                   </select>
                                    @if ($errors->has('shift_id'))
                                        <span class="help-block">{{ $errors->first('shift_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="effective_from_date">Effective From Date<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="effective_from_date" data-target-input="nearest">
                                        <input type="text" name="effective_from_date"  value="{{ old('effective_from_date', $data->effective_from_date) }}" class="form-control effective_from_date" data-target="#effective_from_date" data-toggle="datetimepicker" placeholder="Shifting Form Date" autocomplete="off" />
                                        <div class="input-group-append" data-target="#effective_from_date" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("effective_from_date"))
                                        <span class="help-block">{{$errors->first("effective_from_date")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="effective_to_date">Effective To Date<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="effective_to_date" data-target-input="nearest">
                                        <input type="text" name="effective_to_date"  value="{{ old('effective_to_date', $data->effective_to_date) }}" class="form-control effective_from_date" data-target="#effective_to_date" data-toggle="datetimepicker" placeholder="Shifting To Date" autocomplete="off"  />
                                        <div class="input-group-append" data-target="#effective_to_date" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("effective_to_date"))
                                        <span class="help-block">{{$errors->first("effective_to_date")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required">Status<span class="required"></span></label>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','A') }}"
                                                           {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} name="status"
                                                           id="customRadio1" checked="">
                                                    <label class="custom-control-label"
                                                           for="customRadio1">Active</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2 mb-1">
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           value="{{ old('status','I') }}"
                                                           {{isset($data->status) && $data->status == 'I' ? 'checked' : ''}} name="status"
                                                           id="customRadio2">
                                                    <label class="custom-control-label"
                                                           for="customRadio2">Inactive</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    </ul>
                                    @if ($errors->has('status'))
                                        <span class="help-block">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="save" id="submit"
                                            class="btn btn-dark shadow mr-1"><i
                                            class="bx bx-save"></i>{{ isset($data->emp_duty_shifting_id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.shifting.duties")}}"
                                       class="btn btn-outline-dark {{($data->emp_duty_shifting_id)?'mr-1':''}}"><i
                                            class="bx bx-window-close"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Shifting List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Shift Name</th>
                                    <th>Effective From Date</th>
                                    <th>Effective To Date</th>
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
            datePicker("#effective_from_date");
            datePicker("#effective_to_date");
            let path=window.location.pathname;
            let employee_duty_id=GetParameterValues('employee_duty_id');
            let edit_employee_duty_id=$('#employee_duty_id').val();
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
                    url:'{{route("cms.shifting.shift-datatable")}}',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data':{
                        'employee_duty_id':(employee_duty_id)?employee_duty_id:edit_employee_duty_id
                    },
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "shift"},
                    {"data": "formatted_effective_from_date"},
                    {"data": "formatted_effective_to_date"},
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
            function GetParameterValues(param) {
                var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < url.length; i++) {
                    var urlparam = url[i].split('=');
                    if (urlparam[0] == param) {
                        return urlparam[1].replace("#/","");
                    }
                }
            }
        })
    </script>

@endsection



