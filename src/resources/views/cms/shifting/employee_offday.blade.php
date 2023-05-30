
@extends('layouts.default')

@section('title')
    Offday
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
                        <h4 class="card-title"> {{ isset($data->employee_offday_id)?'Edit':'Add' }} Offday</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            {{ isset($data->employee_offday_id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="offday_from">Offday From Date<span
                                            class="required"></span></label>
                                    <input type="hidden" name="employee_offday_id" id="employee_offday_id" value="{{$data->employee_offday_id}}">
                                    <input type="hidden" name="employee_duty_id" id="employee_duty_id" value="{{(app('request')->get('employee_duty_id'))?app('request')->get('employee_duty_id'):$data->employee_duty_id}}">
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="offday_from" data-target-input="nearest">
                                        <input type="text" name="offday_from"  value="{{ old('offday_from', $data->offday_from) }}" class="form-control offday_from" data-target="#offday_from" data-toggle="datetimepicker" placeholder="offday from" autocomplete="off" />
                                        <div class="input-group-append" data-target="#offday_from" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("offday_from"))
                                        <span class="help-block">{{$errors->first("offday_from")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="input-required" for="offday_to">Offday To Date<span
                                            class="required"></span></label>
                                    <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="offday_to" data-target-input="nearest">
                                        <input type="text" name="offday_to"  value="{{ old('offday_to', $data->offday_to) }}" class="form-control offday_to" data-target="#offday_to" data-toggle="datetimepicker" placeholder="offday To Date" autocomplete="off"  />
                                        <div class="input-group-append" data-target="#offday_to" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($errors->has("offday_to"))
                                        <span class="help-block">{{$errors->first("offday_to")}}</span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-1">
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
                                            class="bx bx-save"></i>{{ isset($data->employee_offday_id)?' Update':' Save' }}
                                    </button>

                                    <a type="reset" href="{{route("cms.shifting.shift",['employee_duty_id'=>(app('request')->get('employee_duty_id'))?app('request')->get('employee_duty_id'):$data->employee_duty_id])}}"
                                       class="btn btn-outline-dark {{($data->employee_offday_id)?'mr-1':''}}"><i
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
                    <h4 class="card-title">Offday List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Off Day From Date</th>
                                    <th>Off Day To Date</th>
                                    <th>Total Off Day</th>
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
            datePicker("#offday_from");
            datePicker("#offday_to");
            let path=window.location.pathname;
            let employee_duty_id=GetParameterValues('employee_duty_id');
            let duty_shifting_id=GetParameterValues('duty_shifting_id');
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
                    url: '{{ route("cms.offday.datatable") }}',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data':{
                        'employee_duty_id':(employee_duty_id)?employee_duty_id:edit_employee_duty_id,
                        'duty_shifting_id':duty_shifting_id
                    },
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "formatted_offday_from"},
                    {"data": "formatted_offday_to"},
                    {"data": "total_offday"},
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



