{{--{{dd($data)}}--}}
@foreach($data as $val)
    <tr>
        <td class="hidden">
            <input type="text" name="job_dtl_id" class="form-control input-sm" value="{{$val->job_dtl_id}}" readonly>
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                                  name="inspector_job_id" value="{{$val->inspector_job_id}}">
        </td>
        <td class="hidden">
            <input type="hidden" class="form-control input-sm"
                   name="inspection_job_type_id" value="{{$val->inspection_job_type_id}}">
        </td>
        <td>{{--<input type="text" class="form-control input-sm"
                                  name="inspection_job_type" value="{{isset($val->name)?$val->name:''}}" readonly>--}}

            <textarea rows="1" wrap="soft"
                      name="inspection_job_type" readonly
                      class="form-control">{!! old('inspection_job_type',isset($val->name) ? $val->name : '')!!}</textarea>

                                  {{--name="inspection_job_type" value="{{isset($val->inspection_job)?$val->inspection_job->name:''}}" readonly>--}}
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="inspection_submitted_by" value="{{isset($val->emp_name)?$val->emp_name:''}}" readonly>
                   {{--name="inspection_submitted_by" value="{{isset($val->inspection_creator)?$val->inspection_creator->employee->emp_name:''}}" readonly>--}}
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="inspection_submitted_date" value="{{\App\Helpers\HelperClass::defaultDateTimeFormat($val->created_at,'LOCALDATE')}}" readonly>
        </td>
        <td>@if($val->attachment)
                <a href="{{ route('mwe.operation.inspection-order-download', [$val->job_dtl_id]) }}"
                   target="_blank"><i class="bx bx-download"></i></a>
            @else --
            @endif
        </td>
        <td>{{--<input type="text" class="form-control input-sm"
                   name="remarks" value="{{isset($val->remarks)?$val->remarks:''}}" readonly>--}}
            <textarea rows="2" wrap="soft"
                      name="remarks" readonly
                      class="form-control">{!! old('remarks',isset($val->remarks) ? $val->remarks : '')!!}</textarea>
        </td>
        <td style="text-align: right">
            <button class="remove btn btn-sm btn-danger" id="remove_inspection_job_by_inspector"
                    data-job_dtl_id="{{$val->job_dtl_id}}" data-inspector_job_id="{{$val->inspector_job_id}}" data-inspection_job="{{$val->name}}" data-inspector_emp_id="{{$val->emp_id}}" type="button">X
                    {{--data-job_dtl_id="{{$val->job_dtl_id}}" data-inspector_job_id="{{$val->inspector_job_id}}" data-inspection_job="{{$val->inspection_job->name}}" data-inspector_emp_id="{{$val->inspection_creator->emp_id}}" type="button">X--}}
            </button>
        </td>
    </tr>
@endforeach




