@foreach($data as $val)
    <tr>
        <td><input type="text" name="inspector_job_number" class="form-control input-sm" value="{{$val->inspector_job_number}}" readonly>
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                                  name="assigned_ssae_emp_id" value="{{$val->assigned_ssae_emp_id}}">
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="assigned_ssae_emp_name" value="{{isset($val->assigned_ssae)?$val->assigned_ssae->emp_name:''}}" readonly>
        </td>
        <td class="hidden"><input type="hidden" class="form-control input-sm"
                                  name="assigned_sae_emp_id" value="{{$val->assigned_sae_emp_id}}">
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="assigned_sae_emp_name" value="{{isset($val->assigned_sae)?$val->assigned_sae->emp_name:''}}" readonly>
        </td>
        <td style="text-align: right">
            <button class="btn btn-sm btn-outline-primary" id="view_inspection_report"
                    data-toggle="collapse" data-target="#collapse_{{$val->inspector_job_id}}" aria-expanded="false" aria-controls="collapse_{{$val->inspector_job_id}}"
                    data-inspector_job_id={{$val->inspector_job_id}} type="button">View inspection
            </button>
            <button class="remove btn btn-sm btn-danger" id="remove_inspector" name="remove_inspector"
                    data-inspector_job_id={{$val->inspector_job_id}} type="button">Remove
            </button>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table class="table table-sm table-secondary">
                <thead class="thead-light">
                <tr>
                    <th class="hidden">job Dtl id</th>
                    <th class="hidden">inspector job id</th>
                    <th class="hidden">inspection job type id</th>
                    <th>inspection job name</th>
                    <th>inspection submitted by</th>
                    <th>inspection submitted at</th>
                    <th>Attachment</th>
                    <th>Remarks</th>
                    <th scope="col" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody class="div_inspection_{{$val->inspector_job_id}}"  id="collapse_{{$val->inspector_job_id}}">

                </tbody>
            </table>
        </td>
    </tr>
@endforeach




