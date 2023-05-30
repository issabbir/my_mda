{{--{{dd($data)}}--}}
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
        <td>{{--<input type="text" class="form-control input-sm"
                   name="workshop[inspector_job_number][]"
                   value="{{$val->job_number}}"
                   --}}{{--value="{{$val->maintenance_inspector->inspector_job_number}}"--}}{{--
                   readonly="readonly">--}}
            <textarea wrap="soft" rows="1"
                      name="workshop[inspector_job_number][]" readonly
                      class="form-control">{!! old('job_number',isset($val->job_number) ? $val->job_number : '')!!}</textarea>
        </td>
        <td>{{--<input type="text" class="form-control input-sm"
                   name="workshop[job_name][]" value="{{$val->inspection_job->name}}"
                   readonly="readonly">--}}
            <textarea wrap="soft" rows="1"
                      name="workshop[job_name][]" readonly
                      class="form-control">{!! old('job_name',isset($val->inspection_job->name) ? $val->inspection_job->name : '')!!}</textarea>
        </td>
        @if(isset($maintenanceReqData->status) &&  in_array($maintenanceReqData->status,App\Enums\Mwe\ConfigRole::can_be_assigned_workshop))
            <td>
                @if(isset($val->workshop_id))
                    <select name="workshop[workshop_id][]" class="form-control workshop_id" id="workshop_id_{{$val->id}}" data-target="#workshop_id_{{$val->id}}" @if(isset($val->workshop_id)) style="pointer-events: none;" @endif>
                        <option value="" data-workshop_info="">Select one</option>
                        @forelse($workshops  as $workshop)
                            <option
                                {{ (old("workshop_id", $val->workshop_id) == $workshop->id) ? "selected" : "" }} value="{{ $workshop->id }}"
                                data-workshop_info="{{$workshop}}">{{$workshop->name}}</option>
                        @empty
                            <option value="">Workshop list empty</option>
                        @endforelse
                    </select>
                    {{--<input type="hidden" name="workshop[workshop_id][]" id="workshop_id_{{$val->id}}" value="{{ $workshop->id }}">--}}
                @else
                    <select name="workshop[workshop_id][]" class="form-control workshop_id" id="workshop_id_{{$val->id}}" data-target="#workshop_id_{{$val->id}}" data-target="#workshop_id_{{$val->id}}">
                        <option value="" data-workshop_info="">Select one</option>
                        @forelse($workshops  as $workshop)
                            <option
                                {{ (old("workshop_id", $val->workshop_id) == $workshop->id) ? "selected" : "" }} value="{{ $workshop->id }}"
                                data-workshop_info="{{$workshop}}">{{$workshop->name}}</option>
                        @empty
                            <option value="">Workshop list empty</option>
                        @endforelse
                    </select>
                @endif

            </td>
            <td>
                {{--<input type="text" class="form-control input-sm set_name" value="{{isset($val->workshop->workshop_saen->emp_name) ? $val->workshop->workshop_saen->emp_name : ''}}" readonly="readonly">--}}
                <textarea wrap="soft" rows="2" readonly
                          class="form-control input-sm set_name">{!! old('job_number',isset($val->workshop->workshop_saen->emp_name) ? $val->workshop->workshop_saen->emp_name : '')!!}</textarea>
            </td>
            <td>
                <input type="text" class="form-control input-sm"
                       name="workshop[workshop_sl_no][]" value={{$val->workshop_sl_no}}>
            </td>
        @endif
{{--        <td style="text-align: right">--}}
{{--            <button class="remove btn btn-sm btn-danger" id="remove_inspection_job" name="remove_inspection_job"--}}
{{--                    data-inspection_id={{$val->id}} type="button">X--}}
{{--            </button>--}}
{{--        </td>--}}
    </tr>
@endforeach




