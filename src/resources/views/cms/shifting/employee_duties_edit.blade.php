
@extends('layouts.default')

@section('title')
    Edit Duties Employee
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
                        <h4 class="card-title">  Edit Duty Schedule</h4>
                        <form method="POST" action="{{route('cms.shifting.duties-update',$data->employee_duty_id)}}" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="placement_type_id">Placement Type</label>
                                    <input type="hidden" name="placement_type_id" id="placement_type_id" value="{{$data->placement_type_id}}">
                                    <select name="placement_type_id" id="placement_type_id" class="form-control placement_type_id select2" disabled>
                                        <option value="">Select One</option>
                                        @foreach($placement_type as $val)
                                            <option {{ (old("placement_type_id", $data->placement_type_id) == $val->placement_type_id) ? "selected" : "" }} value="{{ $val->placement_type_id }}">{{$val->type_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('placement_type_id'))
                                        <span class="help-block">{{ $errors->first('placement_type_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1 placement">
                                    <label class="input-required" for="placement_id">Placement</label>
                                    <input type="hidden" name="placement_id" id="placement_id" value="{{$data->placement_id}}" disabled>
                                    <select name="placement_id" id="placement_id" class="form-control placement_id select2">
                                        <option value="">Select One</option>
                                        @foreach($placements as $val)
                                            <option {{ (old("placement_id", $data->placement_id) == $val->placement_id) ? "selected" : "" }} value="{{ $val->placement_id }}">{{$val->placement_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('placement_id'))
                                        <span class="help-block">{{ $errors->first('placement_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1 vessel">
                                    <label class="input-required" for="placement_vessel_id">Placement</label>
                                    <input type="hidden" name="placement_vessel_id" id="placement_vessel_id" value="{{$data->placement_id}}">
                                    <select name="placement_vessel_id" id="placement_vessel_id" class="form-control placement_vessel_id select2" disabled>
                                        <option value="">Select One</option>
                                        @foreach($vessels as $val)
                                            <option {{ (old("placement_id", $data->placement_id) == $val->id) ? "selected" : "" }} value="{{ $val->id }}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('placement_vessel_id'))
                                        <span class="help-block">{{ $errors->first('placement_vessel_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="duty_year">Duty Year</label>
                                    <input type="hidden" name="duty_year" id="duty_year" value="{{$data->duty_year}}">
                                    <select name="duty_year" id="duty_year" class="form-control duty_year select2" required disabled>
                                        <option value="">Select One</option>
                                        @foreach($year as $val)
                                            <option {{ (old("duty_year", $data->duty_year) == $val['value']) ? "selected" : "" }} value="{{ $val['value']}}">{{$val['text']}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('duty_year'))
                                        <span class="help-block">{{ $errors->first('duty_year') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="duty_month">Duty Month</label>
                                    <input type="hidden" name="duty_month" id="duty_month" value="{{$data->duty_month}}">
                                    <select name="duty_month" id="duty_month" class="form-control duty_month select2" required disabled>
                                        <option value="">Select One</option>
                                        @foreach($month as $key=>$val)
                                            <option {{ (old("duty_month", $data->duty_month) == $key) ? "selected" : "" }} value="{{$key }}">{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('duty_month'))
                                        <span class="help-block">{{ $errors->first('duty_month') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="employee_id">Employee</label>
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{$data->employee_id}}">
                                    <select name="employee_id" id="employee_id" class="form-control employee_id select2" disabled>
                                        <option value="">Select One</option>
                                        @if(isset($duties_employee->emp_id))
                                            <option value="{{$duties_employee->emp_id}}" selected>{{isset($duties_employee->emp_name) ? $duties_employee->emp_name.', '.$duties_employee->emp_code : ''}}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('employee_id'))
                                        <span class="help-block">{{ $errors->first('employee_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="joining_date">Joining Date</label>
                                    <input type="text" name="joining_date" id="joining_date"  value="{{\App\Helpers\HelperClass::defaultDateTimeFormat($data->joining_date,'LOCALDATE')}}" class="form-control joining_date"  autocomplete="off" disabled/>
                                </div>

                                <div class="col-md-3 mb-1">
                                    <label class="input-required" for="off_day">Off Day<span
                                            class="required"></span></label>
                                    <select name="off_day[]" id="off_day" class="form-control off_day select2" multiple="multiple" style="width: 100%;">
                                        @foreach($all_month_days as $all_day)
                                            @if(in_array($all_day['value'],$off_days))
                                                <option @if(in_array($all_day['value'],$off_days)) selected @endif value="{{$all_day['value']}}">{{$all_day['value']}}</option>
                                            @else
                                                <option value="{{$all_day['value']}}">{{$all_day['value']}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('off_day'))
                                        <span class="help-block">{{ $errors->first('off_day') }}</span>
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
                                            class="bx bx-save"></i>{{ isset($data->employee_offday_id)?' Update':' Save' }}
                                    </button>
                                    <a type="reset" href="{{route("cms.shifting.duties-create")}}"
                                       class="btn btn-outline-dark {{($data->employee_offday_id)?'mr-1':''}}"><i
                                            class="bx bx-window-close"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
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
            // datePicker("#joining_date");
            $('.employee_id').select2({
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
                                    text: obj.emp_name.concat(', ',obj.emp_code),
                                };
                            })
                        };
                    },
                    cache: false
                },
            });

            $("#placement_type_id").change(function(){
                show_hide(this.value);
            });
            var placement_type = $('#placement_type_id').val();
            show_hide(placement_type);
            function show_hide(placement_type) {
                if(placement_type=='1'){
                    $('.placement').hide();
                    $('.vessel').show();
                }else if(placement_type=='2'){
                    $('.placement').show();
                    $('.vessel').hide();
                }else{
                    $('.placement').show();
                    $('.vessel').hide();
                }
            }
        })
    </script>

@endsection



