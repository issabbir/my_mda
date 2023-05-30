@foreach($data as $val)
    <tr>
        <td class="hidden"><input type="hidden"
                                  name="workshop[inspection_id][]" value={{$val->id}}>
        </td>
        <td class="hidden"><input type="hidden"
                                  name="workshop[maintenance_req_id][]" value={{$val->maintenance_req_id}}>
        </td>
        <td class="hidden"><input type="hidden"
                                  name="workshop[inspection_job_id][]" value={{$val->inspection_job_id}}>
        </td>
        <td><input type="text" class="form-control input-sm"
                   name="workshop[job_name][]" value="{{$val->inspection_job->name}}"
                   readonly="readonly">
        </td>
        @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_assigned_workshop))
            <td>
                <select name="workshop[workshop_id][]" class="form-control select2 workshop_id">
                    <option value="">Select one</option>
                    @forelse($workshops  as $workshop)
                        <option
                            {{ (old("workshop_id", $val->workshop_id) == $workshop->id) ? "selected" : "" }} value="{{ $workshop->id }}" data-workshop_info="{{$workshop}}">{{$workshop->name}}</option>
                    @empty
                        <option value="">Workshop list empty</option>
                    @endforelse
                </select>
            </td>
            <td class="hidden">
                <input type="hidden" class="form-control input-sm workshop_ssaen_emp_id" id="workshop_ssaen_emp_id"
                       name="workshop[workshop_ssaen_emp_id][]"  value={{$val->ssaen_emp_id}}>
            </td>

            <td>
                <input type="text" class="form-control input-sm workshop_ssaen_emp_name" id="workshop_ssaen_emp_name"
                       name="workshop[workshop_ssaen_name][]" readonly value="{{($val->ssaen_info)?$val->ssaen_info->emp_name:''}}">
            </td>
            <td class="hidden">
                <input type="hidden" class="form-control input-sm workshop_saen_emp_id" id="workshop_saen_emp_id"
                       name="workshop[workshop_saen_emp_id][]"  value={{$val->saen_emp_id}}>
            </td>
            <td>
                <input type="text" class="form-control input-sm workshop_saen_name" id="workshop_saen_name"
                       name="workshop[workshop_saen_name][]" readonly value="{{isset($val->saen_info)?$val->saen_info->emp_name:''}}">
            </td>
            <td>
                <input type="text" class="form-control input-sm"
                       name="workshop[workshop_sl_no][]" value={{$val->workshop_sl_no}}>
            </td>
        @endif
        @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_add_inspection_job))
            <td style="text-align: right">
                <button class="remove btn btn-sm btn-danger" id="remove_inspection_job" name="remove_inspection_job"
                        data-inspection_id={{$val->id}} type="button">X
                </button>
            </td>
        @endif
    </tr>
@endforeach




