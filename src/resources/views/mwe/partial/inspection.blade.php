@if(count($data)>0)
@foreach($data as $val)
    <tr>
        <td class="hidden">
            <input type="text" name="job_dtl_id" class="form-control input-sm" value="{{$val->job_dtl_id}}" readonly>
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                                  name="inspector_job_id" value="{{$val->inspector_job_id}}">
        </td>
        <td>
            <select name="inspector_job_number" id="inspector_job_number" class="form-control input-sm" disabled>
                @foreach($inspection_order_jobs  as $job)
                    <option value="{{ $job->inspector_job_id }}" @if($job->inspector_job_id==$val->inspector_job_id) selected @endif>{{$job->inspector_job_number}}</option>
                @endforeach
            </select>
        </td>
        <td class="hidden">
            <input type="hidden" class="form-control input-sm"
                   name="inspection_job_type_id" value="{{$val->inspection_job_type_id}}">
        </td>
        <td><input type="text" class="form-control input-sm"
                                  name="inspection_job_type" value="{{isset($val->inspection_job)?$val->inspection_job->name:''}}" readonly>
        </td>
        <td>@if($val->attachment)
                <a href="{{ route('mwe.operation.inspection-order-download', [$val->job_dtl_id]) }}"
                   target="_blank"><i class="bx bx-download"></i></a>
            @else --
            @endif</td>
        <td>@if($val->remarks)<input type="text" class="form-control input-sm"
                   name="inspection_job_type"
                   value="{{isset($val->remarks)?$val->remarks:'-'}}"
                   readonly>@else --
            @endif
        </td>
        <td style="text-align: right">
            <button class="remove btn btn-sm btn-danger" id="remove_inspection_job" name="remove_inspection_job"
                    data-job_dtl_id={{$val->job_dtl_id}} type="button">X
            </button>
        </td>
    </tr>
@endforeach
    @endif




